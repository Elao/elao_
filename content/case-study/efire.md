---
title: "Efire Calage"
lastModified: "2023-09-06"
date: "2023-09-06"

# Params
metaDescription: "Efire Calage — Outil interne de détourage d'objets"
description: "Application interne de gestion de projets clients et de génération d'images vectorielles à partir de photos"
websiteUrl: 
shortDescription: "Outil interne de détourage d'objets"
clients: Groupe Efire
size: 3 mois
services: ["Accompagnement", "Développement"]
terms: ["symfony", "html", "css", "svg", "python"]
members: ["bleveque", "qbrouillet", "adefrance", "frey", "xgorse"]
images: ["content/images/casestudies/headers/efire.png"]
---

## Le contexte du projet

**Le Groupe Efire figure parmi les leaders européens de l'étanchéité, du transfert de fluides, de la découpe métallique et de la protection pour les industries.**

Nous avons travaillé avec les équipes spécialisées dans la transformation de la mousse plastique, qui conçoivent et fabriquent des calages en mousse sur mesure, pour protéger les appareillages, transporter du matériel sans risque et organiser les ateliers.

**L'application web Efire Calage** permet aux équipes de **générer rapidement des fichiers vectoriels**. À partir d'une simple photo prise sur tablette, les équipes commerciales d'Efire sont capables de qualifier, valider et envoyer les ressources nécessaires à la conception des futures mousses de rangement.

## L'expertise Elao déployée pour l'application Efire Calage

### Ateliers de recueil du besoin 

L'application a vocation à être utilisée dans des ateliers, sur des _devices_ mobiles. **L'expérience utilisateur a donc été pensée _tablet first_**, même s'il est possible d'utiliser Efire Calage sur n'importe quel _device_.

Pour répondre aux attentes graphiques tout en apportant un cadre standardisé mais souple à l'application, **Elao a proposé d'utiliser son propre _design system_**.

L'interface a ensuite été construite rapidement et simplement à partir de composants préexistants et quelques rares ajouts liés au fonctionnel de l'application.

<figure>
    <img src="content/images/casestudies/efire-qualification.jpg" alt="qualification d'objet sur l'application Efire Calage">
    <figcaption>
      <span class="figure__legend">Interface de qualification</span>
      <span class="figure__credits">crédit montage <a href="https://freemockupzone.com">freemockupzone</a></span>
    </figcaption>
</figure>

### Ateliers UX/UI
Nous avons conçu les écrans _tablet first_ sur Figma en se basant sur le _design system_ Elao. En plus de cela, pour répondre à des besoins très spécifiques de l'application comme la prise de photos ou la validation des formes générées, des composants sur-mesure sont ajoutés.

Une attention particulière est prêtée à l'enchaînement naturel des écrans et à une navigation fluide.

<figure>
    <img src="content/images/casestudies/efire-ux.png" alt="Prototype Figma des écrans liés à la prise de vue">
    <figcaption>
      <span class="figure__legend">Prototype Figma des écrans liés à la prise de vue</span>
    </figcaption>
</figure>

### Phase de développement
C'est Florian, Amélie, Benjamin et Quentin qui sont montés sur ce projet. 

Le premier a planché sur les scripts de détourage d'objets pour permettre, depuis une photo, de générer différents fichiers vectoriels exportables.

Amélie s'est chargée de l'intégration sur la base du _design system_ Elao, un ensemble de composants HTML/Sass pensé pour répondre aux besoins basiques de la construction d'applications. Les quelques éléments spécifiques à l'application Efire Calage ont été réalisés en HTML/Sass directement dans le projet.

Benjamin et Quentin quant à eux ont mis en place l'interface permettant aux utilisateurs de :
- référencer des clients et des projets associés
- suivre l'avancement et enrichir les projets avec des données contextuelles et les images associées
- capturer des images afin d'y détecter les objets

## L'application

### Les principaux enjeux

#### Détourer précisément les objets photographiés au sein de l'application

En partant du code source de la solution existante, nous avons pu l'améliorer tout en gardant l'essence de l'algorithme d'origine pour s'assurer de la pérennité du résultat.

Une fois capturée, l'image subit une succession de traitements avant de devenir interactive dans l'application web pour générer, stocker et mettre à disposition de l'utilisateur des fichiers vectoriels.

<figure>
    <img src="content/images/casestudies/efire-selection.jpg" alt="Écran de sélection">
    <figcaption>
      <span class="figure__legend">Écran de sélection</span>
    </figcaption>
</figure>

#### Retrouver, dans un contexte web, les fonctionnalités natives de l'appareil photo

Ce second point a nécessité une attention particulière, puisqu'il fallait notamment retrouver :
- l'affichage du niveau
- la capture d'une image en haute définition

<figure>
    <img src="content/images/casestudies/efire-snapshot.jpg" alt="prise de vue sur l'application Efire Calage">
    <figcaption>
      <span class="figure__legend">Prise de vue des outils</span>
      <span class="figure__credits">crédit montage <a href="http://www.freepik.com">zlatko_plamenov</a></span>
    </figcaption>
</figure>

### Pour l'équipe Efire, un outil de génération tout-en-un

Efire souhaitait fluidifier le parcours de ses équipes, du recueil de données chez leurs clients jusqu'à la génération et la transmission de fichiers vectoriels exploitables en production.

Avant cette application, les commerciaux prenaient les photos sur site et ne pouvaient les traiter qu'une fois rentrés au bureau.
Les risques : 
- devoir retourner chez le client si une photo ne convenait pas (netteté, luminosité, ...)
- oublier la saisie de certaines informations liées aux pièces (épaisseur, nombre, ...)

Aujourd'hui, la capture et le traitement se font en même temps, permettant de déceler immédiatement une photo inexploitable.

Et cerise sur le gâteau, le travail apporté au traitement algorythmique a permis d'améliorer significativement la précision de détection des contours 😎.

> Citation client
