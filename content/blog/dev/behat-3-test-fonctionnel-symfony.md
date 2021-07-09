---
type:               "post"
title:              "Behat 3 pour vos tests fonctionnels Symfony2"
date:               "2016-01-06"
lastModified:       ~

description:        "Installation et utilisation de Behat 3 pour vos tests fonctionnels Symfony2"

thumbnail:          "images/posts/thumbnails/behat.png"
banner:             "images/posts/headers/behat_cover.jpg"
tags:               ["Behat","Symfony","Mink","Alice"]
categories:         ["Dev", Symfony", "PHP"]

authors:            ["ndievart"]
---

Depuis peu, nous avons fait basculer notre [stack Symfony standard](https://github.com/Elao/symfony-standard) avec comme dépendance par défaut Behat 3.
La documentation sur Behat 2.5 est prédominante par rapport à la version 3, ce qui nous a obligé à faire le tri pour configurer correctement notre stack.
Nous avons donc décidé de vous partager notre set up et la façon dont nous utilisons Behat 3.
<!--more-->
## Behat ##
Behat nous permet de réaliser les tests fonctionnels des applications que nous développons. Il a l'atout d'être extensible et nous permet d'écrire des scénarios compréhensibles à la fois par des humains et par Behat via le language [Gherkin](http://docs.behat.org/en/latest/guides/1.gherkin.html).

### Installation: ###
Avant toute chose, nous devons ajouter Behat à notre projet.

```json
{
    "require-dev": {
        "behat/behat": "3.0.*",
        "behat/mink-selenium2-driver": "1.2.*",
        "behat/symfony2-extension": "2.0.*",
        "behat/mink-browserkit-driver": "1.2.*",
        "behat/mink-extension": "~2.0",
        "behat/mink-goutte-driver": "~1.1"
    }
}
```

Cela fait déjà un sacré paquet de dépendances. On ne va pas toutes les détailler mais pour faire simple, elles permettent d'ajouter des _steps_ par défaut qui nous serons bien utiles.

Après avoir lancer un `$ composer install` on peut maintenant lancer un:
`$ vendor/bin/behat --init`
Ce qui va intialiser le répertoire `/features` et créer le répertoire `/bootstrap` comportant le FeatureContext.php


### Configuration: ###
Ensuite, il faut créer un fichier `behat.yml.dist` à la racine de notre projet dans lequel on va placer la configuration de Behat.

```yaml
default:
    extensions:
        Behat\Symfony2Extension: ~
        Behat\MinkExtension:
            base_url: YOUR_URL
            sessions:
                default:
                    symfony2: ~
                javascript:
                    selenium2: ~
```

Afin de faire fonctionner Behat avec Symfony2, nous utilisons Behat\Symfony2Extension.

Concernant la configuration de MinkExtension, nous utilisons les élements par défaut, il nous suffit juste de spécifier l'url de `base_url`, remplacer donc `YOUR_URL` par la route d'index de votre projet.

***We're all set***

On peut maintenant commencer à écrire nos features et à tester notre application. Créer donc un fichier `hello.feature` dans le répertoire `/feature`
```gherkin
Feature: Hello world
  I need to be able to see hello world

  Scenario: I can see hello world
    When I go to "/"
    Then I should see "Hello World!"

  Scenario: I can't see hello world
    When I go to "/random"
    Then I should not see "Hello World!"
```

Il nous est possible d'écrire _de base_ toutes ces étapes grâce à l'ajout de Mink. Si ces étapes ne marchent pas, vérifier bien que votre FeatureContext étend bien MinkContext.

`class FeatureContext extends MinkContext`


### Extra: ###
Nous utilisons également d'autres extensions pour nous faciliter la vie, notamment Alice pour écrire nos fixtures en yaml et une extension Behat pour appeler nos fixtures au moment où elles sont nécessaires.

```json
{
    require: {
        "nelmio/alice": "2.1.*",
    },
    require-dev: {
        "hautelook/alice-bundle": "~1.0",
        "theofidry/alice-bundle-extension": "v1.0.0"
    }
}
```

Pour la configuration dans `behat.yml.dist`, on ajoute la configuration suivante pour utiliser l'extension Alice
```yaml
    suites:
        default:
            contexts:
                - Fidry\AliceBundleExtension\Context\Doctrine\AliceORMContext:
                    basePath: %paths.base%/features/fixtures
                - FeatureContext: ~
```

Il est intéressant de noter également que l'on peut utiliser `fzaninotto/faker` avec l'extension Alice, ce qui permet de faire facilement et rapidement des fixtures de tests pour nos projets.

Nous avons pris l'habitude, lors de nos tests, de réinitialiser la base de données afin que nos données de tests n'interfèrent pas les unes entre elles. Pour ce faire, nous utilisons une _step_ du AliceORMContext que nous venons de définir dans les context de notre fichier `behat.yml.dist` que nous jouons dans le background de chacun de nos scénarios.

Ce qui nous permet de faire des features comme cela:

```gherkin
Feature: Go to the second page
  I need to be able to go to the second page

  Background: Re-init the database and load the fixtures
    Given the database is empty
    And the fixtures "data.yml" are loaded

  Scenario: I can go to the second page and see my data
    When the fixtures "data2.yml" are loaded
    Then I go to "/"
    And I should see "Go to next page"
    Then I follow "Go to next page"
    And I should be on "/page2"
    And I should see "data"
```

Il faut donc modifier le FeatureContext pour qu'il comporte une implémentation du KernelAwareContext pour pouvoir jouer nos _steps_ :
```php
<?php

use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements KernelAwareContext, SnippetAcceptingContext
{
    private $kernel;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @param \Symfony\Component\HttpKernel\KernelInterface $kernel
     */
    public function setKernel(\Symfony\Component\HttpKernel\KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }
}
```


### En conclusion ###
Une configuration un peu différence de Behat 2.5 mais pas si compliquée comme on a pu le voir.

Des extensions bien utiles pour faire des fixtures en yaml et obtenir des `steps` déjà fonctionnels.

Nous privilégions la regénération de nos fixtures à chacun de nos tests, ce qui permet de ne pas polluer les tests entre eux. Si un test change une valeur, le second n'a pas à la prendre en compte, la valeur est remise à l'initiale.

Et enfin, Behat c'est bien, mangez en!
