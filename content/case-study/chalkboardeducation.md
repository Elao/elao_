---
title: "Chalkboard Education"
lastmod: 2017-09-04
date: "2017-09-04"
name: "Chalkboard Education"

# Params
description: "Nunc auctor est dolor, eget placerat lorem semper sit amet. Integer aliquet mi orci, et eleifend urna fermetum. Nullam pelletesque frigilla vulputate."
websiteUrl: https://www.exemple.com/
clients: Chalkboard
size: 3 months
services: ["Accompagnement", "Développement"]
technologies: ["symfony", "graphql", "react"]
members: ["ndievart", "rhanna"]
images: ["images/casestudies/headers/chalkboardeducation-banner.jpg"]
---

## Contexte projet

Dans certains pays africains, le nombre de places disponibles à l'université est très limité.
De nombreux étudiants n'ont par conséquent pas accès aux cours.
La startup <a href="https://chalkboard.education/">Chalkboard Education</a> a pour but de résoudre ce problème en diffusant les cours via les téléphones mobiles.
Les étudiants africains n'ont certes pas forcément le dernier modèle de smartphone ni une connexion Internet fiable mais cela est suffisant pour rendre possible l'accès à la connaissance.

## Expertise développement

* Symfony
* GraphQL
* React
* React Redux
* Progressive Web App
* Service Worker
* Offline-first
* Mobile-first
* Material Design

Elao accompagne Chalkboard Education depuis 2015 sur la conception de son produit.
Un premier Proof Of Concept a été réalisé en ReactNative et une application pour Android déployée sur Google Play Store pour des étudiants de l'University Of Ghana.
Avec l'émergence des Progressive Web Apps, nous avons conseillé Chalkboard Education de miser sur le web pour plusieurs raisons :

* le public visé est majoritairement sur Android, OS sur lequel actuellement les techniques des PWA sont les plus avancées,
* le coût du développement est moins important que le développement d'applications natives pour Android et iOS,
* la fréquence de mise à jour est plus simple et ne dépend pas de la bonne volonté des stores d'applications,
* le poids d'une web app est beaucoup moins important qu'une application native ce qui est un avantage pour des populations ayant un accès limité à Internet,
* la couverture des appareils ciblés est beaucoup plus large du fait qu'il s'agisse d'une application web.

Le projet Chalkboard Education s'articule autour de deux plateformes :
    
* un back-office permettant de gérer les étudiants et les cours et consulter la progression des étudiants,
* une application web pour l'étudiant afin qu'il puisse accéder à ses cours, répondre à des QCM et valider sa progression.
            
L'application étudiant est une Progressive Web App :

* elle est propulsée par React et React Redux,
* elle peut être installée sur l'écran d'accueil du téléphone,
* les contenus sont téléchargés à la connexion puis conservés en cache navigateur ; la consultation des cours est donc disponible en déconnecté (offline).

Les deux plateformes sont connectées avec :

* une API en GraphQL, solution pertinente par rapport à REST pour laisser l'application consommatrice de l'API choisir les contenus qu'elle souhaite en une seule requête HTTP
* l'envoi / réception de SMS pour que l'étudiant puisse valider sa progression sans Internet.

## Méthodologie & process qualité

* Code review
* Tests unitaires
* Tests fonctionnels
* Méthode agile

Le niveau de qualité appliqué à ce projet correspond aux standards d'Élao. Il implique notamment :

* des ateliers de co-conception avec le CEO
* des revues de code
* des tests unitaires et fonctionnels
* des recettes à fréquence régulière
* des rétrospectives agiles dans le but d'une amélioration continue.
