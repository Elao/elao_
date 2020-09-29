---
type:               "post"
title:              "L'architecture hexagonale avec Symfony"
date:               "2017-06-05"
publishdate:        "2017-06-21"
draft:              false

description:        "Présentation de l'architecture hexagonale et de son implémentation avec le framework Symfony."
tableOfContent:            true

thumbnail:          "images/posts/thumbnails/hexagons.jpg"
header_img:         "images/posts/headers/hexagons.jpg"
tags:               ["Architecture", "Conception", "Symfony", "PHP"]
categories:         ["Dev", "Symfony"]

author:    "mcolin"
---

# Introduction

L'architecture hexagonale, également appelée *Ports & Adapters*, présente deux caratéristiques lorsqu'on la schématise : une forme **hexagonale** (d'où son nom) et une séparation entre l'**application**, l'**extérieur** et une partie contenant des **adapteurs** permettant aux deux de communiquer.

Elle a été pensé par [Alistair Cockburn](http://alistair.cockburn.us/Hexagonal+architecture) dans les années 2000. Son but est de permettre à une application d'être pilotée sans distinction par des utilisateurs, des programmes, des tests automatisés ou des scripts ainsi que d'être développée et testée de façon isolée de son contexte d'exécution et de ses bases de données.

![Architecture hexagonale](images/posts/2017/hexagonal-architecture.png)

## Une architecture hexagonale

Les deux grands principes de base de l'architecture hexagonale sont :

* La **séparation entre le code métier et le code technique**. Le but est de rendre votre code métier **agnostique** de l'architecture technique dans laquelle votre application sera exécutée.
* L'**inversion de dépendance** : votre code technique dépend de votre code métier et non l'inverse comme dans une architecture plus classique. Pour cela vous allez massivement utiliser le **design pattern adapter**.

La forme hexagonale — qui aurait tout aussi bien pu être octogonale ou pentagonale — est là pour mettre en évidence les différentes facettes par lesquelles votre application communique avec l'extérieur via des adapteurs.

Le **code technique** c'est tout l'environnement nécessaire à votre application sans faire partie de son coeur métier. Tout ou partie de ce code peut être remplacé sans impacter votre métier. Cela comprend — entre autres — la persistance (base de données), le système de fichier, le cache, l'applicatif externe (API, binaires, ...), les bibliothèques et frameworks, etc.

Le **code métier** c'est tout le code qui traduit le métier de votre client. Il s'agit des règles métier, de la logique métier, du code purement applicatif, ... Ce code est irremplaçable et constitue le coeur de votre application.

## Une architecture en couches (ou en oignon)

<p class=text-center>
    <img src="/images/posts/2017/onionman.jpg" alt="Onion man" />
</p>

Afin d'aller encore un peu plus loin dans le découplage de mon code et de me donner un cadre facilitant la séparation du code technique et du code métier, je me suis également inspiré de deux autres architectures proches dans l'idée :

* [The Clean Architecture](https://8thlight.com/blog/uncle-bob/2012/08/13/the-clean-architecture.html) de Uncle Bob
* [The Onion Architecture](http://jeffreypalermo.com/blog/the-onion-architecture-part-1/) de Jeffrey Palermo

Ces deux architectures séparent le code en différentes couches imbriquées. Le nombre de couches dépendra de la complexité de votre application et jusqu'où vous souhaitez pousser le découplage.

Pour ma part je suis partie sur les quatres couches suivantes qui représentes assez bien les différentes parties d'une application **Symfony** (de la plus profonde à la moins profonde) :

* Domain
* Application
* Infrastructure
* Ui

L'idée est que chaque couche peut utiliser une couche inférieure mais jamais une couche supérieure, ou en tout cas pas directement.

<p class=text-center>
    <img src="/images/posts/2017/clean-architecture.png" alt="Clean architecture" />
</p>

Les seules moyens de traverser une couche supérieure sont les **événements**, les **exceptions** et les **adapteurs**.

Les **événements** et les **exceptions** peuvent être lancés dans une couche inférieure et traités dans une couche supérieure. Quand au design pattern **adapter**, il permet de définir une interface du service dont vous avez besoin mais située dans une couche supérieure. L'adapteur correspondant sera implémenté dans ladite couche et l'injection de dépendances permettra d'assembler le tout en conservant le principe de séparation des couches.

Cette séparation en couches n'est pas indispensable à l'architecture hexagonale mais offre un cadre strict permettant de bien séparer votre code applicatif de votre infrastructure ainsi que les différentes parties de votre code. Vous pouvez vous contenter de séparer Domain/Application (code métier) de Infrastructure/UI (code technique).

## Qu'est ce qu'on met dedans ?

Dans la couche **Domain** je mets le coeur métier de mon code. Sans être exhaustif, cela comprend mes modèles, tout ce qui concerne les règles métiers (pour lesquelles vous pouvez utiliser le [pattern specification](https://github.com/maximecolin/satisfaction)), les événéments et exceptions métier.

Dans la couche **Application** je place tout mon code applicatif. Généralement cela se traduit par des *commands* et des *queries* (cf CQRS et CommandBus). Cette couche se situant au-dessus de la couche Domain, je pourrais utiliser tout ce qui s'y trouve. Si j'ai besoin de faire appel à des composants de l'infrastructure tels que la persistance, une API ou une bibliothèque, je créerai des interfaces pour chacun de ces composants.

La couche **Infrastructure** contient majoritairement toutes les implémentations des adapteurs décrites dans les interfaces des couches inférieures ainsi que tous les services nécessaires pour faire communiquer mon application avec mon infrastructure.

Enfin la couche **Ui** est une couche un peu particulière. Comme on peut le voir sur le schéma au début de l'article, elle occupe une facette de l'hexagone et n'entoure pas les autres couches. Il s'agit d'une sorte d'adapteur géant qui permet à l'utilisateur de communiquer avec votre application. Elle contient donc tout ce qui touche à l'interface utilisateur comme les contrôleurs, les vues, les formulaires, ...

```
src
|- Application
|  |- Command
|  |- Query
|- Domain
|  |- Model
|  |- Specs
|- Infrastructure
|  |- Adapter
|  |- Repository
|- Ui
|  |- Action
|  |- Form
```

## Pourquoi ?

Généralement lorsque je présente cette architecture on me fait souvent les remarques suivantes : "C'est compliqué !", "Faut écrire beaucoup plus de code !" (cf. les adapteurs), "Ça prend trop de temps !", ...

Certes cette architecture est plus complexe, implique un peu plus de code et demande un peu plus de réflexion qu'une architecture "classique" mais offre tout de même plusieurs avantages de taille.

1. Le code (en particulier le code métier) est beaucoup plus facile à tester unitairement. Il s'agit de pur PHP, débarrassé de toute relation à votre framework ou votre architecture. Toutes les dépendances extérieures à votre métier sont des interfaces que vous pouvez facilement mocker ou implémenter pour vos tests. Votre code métier peut être couvert à 100% par les test unitaires.

2. Votre code métier et votre infrastructure étant complétement découplés, vous pouvez aisémenent faire évoluer votre infrastructure (changement de techno, montée de version, ...) sans jamais impacter votre code métier.

3. Vous pouvez décliner simplement l'application — par exemple en version CLI ou API — en conservant votre code metier et en changeant seulement les couches supérieures.

4. Les différentes parties de votre application étant bien découplées, vous pouvez simplement répartir leur développement sur plusieurs équipes.

5. Un code metier plus stable et pérenne.

6. En début de projet, vous pouvez mettre en place une infrastructure simple (persistance en mémoire/fichier, API mockée, ...) afin de vous concentrer sur la valeur ajoutée : les fonctionnalités métier.

Au final, l'investissement de départ est un peu plus grand, quoiqu'avec l'habitude pas tant, mais est largement amorti sur la durée de vie du projet tant les évolutions et la testabilité sont simplifiées.

<p class="text-center">
    <img src="/images/posts/2017/good-work-chuck-norris.jpg" alt="Good work" />
</p>

# Et Symfony dans tout ça ?

Tout d'abord j'essaie de créer le moins possible de bundles, voire pas du tout. Cette fonctionnalité de Symfony n'est d'aucune utilité pour cette architecture, elle reste néanmoins indispensable sur certaines fonctions selon la version du framework.

Symfony tend d'ailleurs vers le *no bundle* dans ses versions les plus récentes (3.3 sortie dernièrement et 4.0 à venir).

## Framework agnostique

La première règle est de bien **découpler votre code métier de votre framework**, il faut donc bannir les annotations du code que vous placez dans les couches Domain et Application.

Le mapping Doctrine se retrouvera dans des fichiers [`yml`](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/yaml-mapping.html) ou [`xml`](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/xml-mapping.html). Pour cela, il y a une petite configuration à mettre en place dans votre fichier `config.yml` pour indiquer à Doctrine où se trouvent votre mapping et vos entitées.

```yaml
doctrine:
    orm:
        mappings:
            entity:
                type: yml
                prefix: App\Domain\Model
                dir: "%kernel.project_dir%/app/config/doctrine/entity"
                alias: App
                is_bundle: false
```

Vous pouvez indiquer le type `xml` si vous préférez ce format.

Pour la validation, nous allons également utiliser des fichiers [`yml` ou `xml`](https://symfony.com/doc/current/validation.html#the-basics-of-validation). A partir de la version 3.3, Symfony permet d'indiquer dans sa configuration les répertoires qui contiennent des fichiers de validation :

```yaml
framework:
    validation:
        mapping:
            paths:
                - '%kernel.project_dir%/app/config/validation'
```

mais avant 3.3, il vous faudra les mettre dans un bundle qui prendra place dans `src/Infrastructure`

```
src
|- Infrastructure
   |- Bundle
      |- Resource
      |  |- config
      |     |- validation
      |        |- ObjetA.yml
      |        |- ObjetB.yml
      |- InfrastructureBundle.php
```

Une fois fait, vous pouvez désactiver le support des annotations de validation dans `config.yml` :

```yaml
framework:
    validation: { enable_annotations: false }
```

Deuxièmement, **vos contrôleurs ne doivent pas contenir de logique metier** qui doit être restreinte à vos seules couches Domain et Application. Vous devez uniquement faire appel à votre code métier. De fait, vos contrôleurs sont censés être relativement concis.

Troisièmement, faites bien attention à **ne jamais utiliser de code provenant du framework dans votre code métier**. Si vous en avez vraiment besoin, créez une interface dans Domain ou Application puis un adapteur dans l'Infrastructure.

Enfin, **utilisez l'injection de dépendance** de Symfony pour injecter vos adapteurs dans votre code métier.

# Conclusion

Pour conclure, je dirai que l'architecture hexagonale n'est pas une fin en soi ni l'architecture ultime. Je la vois davantage comme un cadre permettant de se contraindre à respecter le principe de séparation entre le code métier et code technique.

Comme tout paradigme, il a ses faiblesses et ses exceptions, mais pour l'utiliser sur tous mes projets depuis quelques années, il m'a beaucoup fait progresser vers une conception propre, solide, testable et maintenable.
