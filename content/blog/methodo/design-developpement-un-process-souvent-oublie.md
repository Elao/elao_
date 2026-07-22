---
type:               "post"
title:              "Design et développement : un process souvent oublié (épisode 1)"
date:               "2026-07-22"
lastModified:       ~
tableOfContent:     false

description: >
    Designers et développeurs travaillent ensemble au quotidien, et pourtant, leurs façons de faire
    et de communiquer restent souvent bien éloignées. Chez Elao, on a décidé de combler ce fossé.
    Une partie de notre réponse tient en un mot : Storybook.

authors:            [eflahaut]
tags:               [design-system, storybook, ux, webdesign]
thumbnail:          content/images/blog/2026/methodo/design-developpement-un-process-souvent-oublie/couverture.png
---

## Épisode 1 · Ce fossé invisible que l'on a décidé de combler grâce au Storybook

*Le travail sur le design d'une application et le développement sont deux faces différentes d'une même pièce et pourtant, la collaboration entre designers et développeurs n'est pas toujours fluide… Non pas par manque de volonté de la part des uns ou des autres, mais parce que personne n'a réellement appris comment faire.*

Chez Elao, la mise en place progressive d'une phase de conception plus étendue, intégrant un travail poussé de design d'UX et UI, a mis en lumière un fossé que l'on n'avait pas anticipé entre design et intégration. Les vocabulaires, les outils et les façons de penser un produit qui diffèrent entre ces deux métiers nous ont amenés à repenser nos processus en y ajoutant, notamment, la construction d'un Storybook comme source de vérité partagée pour chaque projet. Ce premier article de la série revient sur les réflexions qui nous ont amenés à ces travaux.

### 1) Ce que l'on n'apprend pas aux designers

De façon générale, les formations en UX/UI design se concentrent sur les utilisateurs finaux : leurs besoins, leurs attentes et comment les recueillir au mieux pour créer les meilleures expériences et interfaces. On y saupoudre également des bases d'accessibilité et de code (HTML, CSS et JavaScript principalement) afin de pouvoir dialoguer avec les autres interlocuteurs impliqués dans la création d'applications et de sites web, et voilà.

Bien sûr, on a ici une vision très simplifiée du métier, mais cela suffit pour pointer du doigt une faille dans l'apprentissage. Et cela, on s'en aperçoit quand on commence à intervenir sur des projets plus concrets, qui dépassent les limites des logiciels de design.

Car ce que les formations oublient parfois, c'est d'apprendre à leurs designers à voir les maquettes qu'ils créent, non comme le produit final, mais comme un véritable livrable technique ! Un livrable que les développeurs et, de plus en plus, les agents IA doivent pouvoir lire, analyser et traduire en code cohérent. Un livrable complet donc, accompagné d'une documentation ou au moins d'explications claires et organisées de façon logique pour que les intentions initiales ne se perdent pas dans le cosmétique uniquement.

Mais le constat est similaire lorsque l'on s'intéresse à l'autre face de la pièce : les développeurs n'apprennent pas non plus à formuler les besoins en amont auprès des designers. Finalement, chacun sort de sa formation en maîtrisant ses propres outils sans vraiment connaître le quotidien de l'autre.

Ce problème, on l'a clairement identifié chez nous mais il n'est pas si isolé que cela. Dans un meetup organisé par [JetBrains](https://youtu.be/RrlMOunfdHs) en 2024, product designers, développeurs et team leads constatent que la collaboration entre design et développement se trouve parfois mise à mal par un vocabulaire qui ne se recoupe pas ou encore un manque de vision sur le design. Cela pousse alors les équipes de développement à multiplier les itérations de correction afin de faire coïncider un simple composant au mockup fourni.

En conclusion : les outils utilisés, la façon dont designers et développeurs communiquent et la structuration des fichiers de design peuvent faciliter ou compliquer le travail de toute l'équipe, et donc la vitesse à laquelle un projet avance.

Une fois que ce constat est posé, la solution semble toute trouvée, non ?

### 2) Parler la même langue

La réalité est un peu plus complexe que cela.

Quand un développeur demande « est-ce que tu peux créer quelques wireframes avec Shadcn ? Et me dire vers quoi dirige ce bouton pour que je puisse savoir si on doit faire appel à une API ? », un designer (en particulier junior) peut vite se retrouver perdu. Ce vocabulaire, qui fait partie du quotidien des développeurs et qui devient peu à peu accessible avec l'expérience, est pourtant rarement abordé en formation.

Et parfois, les quiproquos sont plus subtils encore : certains mots que designers et développeurs peuvent avoir en commun ne veulent en réalité pas dire exactement la même chose selon le métier. Parfois, c'est le vocabulaire qui bloque, car les termes utilisés par les développeurs sont tout simplement inconnus pour un designer, et inversement. Mais parfois, c'est plus subtil : un mot comme « composant » existe des deux côtés, et le concept de base est le même (un bloc réutilisable), mais la façon de le penser et de le construire diffère suffisamment pour créer des malentendus.

Avec le temps, on finit par ajuster le tir. Le designer apprend les bons termes, un vocabulaire commun se construit progressivement, on intègre petit à petit les besoins réels de l'autre métier et le processus devient plus fluide.

Mais le vocabulaire n'est encore que la surface du problème, car dans un contexte d'agence, les projets sont, par nature, évolutifs et, dans le cas d'Elao, nous n'avons qu'un seul designer UX/UI pour tous les projets.

### 3) Storybook comme source de vérité

Sachant cela, nous nous sommes demandé où se trouverait la référence et comment garantir que les futures mises à jour restent cohérentes avec les choix initiaux. Certains choisiront le fichier Figma comme source de vérité mais dans notre cas, nous avons décidé qu'il s'agirait du Storybook (un projet = un Storybook), créé et maintenu par les développeurs à partir du fichier fourni par le designer.

<blockquote class="blockquote-secondary">
<a href="https://storybook.js.org/">Storybook</a> est un outil open source qui permet de développer et documenter les composants d'une interface de façon isolée, en dehors de l'application. C'est un catalogue vivant : chaque élément UI, des plus simples comme un bouton aux plus complexes, y est visible avec ses variantes et ses états réels. Contrairement à un fichier Figma, ce que l'on voit dans Storybook, c'est le vrai code, tel qu'il sera rendu dans le produit final. C'est d'ailleurs ce qui en fait un outil utilisé par des équipes comme celles de GitHub, Airbnb ou Shopify pour maintenir la cohérence de leurs interfaces à grande échelle.
</blockquote>

Grâce au Storybook, nous pourrons, pour chaque projet :

- documenter et versionner les règles du design system (tokens, typographies, palettes, espacements, principes) à partir des éléments préparés par le designer ;
- exposer les composants UI de façon isolée, des éléments les plus simples (atomes) aux plus complexes (molécules, organismes) et contrôler visuellement leur rendu et leurs variations ;
- concevoir des composants en se concentrant sur leur logique de rendu et leurs déclinaisons, indépendamment de la connexion aux données et aux API réelles.

Le Storybook devient ainsi ce « pont » entre, d'un côté, le développeur et l'application et, de l'autre, le designer et Figma. Et c'est d'autant plus vrai aujourd'hui, grâce à l'introduction de l'IA qui permet d'automatiser une partie du workflow entre les deux outils et surtout, d'alléger la charge qui vient avec leur maintien.

Techniquement, le flux est **bidirectionnel** : Claude peut récupérer les éléments d'un fichier Figma pour les appliquer dans le code, mais il peut aussi créer et modifier du contenu directement dans Figma à partir du code. On pourrait donc, en théorie, **maintenir le fichier Figma en parallèle du développement** et garder les deux synchronisés.

Mais dans notre contexte, nous avons fait un **autre choix**. Avec **un seul designer pour plusieurs projets en parallèle**, maintenir le fichier Figma à jour tout au long de la vie d'un projet n'est tout simplement pas réaliste. Une fois la phase de conception terminée et les premiers écrans posés, le designer passe à d'autres projets et n'intervient plus sur le Figma, sauf si une nouvelle fonctionnalité nécessite un vrai retour sur l'UX et l'UI.

À partir de là, **c'est le Storybook qui vit et évolue avec le projet**. Les développeurs s'en servent, avec l'aide de Claude, pour construire les écrans dont ils ont besoin et générer les composants simples qui n'existent pas encore, en respectant les règles établies par le design system.

Le Storybook devient donc pour nous la source de vérité non pas par limitation technique, mais par choix de process : c'est lui qui reflète l'état réel du projet à tout moment, là où notre fichier Figma reste une photographie de la conception initiale.

Dans le prochain article, nous explorerons comment des outils comme Claude, Figma Make ou le vibe coding nous aident concrètement à construire et alimenter ce process, et ce que ça change dans la collaboration entre design et développement.
