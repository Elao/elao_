
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
authors:            ["tjarrand", "msteinhausser", "adefrance", "aldeboissieu"]
#tweetId:            ""
---

## Du sur mesure

Pour notre site, on avait été séduit par l'approche du site statique généré automatiquement à partir de contenus en Markdown. Pour la précédente version de celui-ci, on utilisait [Hugo](https://gohugo.io/), mais on a aussi testé de nombreux outils existants pour générer de la documentation, par exemple.

Ça a l'avantage de servir un site très performant, peu sujet aux attaques et dont les contenus sont pilotés à travers un workflow git : un article s'écrit comme une feature, via une PR, avec la relecture et la validation des collègues.

Le concept nous a bien plu, mais on s'est plusieurs fois sentie limité par ces solutions : avec par exemple un code source trop fermé ou difficile à étendre. Du coup, soit on adapte notre besoin à ce qu'est capable de proposer la solution, soit on bricole ...

Mais chez Elao, on est des artisans. Alors cette fois, on voulait être complètement libres, avoir un contrôle total sur notre vitrine en ligne et ne plus dépendre d'une solution qu'on ne maitrise pas bien.

Du sur-mesure quoi, comme pour les projets client !

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

Stenope n'est pas un générateur de site statique prêt à l'emploi (l'open-source compte déjà de nombreux projets de qualité répondant à ce besoin) : Stenope c'est un ensemble d'outils pour générer des sites statiques sur-mesure dans Symfony !

## Accessibilité

Sur notre site comme dans nos articles de blog, nous mettons l’accent sur le niveau d’exigence d’intégration, le respect des standards et des bonnes pratiques. Cela prend plusieurs formes, notamment :
- des choix graphiques qui permettent à tou·te·s d'accéder aux contenus par les contrastes, les couleurs, les formes, les tailles ou les animations
- une navigation simple (à la souris comme au clavier) et le respect de l'ordre naturel de la page
- des medias légendés et crédités
- le choix de cacher certains éléments aux lecteurs d'écrans car ils ont un but cosmétique et peuvent polluer la traduction
- ou au contraire d'apporter des alternatives à ceux qui ont besoin d'être retranscrits
- la possibilité d'accéder au contenu quelle que soit la taille de son écran ou le niveau de son zoom

Dans la pratique, l'accessibilité ce sont aussi des optimisations en cours de projet, et nous allons continuer à y travailler avec les évolutions du site. Notre démarche d'accessibilité se veut poussée tout en étant consciente des compromis qu'elle doit faire avec le temps et le coût du projet. C'est un chantier qui s'étalera dans le temps.

## SEO

La refonte et plus globalement la nouvelle identité graphique a donné lieu à une réflexion sur le positionnement d'Elao. Nous en avons donc profité pour écrire du contenu frais, réecrire les `title` et `meta description`, revoir l'arborescence des pages, etc. 
Bien entendu, dans le cadre d'une refonte, n'oublions pas les fondamentaux:
- Penser aux redirections (301) des pages si les urls changent
- En profiter pour améliorer les performances des pages 🚀
- Ne pas indexer la preprod 🥳
- Déterminer un plan d'indexation des pages pour le crawl des robots

Afin de faciliter cette mise en oeuvre qui peut être laborieuse, nous avons développé un petit outil visible dans la toolbar et le profiler de Symfony. Celui-ci comporte deux onglets : SEO et Accessibilité, et apporte des informations sur la page. En vrac, quelques exemples : 
- SEO : Title, meta description, hiérarchie des titres, directives pour les robots, données hreflangs, partage sur les réseaux sociaux
- Accessibilité : images sans balise alt, icones non-explicitées, formulaires non-accessibles, statut des liens internes et externes

Cet outil s'appelle Accesseo, il fait encore l'objet de quelques ajustements à la suite du test intensif qu'il a subi. Nous le rendrons bientôt accessible, et espérons qu'il vous servira peut-être !

## Images et performances

Différentes options se sont présentées pour afficher dans les images dans la bonne taille, tout en bénéficiant de bonnes performances de chargement. Nous avions évoqué un CDN Thumbor, mais finalement, notre choix s'est porté sur [Glide](https://glide.thephpleague.com/). Nous obtenons une intégration souple et facile à utiliser. 

Chaque image du site existe en 2 versions au minimum :
- l’une (la plus grande) pour permettre un affichage optimal sur les écrans haute résolution
l’autre afin de ne pas charger des images inutilement lourdes sur des écrans de résolution standard
- L’attribut `srcset` nous permet de gérer l’affichage de l’une ou l’autre des versions selon la résolution directement dans le html.

`<img src="image.jpg"
     srcset="image.jpg 1x, image@2x.jpg 2x"
     alt="image" />`
     
Pour un affichage des images encore plus performant, l’attribut `srcset` permet également de charger une certaine version de l’image en fonction de la taille de l’écran. Par exemple pour afficher une version mobile de l’image, de taille réduite et plus légère.
Dans la même logique de performance liée aux images, nous utilisons le format [Webp](https://developers.google.com/speed/webp) là où nous le pouvons. Il offre un niveau de compression supérieur aux formats habituels pour les photos (JPEG, etc). Il est donc intéressant pour [les navigateurs qui supportent le Webp](https://caniuse.com/?search=webp).

Les navigateurs qui ne supportent par le WebP verront quand même les images en JPEG.

## Déploiement continu

Par nature, le déploiement d'un site statique est simple et pas prise de tête (💘) : on build, et déploie les fichiers statiques sur un serveur en ssh (via scp ou rsync). Ainsi, c'est assez simple ensuite de mettre en place un Github workflow. Nous avons même poussé pour obtenir un deploy directement sur GithubPage et des previews par PR, ce qui est fortement appréciable pour commenter en équipe un rendu ou un contenu avec de le merger. 

## Sources

- Le site elao_ (propulsé par Stenope) : https://github.com/Elao/elao_
- Stenope : https://github.com/StenopePHP/Stenope
- Glide : https://glide.thephpleague.com/
- Github Actions : https://docs.github.com/en/actions/learn-github-actions
