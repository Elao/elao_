---
type:               "post"
title:              "Un kit pour démarrer votre site statique avec Stenope"
date:               "2022-09-15"
lastModified:       ~
tableOfContent:     false

description: > 
    Stenope, le générateur de site statique initié par Elao s'accompagne désormais d'un kit de démarrage
    pour vous aider à découvrir son utilisation et démarrer vos projets.

thumbnail:          "content/images/blog/thumbnails/stenope-skeleton.jpg"
tags:               ["Stenope", "Site Statique"]
authors:            ["msteinhausser"]
tweetId:            ~
---

## Stenope, l'outil propulsant ce site

[Il y a quelque temps de cela déjà](../elao/rebranding-la-tech.md), nous vous parlions de [Stenope](../../term/stenope.md), 
notre générateur de site statique.
Développé en interne et motivé par la volonté d'avoir la totale maitrise sur notre site, 
il s'agit d'un outil à destination des développeurs Symfony.

Celui-ci consiste en effet en un bundle Symfony que vous pouvez mettre en place sur un projet existant.
Vous bénéficierez dès lors d'une commande CLI agissant comme un _crawler_ parcourant les pages de votre application
afin d'en générer une version statique :

```shell
symfony console stenope:build -e prod ./build
```

La manière d'écrire votre application Symfony n'est pas imposée et ne nécessite pas forcément de connaissances
particulières supplémentaires. Aucun thème ou modèle de données n'est fourni et par conséquent la façon de développer
votre application vous appartient entièrement.

Pour autant, Stenope contient quelques fonctionnalités optionnelles de gestion de contenu qui pourraient vous aider.  
Nous avons également pu mettre en place des fonctionnalités courantes sur certaines typologies de projets qu'il
peut être intéressant de démontrer.

## Un kit de démarrage

Nous avons donc décidé de mettre à disposition un kit de démarrage, qui vous permettra de découvrir davantage comment 
utiliser Stenope et ses fonctionnalités au sein d'une application concrète.
Le kit est suffisamment simple pour vous permettre de bifurquer à tout moment et commencer à écrire votre propre version,
tout en démontrant des possibilités d'extensions.

Le projet est disponible [à cette URL](https://stenopephp.github.io/skeleton/) et peut être installé via Composer:

```shell
composer create-project stenope/skeleton -s dev
```

Vous pouvez dès lors utiliser ce projet comme terrain de jeu pour expérimenter, ou commencer à développer vos propres
pages et contenus en profitant de la structure et des outils déjà mis en place.
Le projet inclut notamment des outils de linting, redimensionnement d'images et autres workflows Github de déploiement 
sur Github Pages…

## Sources

- [La documentation de Stenope](https://stenopephp.github.io/Stenope/)
- [Stenope skeleton](https://stenopephp.github.io/skeleton/)
