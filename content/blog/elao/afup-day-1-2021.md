---
type:               "post"
title:              "AFUP Day 2021, jour 1 : le compte-rendu de l'équipe"
date:               "2021-06-03"
lastModified:       ~

description:        "Notre compte-rendu du premier jour de l'Afup Day, édition 2021, à distance !"
thumbnail:          "images/posts/2021/afup-day-2021/afup-day-2021.png"
tags:               ["Développement", "Web", "afup", "Conférence", "afupDay"]
categories:         ["conference"]
author:             ["aldeboissieu", "bleveque"]
---

Le premier jour de conférence de l'AFUP Day a eu lieu le 28 mai 2021, entièrement en ligne. Nous avons pu ainsi suivre les conférences organisées par les antennes locales de Lille et Rennes. Bravo à eux et à toute l'équipe de l'AFUP pour cette édition très bien organisée qui, malgré la distance, parvient à donner le sourire :). 
Voici quelques notes de l'équipe d'Elao et qui vous propose de faire un zoom sur trois conférences :

# Un service, Késako ? Par Romaric Drigon

Romaric a fait une piqure de rappel très intéressante et très didactique sur ce que sont les services, et comment les organiser pour les utiliser au mieux. Parmi les principaux problèmes que Romaric soulève, il y a les services qui font trop de choses. Difficilement lisibles, trop de dépendances, difficiles à utiliser. Il appelle ça ... le plat de spaghetti. Pour améliorer son code, il faut avoir en tête le principe de responsabilité unique et ainsi mieux découper ses services. Mais attention à ne pas tomber dans l'excès inverse (le plat de ravioli), et n'avoir que de tout petits services, ce qui compliquerait également la compréhension du code. 

Parmi les bonnes pratiques, Romaric cite le design pattern Décoration, le Bus de commande (que nous utilisons beaucoup chez Elao), les événements, etc. 

[Sa conférence est à voir ici.](https://speakerdeck.com/romaricdrigon/un-service-kezako)

# Réconcilier le Back et le Front dans un projet Symfony, par Quentin Machard

Cette session présentait la mise en place du pattern `Atomic Design` au sein d’une application Symfony.

L’Atomic Design consiste à découper ses templates en plusieurs fichiers de granularités différentes (Atomes, Molécules, Organismes, Templates & Pages), C’est une philosophie qui est beaucoup plus utilisé dans les frameworks front, comme React ou VueJS, où l’on créé des composants réutilisables.

Pour aller plus loin dans la mise en place de ce pattern, Quentin a eu l’idée de créer un [Bundle](https://github.com/qmachard/atomic-design-bundle) permettant de documenter ces templates ainsi que leurs différents états facilement (pour les autres langages nous pouvons utiliser [StoryBook](https://storybook.js.org/), qui lui a fortement servi d’inspiration).

Cette solution est très intéressante lorsque l’on souhaite créer une application complexe et que l’on veut documenter facilement ses différents composants graphiques.

# Montez à bord d'une équipe autonome ! par David Laizé

Lors cette session, David nous a fait un retour d’expérience sur les bonnes pratiques qui permettent d’obtenir une équipe autonome.

Une équipe autonome est un ensemble de personnes qui arrivent à produire un résultat en gardant un rythme soutenu sans avoir besoin de pilotage, et où tout le monde est aligné sur les même objectifs.

Pour cela, il faut : de l’entraide, du partage et de la transmission de son savoir au travers de revues de code systématiques. Il faut également savoir se remettre en question en permanence, toujours être à la recherche de la meilleure approche et ne pas se reposer sur ses acquis.

Pour être une bonne équipe autonome, les membres doivent être proches en dehors du bureau, ils doivent passer du temps ensemble, soit lors de conférences, soit lors d’autres activités (soirées, randonnées, …).

Par contre celle-ci doit avoir défini des règles explicites, car sans un cadre précis, personne ne sait ce qu’il doit faire et quand il doit le faire.

David a expliqué que malgré toutes ses années d’expériences, il a rarement croisé des équipes autonomes.

## Alors, cette édition ? 

Malgré une conférence une nouvelle fois en ligne, l'équipe de l'AFUP a parfaitement réussi à entretenir une ambiance sympathique et la bonne humeur régnait sur le chat et dans l'espace virtuel WorkAdventure. Tout était fluide et très bien organisé. Bravo à l'équipe !
