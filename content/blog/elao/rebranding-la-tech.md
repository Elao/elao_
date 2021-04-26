
---
type:               "post"
title:              "Rebranding 3/4 : la tech"
date:               "2021-05-03"
publishdate:        "2021-05-03"
draft:              false
tableOfContent:     2

description:        "On s'outille."

thumbnail:          "images/posts/headers/elao-rebrand-banner-tech.jpg "
header_img:         "images/posts/headers/elao-rebrand-banner-tech.jpg "
tags:               ["Rebranding", "Elao"]
categories:         ["Elao"]
authors:            ["tjarrand"]
#tweetId:            "1384417140346920962"
---

## Du sur mesure

Pour notre site, on avait été séduit par l'approche du site statique généré automatiquement à partir de contenus en Markdown. Pour la précédente version de celui-ci, on utilisait [Hugo](https://gohugo.io/).

Cela permettait de servir un site très performant, peu sujet aux attaques et de piloter ses contenus via un workflow git : un article s'écrit comme une feature, via une PR, avec la relecture et la validation des collègues.

Le concept nous a bien plu, mais cette fois, on voulait être complètement libres, avoir un contrôle total sur notre vitrine en ligne et ne plus être limités par les fonctionnalités d'une solution tierce.

Fidèles à notre façon de faire : on voulait du sur mesure !

## Symfony + Statique = Stenope

En tant qu'experts Symfony chez Elao, ça nous a paru évident : pour maîtriser complètement notre base de code, développons notre site avec Symfony, puis servons-le en statique !

Et ça tombait bien ... Thomas et Maxime, de l'équipe, étaient justement en train de plancher sur un projet open-source avec cette idée en tête.

Cet outil fait maison, c'est [Stenope](https://stenopephp.github.io/Stenope/).

Stenope génère un site statique à partir de n'importe quel projet Symfony.

Sa philosophie :
- L'outil doit s'adapter aux besoins du projet, pas l'inverse.
- Son utilisation doit être naturelle pour les habitué·e·s de Symfony.
- Il est extensible et chaque module est remplaçable.

Stenope n'est pas une solution clé en main, c'est un outil qui n'impose pas sa façon de faire !
Il vient également avec une collection de fonctionnalités optionnelles dédiées à la gestion de contenus statiques : lecture de markdown, coloration syntaxique, etc.

## Deploiement continu


## Sources

- Le site : https://github.com/Elao/elao_
- Stenope : https://github.com/StenopePHP/Stenope
