---
title: "EMS"
lastmod: 2017-05-23
date: "2016-05-23"

# Params
metaDescription: "Développement sur-mesure d'une solution pour EMS par Elao. Etude de cas."
description: "Le besoin et ses spécificités ne pouvaient pas être pris en charge par un logiciel de marketing automation existant. Il a donc du faire l'objet d'un développement spécifique sur mesure from scratch. Nous avons fait le choix de partir sur Symfony et d'adapter comme il se doit l'outil au métier. Trois collaborateurs ont été sollicités pour prendre en charge le développement, puis nous avons recruté et formé pour le compte d'EMS leur futur lead technique."
websiteUrl: https://www.exemple.com/
clients: Example Corp.
size: 3 months
services: ["Accompagnement", "Développement"]
technologies: ["symfony"]
members: ["mcolin", "tjarrand"]
images: ["images/etudes-de-cas/ems_mockup1.png"]
enabled: false
---

## Expertise développement

* PHP
* Symfony
* Custom intégration
  
* Génération quotidienne de statistiques par profil d'utilisateur
* **Haute résilience :** contrainte forte de conserver la cohérence et l'intégrité des données dans le temps
* Mise en place de workers et de Message Queue pour gérer les différents événements métier de manière asynchrone
* **Intégration custom.** L'ensemble des pages (incluant landing, présentation des produits et paiement) est entièrement personnalisable. Mise en place d'un système de tag pour intégrer les informations en temps réel au rendu des pages
* **Personnalisation des campagnes.** Analyse comportementale : ouverture, clic, visite des pages, intérêt pour un produit, montant des achats et répartition dans le temps

## Méthodologie & process qualité

* Code review
* Tests unitaires et fonctionnels
* Méthode agile

* **Fiabilité.** Tests unitaires sur les parties critiques, tests fonctionnels poussés sur le workflow marketing
* Complexité de tester des enchaînements d'événements sur le long terme : un travail important a été effectué sur la modélisation d'un environnement de tests
* Création d'un outil flexible qui doit pouvoir servir plusieurs marques
* Sur le même principe que la haute résilience, plusieurs solutions TPE et outils d'emailing ont été intégrés à l'application
        
## Quelques chiffres

** Environ 2 000 leads / jour
** Plus de 30 000 emails / jour
