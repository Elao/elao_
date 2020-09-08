---
type:           "post"
title:          "Le Design Pattern 'Chain of Responsibility'"
date:           "2017-05-13"
publishdate:    "2017-05-13"
draft:          false
slug:           "design-pattern-chain-of-responsibility"
description:    "Nouvel article consacré aux Design Patterns. Aujourd'hui : le pattern Chain of Responsibility"

thumbnail:      "/images/posts/thumbnails/chaplin-assembly-line.jpeg"
header_img:     "/images/posts/headers/chaplin-assembly-line.jpg"
tags:           ["Design Pattern", "Conception"]
categories:     ["Dev", "Design Pattern"]

author_username:    "xavierr"

---

>"_Quelqu'un pourrait me passer le sel, s'il vous plaît ?_" (Martin Fowler, sept. 2015)

Aujourd'hui, nous allons nous amuser à enfiler des objets comme des perles grâce au Design Pattern `Chain of Responsibility`.
<!--more-->

## Classification

Selon la classification établie par le _GoF_ (_Gang of Four_), la `chaîne de responsabilité` appartient aux Design patterns comportementaux (_behavior_).

## Définition

> Avoid coupling the sender of a request to its receiver by giving more than one object a chance to handle the request. Chain the receiving objects and pass the request along the chain until an object handles it.

En résumé, le DP _CoR_ va permettre à son consommateur de transmettre une requête à une chaîne d'objets susceptibles de la traiter, sans avoir conscience de l'objet effectivement responsable du traitement (_handler_).

Concrètement, le pattern consiste principalement à construire une chaîne de _handlers_ chargée de traiter une requête (aka _command_).

## Quand l'utiliser ?

Voici les différentes situations que référence le _GoF_ :

* il existe plusieurs objets susceptibles de traiter une requête, mais l'objet qui traitera la requête (_handler_) n'est pas connu _a priori_ (mais doit être déterminé automatiquement par la chaîne)
* vous souhaitez transmettre une requête à un objet parmi plusieurs, sans le désigner explicitement
* la liste des objets susceptibles de traiter la requête devrait être construite dynamiquement

## Diagramme du Design pattern `Chain of Responsibility`

<p class="text-center">
    {{< figure class="text-center" src="/images/posts/design-pattern/behavior-cor.gif" alt="Le Design Pattern 'Chain of Responsibility'">}}
    <figcaption style="text-align: center;font-style: italic;">Diagramme du Design Pattern 'Chain of Responsility'</figcaption>
</p>

## Participants

* Le __Handler__ :
    * définit une interface que les handlers devront implémenter
    * peut implémenter la logique de chaînage des successeurs
* Les __Concrete Handlers__ :
    * traitent les requêtes dont ils sont responsables
    * peuvent accéder à leur successeur dans la chaîne
    * si un _handler_ sait traiter une requête, il le fait ; dans le cas contraire, il la transmet à son successeur
* Le __client__ est l'émetteur de la requête, qu'il passe à la chaîne

## _Si tous les fous du monde voulaient bien se donner la main ..._

> Illustrer un Design Pattern à l'aide d'un exemple pertinent ou un tant soit peu réaliste n'est pas toujours chose aisée. Aussi, notez que l'exemple qui suit est <strike>honteusement</strike> largement inspiré d'un article publié sur le blog [spaghetti.io](http://spaghetti.io/cont/article/a-chain-of-responsibility-implementation-inside-the-symfony-container/15/1.html#.WRcQErzyi-w). Je vous invite à consulter cet article puisque l'auteur ne se contente pas de présenter le Design Pattern, mais propose un exemple d'implémentation dans un contexte Symfony, ainsi que des pistes très intéressantes pour construire la chaîne et la configurer dans le DIC (_Dependency Injection Container_).

Vous maintenez une application de _e-commerce_ dont le catalogue de produits est mis à jour à l'aide d'imports. Les fichiers d'import des produits à intégrer au catalogue proviennent de plusieurs sources et ne sont donc pas standardisés : il existe des sources de données au format XML, d'autres au format JSON et enfin certaines au format CSV.

Votre importeur doit donc supporter ces trois formats et pour cela, il va s'appuyer sur trois extracteurs spécialisés :

* ProductJsonExtractor
* ProductXmlExtractor
* ProductCsvExtractor

> Vous l'aurez sans doute deviné, si l'on se réfère au diagramme des participants, notre importeur correspond au __Client__ tandis que les trois extracteurs correspondent aux __Concrete Handlers__.

Voici à quoi ressemble un _extractor_ avant que nous n'appliquions le pattern _CoR_ :

```php
    <?php
    class ProductXmlExtractor implements ProductExtractorInterface {

        public function extractProducts(\SplFileObject $file): array {
            $content = $file->fread($file->getSize());
            $xml = simplexml_load_string($content);
            $products = [];
            foreach ($xml->productList->product as $productNode) {
                $product = new ProductDto(
                    (integer) $productNode->id,
                    (string) $productNode->name,
                    (string) $productNode->description,
                    (float) $productNode->price
                );
                $products[] = $product;
            }

            return $products;
        }
    }
```

Rien de compliqué, cette classe reçoit un fichier en entrée et en extrait une liste de produits.

Noter que pour l'heure, `ProductXmlExtractor` (tout comme les deux autres _extractors_) implémente une interface `ProductExtractorInterface` qui expose simplement une méthode `extractProducts`. J'utilise également pour les valeurs de retour une classe `ProductDto` (c'est un bête conteneur de données, pardonnez-moi le pléonasme).

Voici à présent ce que nous allons faire :

* [construire la chaîne dans une classe abstraite dédiée](#chain-construct)
* [mettre à jour les _handlers_ concrets](#concrete-handlers)
* [instancier la chaîne et la consommer](#chain-usage)

## <a name="chain-construct"></a> Logique de construction de la chaîne

> "Tu aimeras ton prochain comme toi-même"

Nous allons faire évoluer notre classe concrète `ProductXmlExtractor` : au lieu d'implémenter l'interface `ProductExtractorInterface`, elle va en effet étendre une classe abstraite chargée notamment de construire la chaîne des _handlers_ et d'exposer les méthodes métiers attendues par ses héritiers. Voici le code de cette classe abstraite `AbstractProductExtractor`, abondamment commentée pour expliquer le principe :

```php
<?php
/**
 * This is the base class for any concrete handler.
 * It includes the methods common to all handlers, amongst which
 * the ones responsible for building and managing the chain (Here, the
 * constructor expects an AbstractProductExtractor
 * instance injected in its $nextHandler property).
 *
 * All the "magic" is done in the extractProducts method : if the current
 * instance can handle the request, then it handles it,
 * otherwise it delegates the request to the next handler in the chain.
 */
abstract class AbstractProductExtractor
{
    /** @var AbstractProductExtractor|null */
    private $nextHandler;

    /**
     * We use the constructor to build the handler chain. Each handler points to one follower.
     * The last handler of the chain is the one that has a null $nextHandler.
     *
     * @param AbstractProductExtractor|null $nextHandler
     */
    public function __construct(AbstractProductExtractor $nextHandler = null)
    {
        $this->nextHandler = $nextHandler;
    }

    /**
     * Helper method that enables to inject a new handler in the chain through
     * any element of the chain.
     * If the current handler already has a follower, the new handler is
     * propagated till the last handler of the chain.
     *
     * @param AbstractProductExtractor $nextHandler
     */
    public function setNextHandler(AbstractProductExtractor $nextHandler)
    {
        if (null === $this->nextHandler {
            $this->nextHandler = $nextHandler;
            return;
        }

        $this->nextHandler->setNextHandler($nextHandler);
    }

    /**
     * The main exposed method that does the job. It handles the import file
     * if it supports its format.
     * Else it transmits the request to the next handler.
     *
     * @param \SplFileObject $file
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException If no handler can handle the request
     */
    public function extractProducts(\SplFileObject $file)
    {
        if ($this->support($file)) {
            return $this->handle($file);
        }

        if (null !== $this->nextHandler) {
            // !!! Do not do the following:
            // return $this->handle($file)
            // Indeed, you must re-enter the current method in order to make sure that the
            // handler supports the $file passed as an argument.
            return $this->nextHandler->extractProducts($file);
        }

        // Here, you must throw an exception if you want to make sure that the request is handled
        throw new \InvalidArgumentException("No handler found for file '{$file->getFilename()}'");
        // Otherwise, you could return false
        // return false;
    }

    /**
     * The main business logic of the class: opens the import file passed as
     * an argument and parses its content.
     *
     * @param  SplFileObject $file
     *
     * @return ProductDto[]
     */
    protected function handle(\SplFileObject $file): array
    {
        // The following line of code is only for debug & demo purposes:
        echo static::CLASS." is the handler for {$file->getExtension()} files\n";
        $content = file_get_contents($file->getRealPath());

        return $this->parseContent($content);
    }

    abstract protected function support(SplFileObject $file): bool;
    abstract protected function parseContent($content): array;
}

```

En résumé, la construction de la chaîne consiste à introduire dans chaque _extractor_ concret une propriété __`$nextHandler`__ de type `AbstractProductExtractor`.

Le dernier maillon de la chaîne est celui dont sa propriété `$nextHandler` est à _null_. Les méthodes de construction et de gestion de la chaîne étant communes à tous les _extractors_ concrets, elles sont factorisées dans notre classe abstraite.

Enfin, c'est la méthode `support`, qui retourne un booléen, qui permet de déterminer si un _handler_ concret sait traiter une requête ou s'il doit la passer à son successeur.

## <a name="concrete-handlers"></a> Mise à jour des _handlers_ concrets

La classe abstraite `AbstractProductExtractor` se chargeant de gérer la chaîne, les _handlers_ concrets vont continuer à faire ce qu'ils savent faire le mieux, à savoir extraire des données à partir d'un fichier source.

Voici les modifications à apporter à notre extracteur `ProductXmlExtractor` :

```php
    <?php
    class ProductXmlExtractor extends AbstractProductExtractor {
        /**
         * {@inheritdoc}
         */
        protected function support(SplFileObject $file): bool {
            return $file->getExtension() === 'xml';
        }

        /**
         * {@inheritdoc}
         */
        protected function parseContent($content): array {
            $xml = simplexml_load_string($content);
            // ... Le reste sans changement ...
            return $products;
        }
    }
```

Désormais, la classe `ProductXmlExtractor` étend la classe abstraite `AbstractProductExtractor` et implémente donc les deux méthodes attendues (`support` et `parseContent`).

## <a name="chain-usage"></a> Instancions et utilisons notre chaîne

Enfin (et ce n'est pas trop tôt, me direz-vous), voici le code qui permet d'instancier et consommer notre chaîne de _handlers_ :

```php
    <?php
    // Firstly, build the chain of handlers:
    $handlerChain = new ProductCsvExtractor();
    $handlerChain->setNextHandler(new ProductXmlExtractor());
    $handlerChain->setNextHandler(new ProductJsonExtractor());
    // Alternative method:
    // new ProductCsvExtractor(new ProductXmlExtractor(new ProductJsonExtractor()));

    // Secondly, use it:
    $xmlFile = new \SplFileObject('/path/to/products.xml');
    $products = $handlerChain->extractProducts($xmlFile);

    $csvFile = new \SplFileObject('/path/to/products.csv');
    $products = $handlerChain->extractProducts($csvFile);

    $jsonFile = new \SplFileObject('/path/to/products.json');
    $products = $handlerChain->extractProducts($jsonFile);

    // Thirdly, try to parse an unsupported format:
    try {
        $unsupportedFile = new \SplFileObject('/path/to/unsupported/format/file');
        $handlerChain->extractProducts($unsupportedFile);
    } catch (\InvalidArgumentException $e) {
        echo "Exception (as expected !) : {$e->getMessage()}\n";
    }
```

