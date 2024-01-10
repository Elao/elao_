---
title:              "L'importance du design system dans la conception d'expériences utilisateurs efficaces"

tableOfContent:     3
date:               "2024-01-10"
lastModified:       ~

description:        ~

thumbnail:          "content/images/blog/thumbnails/importance-design-system.jpg"
banner:             "content/images/blog/headers/importance-design-system.jpg"
tags:               ["design system", "expérience utilisateur"]
authors:            ["eflahaut"]
---

Créer des **interfaces utilisateurs claires** n’est pas une mince affaire. 

Aujourd’hui, designers et développeurs doivent prendre en compte de nombreux paramètres 
afin de s’assurer que leurs produits digitaux respectent les attentes et besoins de leurs utilisateurs 
tout en s’articulant autour de diverses contraintes. 

Ces dernières peuvent rendre la création d’applications, de sites et autres produits digitaux
plus complexe qu’auparavant, impliquant souvent des évolutions fréquentes 
notamment dans le cadre d’un **processus itératif** - ainsi que des allers et retours entre équipes
(designers/développeurs). 

On sait que cela peut engendrer des risques pour le projet (perte d’information, confusions, …). 

Il n’existe pas de solution magique pour pallier complètement ces potentiels problèmes, mais dans ce contexte, 
le design system est une solution multi-facette pouvant aider les équipes à s’organiser 
afin de mener au mieux le développement de leurs produits.

## Mais c'est quoi un design system ?

À ne pas confondre avec les design patterns, les style guides ou encore les bibliothèques de composants, 
un design system est un **référentiel UX et UI**, principalement utilisé par les designers et développeurs 
(mais il peut être utile à tous les membres d’équipe) 
dans le cadre de la **conception** et du **développement de d’applications web et mobile**.

La création d’un design system est motivée par des objectifs de **cohérence des interfaces utilisateur**, 
de **maintenabilité du design** ainsi que du **code** et de **collaboration** entre les équipes de designers et développeurs.

En tant que référentiel, ce dispositif se fonde sur l’**Atomic Design** : 
on crée les éléments de conception de base (comme les couleurs, les polices, etc.) 
et on les assemble au fur et à mesure pour créer des **composants** de plus en plus complexes. 
Un peu comme des legos ! Le design system harmonise les règles de design et de comportement des composants, 
instaurant ainsi un **langage commun entre designers et développeurs**. 

Autrement dit, le design system est une sorte de boîte à outils UX/UI, un set d’éléments variés, standardisés, **assemblables et réutilisables**. 
Je disais en début d’article qu’il fallait veiller à ne pas confondre design patterns, style guides, 
bibliothèques de composants et design system ; c’est parce que le design system inclut, entre autres, 
tous ces éléments et bien d’autres que nous allons voir très vite !

## Construire un design system efficace

### Quels éléments y inclue-t-on ?

Chaque design system est propre à l’entreprise qui le crée et à ses besoins. 
Des designs systems pour des projets d’envergure moindre pourront par exemple inclure seulement un kit UI détaillé et 
quelques éléments graphiques et/ou de développement, là où certains vont bien plus loin en y incluant par exemple 
des bibliothèques de patterns et une documentation poussée.

[Une étude menée par Google et Clarity en 2020](https://material.io/blog/research-state-of-design-systems-2020) auprès d’utilisateurs et concepteurs de design systems a montré que 
ce qui peut être considéré comme des éléments essentiels semble varier selon les années. 

Ainsi, en 2020, on retrouvait notamment en “top 4” des éléments intégrés, une bibliothèque d’icônes, un kit UI, 
un style guide ainsi qu’une bibliothèque de code pour les composants là où l’année précédente, 
les bibliothèques de composants, style guides, design guidelines et content guidelines étaient à l’honneur. 

Il n’existe pas de règles prédéfinies : les besoins des utilisateurs finaux et des équipes de conception évoluant 
avec le temps et le contexte, les éléments attendus d’un design system sont en constante évolution.

<figure>
    <img src="content/images/blog/2023/importance-design-system/decathlon-design-system.png" alt="Le design system de Décathlon">
    <figcaption>
      <span class="figure__legend">Le design system de Décathlon inclut même une partie sur le design des boutiques</span>
    </figcaption>
</figure>

<figure>
    <img src="content/images/blog/2023/importance-design-system/polaris.png" alt="Polaris">
    <figcaption>
      <span class="figure__legend">On retrouve sur Polaris une bibliothèques de composants avec des bests practices et des règles d’utilisation qui peuvent être utilisées à la fois par les équipes de développement et par les UX et UI designers</span>
    </figcaption>
</figure>

### Quelles étapes de construction ?

Même s’il n’existe pas de liste exhaustive d’éléments à inclure dans un design system, 
la réalisation de quelques étapes fondamentales peuvent nous aider à construire un outil efficace :

- **Définir les objectifs et l’utilisation** qui sera faite du design system: pour quel type de produit(s) ? 
Cela va permettre de mettre en lumière une première liste d’éléments à intégrer.
- **Formuler les principes et objectifs fondamentaux** sur lesquels seront construits les interfaces à créer : 
par exemple, l’application devra-t-elle répondre à des critères d’accessibilité ? Ils seront un référentiel pour 
les designers et les développeurs, qui pourront y revenir pour s’assurer que les interfaces, 
tant dans leur aspect visuel que dans leurs fonctionnalités répondent aux objectifs.
- **Déterminer la stratégie visuelle et l’image de marque**, avec tous les éléments qu’elles comportent 
(couleurs, typographies, éléments graphiques, illustrations, etc…), afin de concevoir le style guide. 
Ce dernier sera l’un des premiers éléments à être intégrés dans le design system comme base 
pour tous les composants qui seront créés par la suite.
- **Construire un kit UI**, comme expliqué plus tôt, sur les principes d’atomic design 
(c’est notamment là qu’intervient le style guide !) pour s’assurer qu’ils sont adaptables 
et réutilisables dans plusieurs projets.

Il est intéressant de noter que certains design sytems sont disponibles en **open source**, 
tels que **[Material Design](https://m3.material.io/) de Google**, dont l’interface présente l’avantage d’être particulièrement bien adapté pour tout type d’appareil et toute résolution d’affichage. 
Cet accès permet non seulement de pouvoir réutiliser des assets ou des patterns 
et ainsi de bénéficier d’un gain de temps dans ses propres projets, 
mais également d’en apprendre plus sur la construction des design systems en eux même. 

### Les principes fondateurs d’un design system efficace 

Plusieurs aspects essentiels peuvent être identifiés, en plus des éléments constitutifs du design system, qui, 
une fois mis en œuvre, font de ces outils des ressources fiables. 
Ces aspects favorisent considérablement la collaboration et simplifient la conception de produits multiplateformes. 
Voici quelques notions :

- Un **processus itératif** : un bon design system est évolutif, adaptable et réutilisable. 
Il est construit de manière itérative, avec un accès aux anciennes versions ainsi qu’un historique 
listant les modifications et apports à chaque nouvelle version. [Un article posté sur le site designsystems.com](https://www.designsystems.com/how-spotifys-design-system-goes-beyond-platforms) (Figma) à propos d’Encore, le design system de Spotify, 
met en lumière l’importance de la flexibilité et de la prise en compte du cross-platform dans la création 
et la mise à jour de design system. Ces deux principes permettent de créer une vraie cohérence 
entre les interfaces selon les plateformes utilisées (web ou mobile) et les tailles d’écran, 
résultant ainsi en une expérience utilisateur plus fluide et intuitive.
- L’**accessibilité** comme l’un des principes fondateurs : Toujours sur le site designsystems.com, 
Figma a réalisé une série d’articles sur le futur des design systems. Ils sont amenés à se complexifier en même temps 
que les projets et devront non seulement être adaptés (notamment grâce à l’automatisation par l’IA) 
mais ils seront également  tenus de prendre en compte et d’intégrer les [principes d’accessibilité](https://www.designsystems.com/the-future-of-design-systems-is-accessible/), 
tant dans leur conception que dans la création d’éléments et règles qui les composent.
- Les **utilisateurs du design system et leurs besoins** au cœur de la conception de l’outil : 
Les parties prenantes (développeurs, designers, Product Owners,...) sont au centre de la démarche, 
les informations sont organisées et hiérarchisées de façon cohérente.
- Une **équipe** (ou, a minima, une personne) doit être dédiée à la construction et au maintien du système. 
Cela n’empêche pas la plupart des design systems d’être ouverts à la collaboration 
(soit en open source, soit simplement en interne).
- Des **outils** adaptés qui se complètent : La création et le maintien d’un design system repose sur des outils 
de collaboration et de conception tels que, [Figma](https://www.figma.com), [StoryBook](https://storybook.js.org) 
- (outil permettant la création de bibliothèque de composants) ou encore [ZeroHeight](https://zeroheight.com) 
(pour la création de documentation) afin de mettre à disposition des utilisateurs 
toutes les ressources nécessaires. Il est également possible d’utiliser plusieurs de ces outils à la fois.

<figure>
    <img src="content/images/blog/2023/importance-design-system/material-design.png" alt="Un exemple de composants tiré de Material Design, le design system de Google">
    <figcaption>
      <span class="figure__legend">Un exemple de composants tiré de Material Design, le design system de Google</span>
    </figcaption>
</figure>

## D’accord, mais tout ce travail pour quels bénéfices exactement ? 

Ainsi, si la mise en place d’un design system peut s’avérer longue et minutieuse, 
elle est loin d’être une perte de temps. Ce travail doit être considéré comme un investissement qui, 
sur le long terme, va permettre de faire émerger de nombreux avantages, 
tant pour l’entreprise qui le crée que pour ses clients : 

- Le design system apporte un **socle commun** auquel tous les acteurs d’un projet peuvent se référer. 
La structure et la rigueur apportées par un design system améliore la cohérence visuelle, 
fonctionnelle et facilite la conception, y compris sur du multi-plateforme.
- En tant qu’**écosystème accessible à tous**, il peut aider les nouveaux collaborateurs à comprendre 
et prendre rapidement en main les projets.
- Une fois mis en place, en réduisant le temps et les efforts à la conception, il en **réduit** également le **coût**. 
à chaque nouveau projet et l’équipe peut concentrer ses efforts sur des tâches à plus forte valeur ajoutée.
- Il permet la **réduction de la dette UX**, qui est le résultat de décisions de design et de développement 
qui sont le résultat de décisions hétérogènes et sans direction commune, et qui impactent directement le parcours 
utilisateur sur une application. 

Tous ces éléments contribuent à résoudre des problèmes de design et de développement récurrent et, ainsi, 
à améliorer l’expérience utilisateur : la cohérence qui résulte de l’implémentation d’un design système aide 
les utilisateurs à se créer une “carte mentale” des interfaces, améliorant ainsi 
la transmission des informations importantes.

## Et chez Elao ?

Nous avons aussi notre propre design system, construit au départ pour répondre à un besoin client 
(la conception d’une interface admin sur-mesure sans maquette). 
Au fil du projet, nous avons testé différentes solutions, dont la mise en place de thèmes 
et ce que nous avions appelé “Elao Admin”, qui avait pour but de mutualiser un petit nombre de composants de base. 

L’ouverture d’Elao Admin à d’autres types d’interfaces l’a transformé en un design system plus complet 
dont les bénéfices ne sont plus à prouver :
- Il nous permet de **répondre à des besoins clients de manière progressive** tout en leur proposant 
une solution qui leur coûte moins cher.
- Il nous permet de **gagner du temps** sur le design, qui ne fait pas partie de notre cœur de métier. 
Notre équipe de développeurs peut construire rapidement des interfaces sans avoir de choix de design à faire.

<figure>
    <img src="content/images/blog/2023/importance-design-system/elao-design-system.png" alt="Le design system de Décathlon">
    <figcaption>
      <span class="figure__legend">Le <a href="https://elao.github.io/elao-admin">design system d'Elao</a></span>
    </figcaption>
</figure> 

## Conclusion

La conception d’interfaces utilisateurs efficace exige une **orchestration complexe**, 
entre prise en compte des **attentes des utilisateurs**, **contraintes techniques** et **collaboration** entre équipes. 

Dans ce contexte, le design system est un outil puissant basé sur les principes de l’Atomic Design. 
En offrant une boîte à outils utilisable par tous, il s’impose en tant que pilier crucial pour harmoniser 
le processus de conception et, ainsi, créer des interfaces cohérentes. 
Plus important encore : un design system est en constante évolution et se construit de façon itérative 
en fonction des besoins des entreprises, de ses utilisateurs finaux et de ses équipes de conception.

Un sujet pertinent lié à cette dynamique évolutive est l’**intégration croissante de l’IA** au sein même 
des design systems pour, par exemple, **amplifier l’automatisation**, **simplifier la maintenance** 
et assurer une **meilleure prise en compte de l’accessibilité** au sein de l’outil. 

À nous donc de garder un œil sur les prochaines avancées en la matière 
qui nous permettront de faire encore évoluer notre système !
