
---
type:               "post"
title:              "Rebranding 3/4 : la tech"
date:               "2021-05-03"
publishdate:        "2021-05-03"
tableOfContent:     2

description:        "On s'outille."

thumbnail:          "images/posts/headers/elao-rebrand-banner-tech.jpg"
credits:            { name: 'Phil Hearing', url: 'https://unsplash.com/@philhearing' }
tags:               ["Rebranding", "Elao"]
categories:         ["Elao"]
authors:            ["tjarrand", "msteinhausser"]
#tweetId:            ""
---

## Du sur mesure

Pour notre site, on avait été séduit par l'approche du site statique généré automatiquement à partir de contenus en Markdown. Pour la précédente version de celui-ci, on utilisait [Hugo](https://gohugo.io/), mais on a aussi testé pas mal d'outils existant pour générer de la documention par exemple.

Ça a l'avantage de servir un site très performant, peu sujet aux attaques et dont les contenus son pilotés à travers un workflow git : un article s'écrit comme une feature, via une PR, avec la relecture et la validation des collègues.

Le concept nous a bien plu, mais on s'est plusieurs fois sentie limité par ces solutions : avec par exemple un code source trop fermé ou difficile à étendre. Du coup, soit on adapte notre besoin à ce qu'est capable de proposer la solution, soit on bricole ...

Mais chez Elao, on est des artisans. Alors cette fois, on voulait être complètement libres, avoir un contrôle total sur notre vitrine en ligne et ne plus dépendre d'une solution qu'on ne maitrise pas bien.

Du sur mesure quoi, comme pour les projets client !

## Symfony + Statique = Stenope

En tant qu'experts Symfony chez Elao, ça nous a paru évident : pour maîtriser complètement notre base de code, développons notre site avec Symfony, puis servons-le en statique !

Et ça tombait bien ... [Thomas](../../member/tjarrand.yaml) et [Maxime](../../member/msteinhausser.yaml), de l'équipe, étaient justement en train de plancher sur un projet open-source avec cette idée en tête.

Cet outil fait maison, c'est [Stenope](https://stenopephp.github.io/Stenope/).

> Stenope génère un site statique à partir de n'importe quel projet Symfony.

### Sa philosophie

- Stenope doit s'adapter aux besoins du projet, pas l'inverse.
- Stenope fonctionne "out-of-the-box" dans n'importe quel projet Symfony standard, et son utilisation semble naturelle aux habitué·e·s de Symfony.
- Stenope est extensible : chaque module est interfacé, remplaçable et optionel.

### Son fonctionnement

- Stenope scanne votre application Symfony comme le ferait un robot d'indexation et exporte chaque page vers un fichier HTML dans un dossier de build.
- Stenope fournit une collection de services dédiés à la gestion de contenus statiques permettant de lister et convertir des sources de données (comme des fichiers Markdown locaux mais aussi des CMS Headless distants) en objet PHP métier, comme le ferait un ORM.
- Stenope vous donne un grand contrôle sur la manière dont sont récupérés et hydratés ces contenus.
- Il ne vous reste qu'à utiliser vos objets métier comme bon vous semble, par exemple dans des controllers et des templates twig.

Stenope n'est pas un générateur de site statique prêt à l'emploi (l'open-source compte déjà de nombreux projets de qualité répondant à ce besoin) : Stenope c'est un ensemble d'outils pour générer des sites statiques sur mesure dans Symfony !

## Deploiement continu


## Sources

- Le site elao_ (propulsé par Stenope) : https://github.com/Elao/elao_
- Stenope : https://github.com/StenopePHP/Stenope
