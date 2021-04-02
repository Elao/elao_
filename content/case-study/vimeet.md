---
title: "Vimeet"
lastModified: "2017-09-04"
date: "2017-09-04"

# Params
metaDescription: "Vimeet - Événements et rendez-vous d'affaires B2B."
description: "Vimeet permet l'organisation d'événements et la planification de rendez-vous d'affaires B2B."
websiteUrl: https://vimeet.events
clients: Proximum Group
size: 2 ans
services: ["Accompagnement", "Développement"]
technologies: ["symfony", "elasticsearch", "vue-js"]
members: ["mcolin", "ndievart", "rhanna"]
images: ["images/casestudies/headers/vimeet-banner.jpg"]
---

## Le contexte du projet

**Proximum Group est un leader européen de l'organisation d'événements B2B**. Sa spécificité est de faciliter la mise en relation et la prise de rendez-vous entre entreprises lors de salons et d'événements B2B de grande envergure.
Proximum Group organise 2000 événements par an sur le thème de domaines industriels variés comme l'aérospatiale, l'énergie, la logistique ou les nouvelles technologies. Ces événements rassemblent 170000 participants et génèrent 1,5 millon de rendez-vous chaque année.

**Proximum Group a choisi Elao pour l'accompagner dans la refonte de sa plateforme de gestion d'événements et de rendez-vous, Vimeet.**

Le défi consistait à numériser et automatiser davantage leur métier en internalisant un maximum de fonctionnalités grâce à une suite d'outils et d'applications interconnectées.

## L'expertise Elao déployée pour l'application Vimeet

### Conception Agile

**Le projet a été piloté et développé avec les méthodes Agile**.

L'équipe Elao s'est déplacée à chaque sprint dans les locaux de Proximum afin de réaliser des ateliers de co-conception avec le Product Owner, planifier les sprints suivants et présenter l'avancement des développements.

À chaque fin de sprint était réalisée une rétrospective afin d'identifier ce qui a fonctionné et ce qui n'a pas été dans le but de s'améliorer au sprint suivant.

Des membres de l'équipe ont également participé à plusieurs événements organisés par Proximum afin de comprendre leur déroulement, les contraintes de fonctionnement et l'utilisation des applications. Nous avons également pu recueillir directement le retour d'utilisateurs dans les bureaux de Proximum.

### Phase de build (développement)

Une équipe variant de 4 à 6 développeurs a participé activement avec le Product Owner à la co-conception des différents produits Vimeet.

Un scrum master faisait le lien entre le product owner et l'équipe tandis qu'un lead développeur assurait la direction technique du projet.

L'équipe répondait à chaque besoin avec des solutions techniques innovantes et modernes.

Nous avons mis en place :
* Des revues de design technique de chaque fonctionnalité.
* Des revues de code.
* Des tests fonctionnels et unitaires.
* De l'intégration continue.

Plusieurs besoins ont donné lieu à de la <abbr title="Recherche & Développement">R&D</abbr> et au développement de <abbr title="Proof of Concept">POC</abbr> afin de valider les choix techniques et solutions pertinentes.

### Phase de run (évolutions fonctionnelles et maintenance applicative)

La première version avait pour objectif de couvrir les besoins d'un premier événement basculant sur cette nouvelle plateforme.

En parallère des retours suite à ce premier événement, nous avons continué a itérer avec à chaque fois pour cible le prochaine événement qui arrivait avec ses besoins et ses spécificités.

L'application permet aujourd'hui de couvrir l'ensemble des événements organisés par Proximum et continue d'évoluer, de proposer de nouvelles fonctionnalités et de s'adapter à ses utilisateurs.

## Les applications

### Inscription à l'événement

Pour chaque événement, Vimeet devait permettre de configurer une plateforme d'inscription et une billetterie correspondant aux besoins et à la charte graphique de l'événement.

Les exposants devaient pouvoir acheter et réserver différents produits pour établir leur stand (emplacements, mobilier, matériel numérique, ...).

Les visiteurs devaient pouvoir acheter leur place avec différentes options (visio, présentiel, nombre de rendez-vous, ...).

**Les contraintes étaient les suivantes :**

* Un paramétrage complet de l'événement, de la billetterie, des informations demandées aux participants et de la charte graphique.
* Mise à disposition d'une boutique d'options avec gestion de stock, code promos, réductions, packs...
* Paiement et facturation
* Campagnes emailing, emails transactionnels et SMS transactionnels

### Demande de rendez-vous

Une fois inscrits, les participants devaient avoir accès, avant l'événement, à une plateforme de demande de rendez-vous leur permettant de créer un profil décrivant leur société, de visualiser les autres sociétés à travers un annuaire et de réaliser des demandes de rendez-vous avec les autres participants.

**Les contraintes étaient les suivantes :**

* Informations demandées sur les profils configurables par événement.
* Contraintes des rendez-vous configurables par événement.
* Moteur de recherche à facettes.
* Gestion de prise de rendez-vous avec des créneaux, des indisponibilités et des annulations.

### Génération des rendez-vous

Quelques jours avant l'événement, les rendez-vous devaient être créés et planifiés à partir des différentes demandes réalisées par les participants afin de générer des plannings optimisés et équitables.

**Les contraintes étaient les suivantes :**

* Tenir compte des différentes contraintes : disponibilités des participants, disponibilités des lieux, crédits de rendez-vous de chaque participant, répartition équitable des rendez-vous, etc.
* Génération de plannings optimisés et pertinents répondant à un maximum de demandes tout en maximisant l'utilisation des créneaux et des lieux.
* Possibilité de modifier a posteriori le planning. L'annulation, la modification ou l'ajout de rendez-vous devaient pouvoir adapter les agendas des participants concernés.

Nous avons choisi de développer cette application à partir de la solution Open-Source [OptaPlanner](https://www.optaplanner.org/), un moteur de résolution de problèmes à satisfaction de contraintes.

[Notre article sur OptaPlanner](../blog/dev/planification-de-rdv-avec-optaplanner.md)

### Contrôle d'accès

Proximum souhaitait également gérer en interne le contrôle d'accès aux événements ainsi qu'à une zone de rencontre libre à l'intérieur des événéments.

Il fallait pour cela générer des badges à imprimer pour les participants et développer une application pour scanner ces badges et contrôler l'accès de leurs porteurs.

**Les contraintes étaient les suivantes :**

* Générer des badges au format PDF prêts à l'impression pour les participants.
* Disposer un QR Code sur les badges permettant d'identifier les participants.
* Développer une application capable de scanner les badges et compatible avec les smartphones douchettes du fourniseur.
* L'identification et le contrôle d'accès devait être suffisamment rapide pour ne pas créer d'attente à l'arrivée des participants.

Nous avons choisi de réaliser une SPA en Vue.js afin de proposer une interface réactive simple à déployer et permettant aux opérateurs de contrôler rapidement les badges des participants. Cette SPA communique directement avec l'application d'inscription via une API afin d'identifier les badge.

### Visio

Les événements étant souvent internationaux, très tôt Proximum Group a souhaité intégrer une fonctionnalité de rendez-vous par visioconférence.

Cette fonctionnalité permettait à des participants étrangers de participer mais également de maintenir des rendez-vous lorsqu'un participant ne pouvait pas ou plus se déplacer jusqu'à l'événement.

**Les contraintes étaient les suivantes :**

* La possibilité d'organiser des rendez-vous visio directement dans l'application, sans rien avoir à installer.
* Un son et une vidéo de qualité.

Nous avons choisi une technologie web standard, le WebRTC, pour créer une application de rendez-vous en visio-conférence.

### Jour-J

Pendant le déroulement de l'événement, chaque participant dispose d'un emploi du temps complet de ses rendez-vous. Afin qu'ils puissent enchainer leur rendez-vous simplement, Proximum nous a demandé de développer une application mobile leur permettant de visualiser leur emploi du temps, de potentiellement annuler ou déplacer un rendez-vous et de contacter les autres participants en cas de trou.

**Les contraintes étaient les suivantes :**

* Une application mobile simple pour le jour-J
* Afficher les rendez-vous de chaque participant
