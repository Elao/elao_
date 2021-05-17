---
type:               "post"
title:              "Écrire des tests Behat proches de son domaine"
date:               "2018-06-17"
lastModified:       ~

description:        ""

thumbnail:          "images/posts/thumbnails/ecrire-des-tests-behat-proche-de-son-domaine-thumbnail.png"
banner:             "images/posts/headers/ecrire-test-behat-proche-de-son-domaine.jpg"
tags:               ["Behat","Symfony","DDD"]
categories:         ["Dev", Symfony", "PHP"]

author:    "ndievart"
---

Il y a quelque temps nous publiions un article sur [l'utilisation Behat 3 pour l'écriture des tests fonctionnels Symfony](./behat-3-test-fonctionnel-symfony.md). Depuis les choses ont beaucoup changé sur les différents projets où nous posons du Behat pour nos tests fonctionnels.
Dans cet article nous allons voir comment nous écrivons désormais nos tests en partant d'une approche Domaine.

## Cheminement 📖

L'ajout et le maintien des tests fonctionnels se sont avérés de plus en plus complexes à réaliser sur plusieurs de nos projets avec une grande complexité métier. Certains parcours utilisateur étaient compliqués à mettre en place. Le maintien à jour des fixtures de tests devenait difficile, les dépendances entre les entités testées les rendant encore plus complexes.

Dans de nombreux cas, nous en arrivions à faire une fixture particulière pour chaque test plutôt que de réutiliser certaines d'entre elles pour être totalement maître du contexte. A chaque modification du _model_, la mise à jour de toutes les fixtures étaient une réelle perte de temps.

La plupart des projets chez Elao ont [une architecture hexagonale](./architecture-hexagonale-symfony.md) et sont orientés DDD, Domain Driven Design. Nous avons donc déjà toutes les méthodes métiers nécessaires pour créer des entités pour les contextes qui nous intéressent.

Par exemple, nous avons dans notre classe métier «Produit» des méthodes nous permettant de créer directement des produits de différent _types_ comme des formules. Ces méthodes permettent d'abstraire certaines informations inutiles à faire figurer à chaque endroit du code et simplifient la création de ces produits.
Nos _commands_ utilisent donc déjà ces méthodes pour créer des formules, et sont très flexibles pour chaque besoin différent.

```php
<?php

class Product
{
  public const TYPE_PLAN = 'plan';

  public static function createPlan(
      string $reference,
      int $price,
      float $vat,
      int $stock
  ) {
      return new self(
        self::TYPE_PLAN,
        $reference,
        $price,
        $vat,
        $stock,
        new \DateTime()
      );
  }
}
```

Nous avons initié cette réflexion après avoir rencontré les problèmes cités ci-dessus, mais également en explorant le code source, et notamment les tests fonctionnels du projet [Sylius](https://github.com/Sylius/Sylius/tree/master/features).

Mais, arrêtons de tourner autour du pot. À quoi ressemble un test fonctionnel avec une orientation métier ?

```gherkin
Feature: Manage the plans
  As an Admin, in order to manage my plans, I need to be able to create and update the plans

  Scenario: I can update a plan
    Given the database is purged
    And there is a plan named "Premium" with a price of 100
    And the super admin "admin@example.net" is created
    And I am logged with "admin@example.net"
    When I go to this page "/fr/product/1/update/plan"
    Then the "reference" field should contain "Premium"
    And I fill in the following:
      | reference | Early bird |
      | price     | 20         |
    And I submit the form
    And I should be on this page "/fr/product"
    And the plan "Early bird" must cost 20
```

Nous n'avons plus à _loader_ des fixtures et à les maintenir, maintenant, nous pouvons utiliser un même _step_ (`And there is a plan called "AAAA" with a price of DDD`) pour plusieurs de nos tests fonctionnels, ce qui nous permet de créer des formules dans divers contextes.

> Comment mettre tout cela en place avec Behat ?

## Mise en place 🔧

Tout d'abord, nous avons besoin d'installer Behat en _dev-dependencies_ de notre composer.json

```json
"require-dev": {
    "behat/behat": "^3.1",
    "behat/mink-browserkit-driver": "^1.3",
    "behat/mink-extension": "^2.2",
    "behat/symfony2-extension": "^2.1",
    "webmozart/assert": "^1.1"
}
```

Le point d'entrée de Behat est le fichier `behat.yml.dist` à la racine de notre projet. Afin de déporter l'ensemble de la logique de notre code Behat dans un seul et même endroit, notre fichier `behat.yml.dist` ne sert qu'à importer notre fichier de configuration:


```yaml
imports:
  - features/Behat/Resources/config/default.yml
```

L'architecture des repertoires de nos tests fonctionnels est la suivante:

```
- features/
    - Behat/ contient le code de nos proxys métier et nos contextes
    - product/ contient les tests .features sur les produits
    - user/ contient les tests .features sur les utilisateurs
    - ...
- src/ code métier
```

Le point d'entrée est donc le repertoire `features/` dans lequel nous stockons à la fois nos tests mais aussi nos services et contextes.

Expliquons ensuite comment réaliser un _step_ comme `And there is a plan named "Premium" with a price of 100`.

Nous allons donc créer un _Manager_ qui nous permettra d'appeler nos méthodes de création de produits, de modifier des paramètres, d'appeler les _repositories_ pour persister en base de données ce qui doit l'être etc...

```
features/Behat/Manager/ProductManager.php
```

```php
<?php

namespace App\Tests\Behat\Manager;

class ProductManager
{
    private $productRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository
    ) {
        $this->productRepository = $productRepository;
    }

    public function createPlan(string $reference, int $price): void
    {
         $plan = Product::createPlan(
            $reference,
            $price,
            20,
            100
        );

        $this->productRepository->add($plan);
    }
}
```

Ce _Manager_ utilise la méthode _static_ que nous avons vue précédemment qui est également utilisée dans notre code métier. Nous aurions pu utiliser notre _Command Handler_ métier qui permet de créer une formule et donc ne pas à avoir à dupliquer certaines parties de notre code, mais pour des raisons de simplifications, nous partirons sur cet exemple.

Nous allons ensuite créer un service qui va nous servir de _proxy_, sous la forme d'un passe-plat, pour pouvoir appeler notre _Manager_ dans nos contextes Behat.

```
features/Behat/Proxy/ProductProxy.php
```

```php
<?php

namespace App\Tests\Behat\Proxy;

class ProductProxy
{
    private $productManager;

    public function __construct(
        ProductManager $productManager
    ) {
        $this->productManager = $productManager;
    }

    public function getProductManager(): ProductManager
    {
         return $this->productManager;
    }
}
```

Et enfin, nous allons créer un _ProductContext_ afin de créer notre _step_ Gherkin

```
features/Behat/Context/ProductProxy.php
```

```php
<?php

namespace App\Tests\Behat\Context;

use Behat\Behat\Context\Context;

class ProductContext implements Context
{
    private $productProxy;

    public function __construct(
        ProductProxy $productProxy
    ) {
        $this->productProxy = $productProxy;
    }

    /**
     * @Given there is a plan named :reference with a price of :price
     */
    public function createPlan(string $reference, int $price): void
    {
        $this->productProxy
            ->getProductManager()
            ->createPlan($reference, $price)
        ;
    }
}
```

Ensuite, nous n'avons plus qu'à modifier notre fichier `default.yml` afin de lui spécifier l'utilisation du nouveau contexte que nous venons de créer.

```
features/Behat/Resources/config/default.yml
```

```yaml
default:
    extensions:
        Behat\Symfony2Extension:
            kernel:
               env: test
               bootstrap: 'vendor/autoload.php'
        Behat\MinkExtension:
            base_url:  'http://localhost:8000/app_test.php'
            sessions:
                default:
                    symfony2: ~
    suites:
        default:
            contexts:
                - App\Tests\Behat\Context\ProductContext:
                    - '@App\Tests\Behat\Proxy\ProductProxy'
```

Nous sommes donc maintenant en mesure d'utiliser notre _step_ dans nos features Behat et de créer des formules facilement, sans utiliser de fixtures. Ce qui permet l'évolution de nos tests avec notre code métier.

Maintenant que nos services sont en place, nous pouvons réaliser de nouveaux _steps_ qui nous permettent de tester simplement le fonctionnement de la plateforme.
Nous allons réaliser le _step_ précédent `And the plan "Early bird" must cost 20` qui nous permet de tester que notre formule a bien été modifiée au bon prix.

Nous modifions alors notre _Manager_ afin d'y ajouter la fonction de récupération d'une formule via le _repository_. Notre _ProductContext_ accède donc à la formule et peut tester que son prix a bien été modifié comme nous le souhaitons.

```php
<?php

namespace App\Tests\Behat\Manager;

class ProductManager
{
    // ...

    public function getPlanByReference(string $reference): Product
    {
        return $this->productRepository
            ->getPlanByReference($reference)
        ;
    }
}
```

```php
<?php

namespace App\Tests\Behat\Context;

use Behat\Behat\Context\Context;
use Webmozart\Assert\Assert;

class ProductContext implements Context
{
    // ..

    /**
     * @Given the plan :reference must cost :price
     */
    public function thePlanMustCost(string $reference, int $price)
    {
        $plan = $this->productProxy
            ->getProductManager()
            ->getPlanByReference($reference)
        ;

        Assert::same($plan->getPrice(), $price);
    }
}
```

Et c'est tout, pas besoin de _parser_ le _DOM_ pour retrouver la valeur du prix de la formule et vérifier si il est égale à A ou B. Cela rend les _steps_ Behat beaucoup plus lisibles.

## Passage d'informations entre step 📦

Au fur et à mesure de l'utilisation de ce système, vous vous rendrez compte qu'il manque quelque chose... En effet, les différents _steps_ sont distincts les uns des autres, ne communiquant pas, ils ne peuvent pas utiliser les valeurs des autres _steps_.
Imaginons que vous souhaitez créer une formule "Early bird" et que celle-ci soit disponible uniquement jusqu'à une certaine date. Pour réaliser ce _step_ il vous faudra donc soit créer un nouveau _step_ qui permet de créer une formule avec une référence, un prix et une date de fin de disponibilité. Cela nous fait dupliquer une partie du code précédent et ce n'est pas forcément pertinent.

Pour éviter cela, il est intéresant de pouvoir récupérer un élément du _step_ précédent dans le _step_ suivant afin de modifier certaines valeurs.

Afin de réaliser cette tâche, nous avons ajouté un service qui sert de réceptacle de données entre nos _steps_.
Ce _Storage_ contient simplement un tableau indexé par type de donnée stockée et nous offre l'accès à un getter et un setter pour récupérer ou écraser la donnée.

```
features/Behat/Storage/Storage.php
```

```php
<?php

namespace App\Tests\Behat\Storage;

class Storage
{
    /** @var array */
    private $storage;

    public function set(string $name, $value): void
    {
        $this->storage[$name] = $value;
    }

    public function get($name)
    {
        return $this->storage[$name] ?? null;
    }
}
```

On peut ensuite injecter ce _storage_ à notre _ProductProxy_ et on pourra piocher dans les données pour les modifier.

```php
<?php

namespace App\Tests\Behat\Proxy;

class ProductProxy
{
    private $productManager;
    private $storage

    public function __construct(
        ProductManager $productManager,
        Storage $storage
    ) {
        $this->productManager = $productManager;
        $this->storage = $storage;
    }

    // ...

    public function getStorage(): Storage
    {
         return $this->storage;
    }
}
```

En repartant de notre test fonctionnel en Gherkin, nous pouvons donc avoir les _steps_ suivantes:

```gherkin
  Scenario: I can not buy a plan with an availability date passed
    Given the database is purged
    And there is a plan named "Premium" with a price of 100
    And this plan is not available anymore
    And the user "user@example.net" is created
    And I am logged with "user@example.net"
    When I go to this page "/fr/buy"
    Then I should see "Premium"
    And I can not buy "Premium"
```

Ce qui se retranscrirait dans le code du _ProductManager_ et du _ProductContext_ par:

```php
<?php

namespace App\Tests\Behat\Manager;

class ProductManager
{
    private $productRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository
    ) {
        $this->productRepository = $productRepository;
    }

    public function createPlan(string $reference, int $price): Product
    {
         $plan = Product::createPlan(
            $reference,
            $price,
            20,
            100
        );

        $this->productRepository->add($plan);

        // We now return the plan after the creation
        return $plan;
    }

    public function setAvailability(
        Product $plan,
        ?\DateTimeInterface $availability
    ): void {
        $plan->updateEndOfAvailability($availability);

        $this->productRepository->set($plan);
    }
}
```

```php
<?php

namespace App\Tests\Behat\Context;

use Behat\Behat\Context\Context;
use Webmozart\Assert\Assert;

class ProductContext implements Context
{
    /**
     * @Given there is a plan named :reference with a price of :price
     */
    public function createPlan(string $reference, int $price): void
    {
        // We receive the return of the createPlan function
        $plan = $this->productProxy
            ->getProductManager()
            ->createPlan($reference, $price)
        ;

        // And we set the plan in the storage for further usage
        $this->productProxy->getStorage()->set('plan', $plan);
    }

    // ...

    /**
     * @Given this plan is not available anymore
     */
    public function planNotAvailable(): void
    {
        // We retrieve the previous plan from the storage
        $plan = $this->productProxy->getStorage()->get('plan');

        $this->productProxy
            ->getProductManager()
            ->setAvailability(
                $plan,
                new \DateTime('1999-10-10 10:00:00.000')
            )
        ;
    }
}
```

À la lecture de notre test fonctionnel, nous comprenons tout de suite dans quel contexte nous nous trouvons, avec une formule non disponible, et nous testons qu'elle n'est plus achetable par un utilisateur.

## Axes d'amélioration 🚀

Afin de rendre nos tests fonctionnels encore plus compréhensibles, nous avons de futurs axes d'amélioration comme pouvoir naviguer sur le site sans faire mention des urls qui n'ont pas toujours de notion métier.
Ce qui permettrait la rédaction de _steps_ tel que:

```diff
- When I go to this page "/fr/buy"
+ When I go to the products list
```

De même, la modification d'une entité peut se passer hors des _steps_ prédéfinis par Mink qui remplissent un formulaire, en utilisant un _DataNode_ contextualisé par exemple.

```diff
- And I fill in the following:
-   | reference | Early bird |
-   | price     | 20         |
- And I submit the form
+ And I modify this plan with
+   | price     | 20         |
+   | reference | Early bird |
```

Mais tout ceci demande de coder tous les contextes, les _steps_, les _proxies_, ce qui est très verbeux. Cependant la valeur ajoutée d'avoir une bonne couverture de tests fonctionnels est importante et le temps passé à coder les tests est du temps gagné en débogage.

## En conclusion 🎬

Avant, nous avions beaucoup de tests avec des fixtures lourdes à maintenir qui cachaient une grande partie de ce qui était chargé. Nous avons maintenant des _steps_ qui décrivent le contexte dans lequel le test s'effectue. Le code métier directement utilisé est plus maintenable.

Nos tests sont lisibles ce qui facilite la code review par nos pairs. Ils sont réutilisables pour différents contextes.
