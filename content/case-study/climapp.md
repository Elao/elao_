---
title: "Clim'app"
lastModified: "2021-03-30"
date: "2016-12-01"

# Params
metaDescription: "Conçue pour faciliter le quotidien des professionnels du froid, Clim’app est une solution simple et mobile adaptée aux problématiques et enjeux liés à la manipulation et à la gestion des fluides frigorigènes"
description: "Une application dédiée aux professionnels du froid"
websiteUrl: https://www.climapp.dehon.com/
clients: "Climalife Dehon / Gestimat"
size: "8 mois"
services: ["Accompagnement", "Développement"]
technologies: ["symfony", "react", "react-native"]
members: ["msteinhausser", "abenassy", "tjarrand", "cmeiller"]
images: ["images/casestudies/headers/climapp-banner.png"]
---

## Le contexte du projet

**Climalife est un leader européen des fluides frigorifiques pour la
réfrigération et la climatisation** et fournit une gamme de produits
amenés à être manipulés par des professionnels. Le suivi des déchets dangereux
en résultant
étant [encadré](https://www.service-public.fr/professionnels-entreprises/vosdroits/R43122)
par la génération de documents spécifiques, les techniciens sur site sont en
charge de remplir au fur et à mesure de leurs interventions toutes les informations
nécessaires.

C'est dans ce contexte que Climalife a souhaité proposer une application mobile
permettant de faciliter la saisie des données par les techniciens et automatiser
la génération des documents réglementaires, tout en assurant la traçabilité de 
leurs produits.

## L'expertise Elao déployée pour l'application Clim'app

### Ateliers de recueil du besoin

L'équipe Elao s'est déplacée sur site afin de comprendre la façon dont
travaillent les techniciens et proposer une solution pertinente quant à leurs
besoins. Jusqu'alors, tout était saisi manuellement sur les documents
réglementaires, ne facilitant pas le travail des techniciens.

Suite à nos observations et plusieurs ateliers avec Climalife, nous avons
été en mesure de proposer **une ergonomie adaptée à la récolte des informations
tout en accompagnant le technicien durant son intervention** de manière à lui
simplifier la tâche.

### Phase de build (développement)

Thomas, Maxime, Arnaud et Christophe ont mené la phase de développement en
**récoltant besoins et spécifications fonctionnelles** au cours de nombreux
ateliers avec Climalife.  
Donnant lieu à **plusieurs cycles de développement et livraisons itératives**
ils ont pu assurer régulièrement que le fonctionnel était en accord avec le
besoin exprimé grâce à la **recette et validation fonctionnelle** par Climalife.

### Phase de run (évolutions fonctionnelles et maintenance applicative)

Depuis la mise en production de Clim'app, Climalife fait appel à Elao de façon
récurrente pour faire évoluer son produit en fonction des besoins remontés par
ses clients.

L'application s'est ouverte à l'international et gère de nouveaux types de documents, 
propres aux réglementations en vigueur dans chaque pays.

## Les applications

### Pour les techniciens sur site : une application fluide et une saisie simplifiée

Clim'app souhaite faciliter la saisie des données d'interventions par les
techniciens sur les installations avec manipulation de fluides frigorigènes,
dans le but de pouvoir générer automatiquement les documents règlementaires.

**Les contraintes étaient les suivantes :**

* Une ergonomie pensée pour des professionnels du froid, devant accompagner
  ceux-ci durant leurs interventions.
* Se dispenser de la saisie rébarbative d'informations redondantes
  en centralisant celles-ci dans une base de données.
* La gestion du mode hors-ligne : un enjeu majeur pour les techniciens pouvant
  être amenés à intervenir sur des sites sans aucune connexion disponible.

Pour répondre à ces besoins et aux spécifications fonctionnelles du produit,
l'équipe technique d'Elao a fait le choix d’utiliser React Native, un framework
JavaScript basé sur React permettant de construire des applications mobiles à la
fois pour iOS et Android.

<figure>
    <img src="images/casestudies/climapp/climapp-app.png" alt="Application Clim'app">
    <figcaption>
      <span class="figure__legend">L'application Clim'app</span>
    </figcaption>
</figure>

À l'issue d'une intervention, après synchronisation une fois la connexion réseau
rétablie, Clim'app génère automatiquement les **fiches d'intervention et autres
documents règlementaires**, lesquels sont alors disponibles depuis
l'application.

<figure>
    <img src="images/casestudies/climapp/climapp-pdf-cerfa.png" alt="Gestion des documents règlementaires">
    <figcaption>
      <span class="figure__legend">Gestion des documents règlementaires</span>
    </figcaption>
</figure>

### Pour les gestionnaires : un suivi des interventions et des métriques des installations de leurs clients

**Une interface d'administration permet à Climalife de proposer à ses clients un
suivi des interventions réalisées ainsi que de leur parc d'installations et
matériels (détecteurs, contenants, ...).**


<figure>
    <img src="images/casestudies/climapp/climapp-admin01.png" alt="Interface d'administration et de suivi">
    <figcaption>
      <span class="figure__legend">L'interface d'administration et de suivi</span>
    </figcaption>
</figure>


Il est par exemple possible de suivre l'utilisation de leurs contenants, accéder
aux documents réglementaires générés suite aux interventions, visualiser les 
déplacements de contenants ou consulter la liste des matériels nécessitant un contrôle.


<figure>
    <img src="images/casestudies/climapp/climapp-admin02.png" alt="Interface d'administration et de suivi">
    <figcaption>
      <span class="figure__legend">L'interface d'administration et de suivi</span>
    </figcaption>
</figure>
