---
type:           "post"
title:          "Le Design Pattern 'Decorator'"
date:           "2017-05-04"
lastModified:       ~

description:    "Présentation du pattern 'Decorator' dans le cadre d'une série consacrée aux Design Patterns"

thumbnail:      "images/posts/thumbnails/decorator_pattern.jpg"
banner:     "images/posts/headers/decorator_pattern.jpg"
tags:           ["Design Pattern", "Conception"]
categories:     ["Dev", "Design Pattern"]

authors:            ["xavierr"]

---

Le _Gang of Four_ a classé les design patterns dans trois catégories : les DP de création, les DP structurels et les DP comportementaux (_behavior_).

Au début de cette série, nous avons abordé deux design patterns de création : la [`Factory Method`](./design-pattern-factory-method.md) et le pattern [`Abstract Factory`](./design-pattern-abstract-factory.md). Je vous propose aujourd'hui de nous pencher sur notre premier design pattern structurel : le `Decorator`.

Il y sera notamment question de modération, de sexe et de grossièretés. Autant de bonnes raisons de poursuivre votre lecture.

## Classification

Le `Decorator` appartient à la famille des __design patterns structurels__.

## Définition

> Attach additional responsibilities to an object dynamically. Decorators provide a flexible alternative to subclassing for extending functionality.

Que nous apprend cette définition (GoF) ? Elle nous apprend que le Design Pattern `Decorator` permet d'ajouter __dynamiquement__ des responsabilités à un objet existant et que cette solution apporte plus de __souplesse__ que l'héritage.

Nous allons donc nous efforcer de démontrer ses avantages par rapport à l'héritage.

Noter que le terme `Wrapper` est un synonyme de `Decorator` (GoF).

## Diagramme du Design pattern `Decorator`

<figure>
    <img src="images/posts/design-pattern/structural-decorator.jpg" alt="Le Design Pattern 'Decorator'">
    <figcaption>
      <span class="figure__legend">Le Design Pattern 'Decorator'</span>
    </figcaption>
</figure>

## Participants

* __Component__ est une interface qu'implémentent à la fois l'objet décoré et les objets qui le décorent
* __ConcreteComponent__ représente l'objet décoré (qui implémente `Component`)
* __Decorator__ représente l'interface des décorateurs
* On trouve ensuite les décorateurs concrets (__ConcreteDecoratorA__, etc.)

## Exposé du cas concret (sexe et violence à tous les étages)

Nous allons à présent nous attacher à montrer l'implémentation de ce Design Pattern à travers un cas concret.

Vous avez développé une application Web qui met en relation des vendeurs et des acheteurs. Vous mettez à disposition des utilisateurs une messagerie privée qui permet aux acheteurs et aux vendeurs de communiquer.

Votre application rencontre un franc succès mais des dérives ont été constatées dans l'utilisation de la messagerie (spam, injures, etc.). Il apparaît donc nécessaire de développer un système de modération, automatisé compte tenu des volumes d'échanges à traiter.

Voici le code de la classe responsable de la modération :

```php
<?php

interface ModeratorInterface
{
    public function moderate(string $text): string;
}

class Moderator implements ModeratorInterface
{
    private $replacements;

    public function __construct(array $replacements = [])
    {
        $this->replacements = $replacements;
    }

    public function moderate(string $text): string
    {
        return strtr($text, $this->replacements);
    }
}

```

Exemple d'utilisation :

```php
<?php

$replacements = [
    'abruti' => 'individu aux capacités intellectuelles limitées',
    'nique ta mère' => 'poutou à ta maman',
];

$moderator = new Moderator($replacements);
echo $moderator->moderate("nique ta mère\n"); // Affiche 'poutou à ta maman'

```

Cependant, les administrateurs de l'application se sont aperçus que certains vendeurs profitaient de la messagerie privée pour communiquer leurs coordonnées aux acheteurs afin de traiter en direct et ainsi passer outre les commissions versées au site.

Il nous faut donc renforcer le système de modération pour empêcher les vendeurs de communiquer leurs coordonnées via la messagerie. L'occasion de voir comment le Design Pattern `Decorator` va nous permettre d'enrichir la modération tout en conservant une certaine souplesse.

## Le pattern `Decorator` en action

Nous allons rédiger une classe qui va ajouter des fonctionnalités de modération en décorant notre classe initiale `Moderator` :

```php

<?php

class PhoneNumberModerator implements ModeratorInterface
{
    /** @var ModeratorInterface */
    private $decorated;

    static private $phonePattern = '/[0-9]{10}/';

    public function __construct(ModeratorInterface $baseModerator)
    {
        $this->decorated = $baseModerator;
    }

    public function moderate(string $text): string
    {
        return preg_replace(
            self::$phonePattern,
            '**********',
            $this->decorated->moderate($text)
        );
    }
}
```

Cette classe `PhoneNumberModerator` permet d'obfusquer les numéros de téléphone. Elle accepte en argument de son constructeur une instance de ModeratorInterface qu'elle décore.

Exemple d'utilisation :

```php
    <?php

    $moderator = new PhoneNumberModerator(new Moderator($replacements));
    echo $moderator->moderate("nique ta mère ; appelez-nous au 0600000000.\n");
    // Affiche : "poutou à ta maman ; appelez-nous au **********."

```

Il nous faudra également obfusquer les adresses email et il convient donc de rédiger une classe `EmailAddressModerator` (assez similaire à `PhoneNumberModerator`).

Le code pour instancier les services modérateurs devient donc :

```php
    <?php
    $moderator = new EmailAddressModerator(
        new PhoneNumberModerator(
            new Moderator($replacements)
        )
    );
```

## Avantages du pattern `Decorator` par rapport à l'héritage

Supposons que nous soyons passés par l'héritage pour enrichir notre service de modération, ce qui nous donnerait :

__PhoneNumberModerator__ hérite de __EmailAddressModerator__ qui hérite de __Moderator__.

Outre le fait que la chaîne d'héritage semble un peu forcée (pourquoi __PhoneNumberModerator__ hériterait de __EmailAddressModerator__ ?), la solution de l'héritage manque de souplesse. En effet, ici, utiliser __PhoneNumberModerator__ implique nécessairement d'utiliser __EmailAddressModerator__. Il n'est pas possible d'utiliser nos services _à la carte_, ce que permet le pattern `Decorator`.

En effet, grâce au pattern `Decorator`, toutes les combinaisons de services modérateurs sont possibles, ce que ne permet pas l'héritage (conception _dynamique_ vs _statique_).

Autre avantage du pattern, le chaînage des décorateurs est potentiellement extensible à l'infini. Il est ainsi très facile d'ajouter une nouvelle classe de modération sans qu'il soit nécessaire de modifier la hiérarchie d'héritage.

Enfin, comme la chaîne des décorateurs est construite de manière dynamique lors de leur instanciation, il est possible d'introduire des services de manière conditionnelle. Par exemple, on pourrait ajouter un service de modération qui bannirait les expressions à caractère sexuel, uniquement si l'utilisateur courant est mineur.

Au final, cet exemple est une parfaite illustration de l'avantage de la composition sur l'héritage.

## Autres usages du pattern `Decorator`

Le design pattern `Decorator` est également très utilisé pour ajouter des fonctionnalités de cache à des services existants.

C'est notamment le cas de la classe [`HttpCache`](https://github.com/symfony/symfony/blob/master/src/Symfony/Component/HttpKernel/HttpCache/HttpCache.php) de Symfony :

```php
    <?php
        $kernel = new AppKernel('prod', false);
        // AppCache hérite de HttpCache:
        $kernel = new AppCache($kernel);
```

Source : http://symfony.com/doc/current/http_cache.html#symfony-reverse-proxy

La classe `AppCache` de Symfony implémente l'interface `HttpKernelInterface` (via son parent `HttpCache`), tout comme la classe `AppKernel` qu'elle décore.
