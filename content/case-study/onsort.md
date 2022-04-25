---
title: "On sort !"
lastModified: "2017-05-29"
date: "2017-02-01"

# Params
metaDescription: "On Sort - l'agenda culturel de la métropole d'Orleans."
description: "L'application On Sort ! est un agenda des sorties dans la métropole orléanaise. Les organisateurs peuvent suggérer leurs événements en quelques clics."
websiteUrl: http://sortir.orleans-metropole.fr/
clients: Mairie d'Orléans
size: 2 mois
services: ["Accompagnement", "Développement"]
terms: ["symfony", "vue-js", "html", "css"]
members: ["mcolin"]
images: ["content/images/casestudies/headers/onsort-banner.jpg"]
---

## Le contexte du projet

**Orléans Métropole se compose de 22 communes autour de la ville d'Orléans et représente 287 000 habitants.**

L'application "On sort !" - pensé par la mairie d'Orléans - est une plateforme mettant en avant les événements culturels de la ville d'Orléans et des communes de la métropole.

**Nous avons accompagné la mairie d'Orléans tout au long de ce projet en étant force de proposition pour répondre aux défis techniques et ergonomiques tout en nous assurant de la maîtrise du budget consacré.**

## L'expertise Elao déployée pour l'application On Sort !

### Ateliers de recueil du besoin
L'équipe Elao s'est déplacée à Orléans pour rencontrer l'équipe de la mairie portant ce projet.
La mairie disposait d'un calendrier collaboratif avec [OpenAgenda](https://openagenda.com/) qu'elle souhaitait mettre à disposition des acteurs culturels de la métropole, mais il manquait une interface utilisateur attractive permettant aux habitants de visualiser les événements culturels à venir.
À partir de ces ateliers, nous avons pu comprendre les besoins et les contraintes de la mairie. L'application devait permettre de développer l'offre culturelle de la métropole, de la rendre plus accessible et d'augmenter l'attractivité des différents lieux culturels.
**Pour ce projet, le challenge résidait dans une ergonomie dynamique et multi-plateforme (desktop, tablette et mobile) ainsi qu'un affichage pertinent des événements proposés aux utilisateurs.**

### Phase de build (développement)
Maxime a réalisé toute la phase développment, **accompagnant l'équipe de la mairie d'Orléans dans la conception et proposant différentes solutions techniques et fonctionnelles pour répondre aux besoins**.

La mise en production de la première version a permis à la mairie de tester très vite l'application auprès des utilisateurs et de recueillir les retours des habitants et des acteurs culturels.

### Phase de run (évolutions fonctionnelles et maintenance applicative)
À la suite de la mise en production, la mairie d'Orléans a régulièrement fait appel à Elao pour faire évoluer l'application et prendre en compte l'avis de ses utilisateurs.

## L'application

### Une application simple et dynamique

La mairie d'Orléans souhaitait une application simple et performante permettant à un maximum d'habitants de la métropole de trouver facilement les événements culturels disponibles autour d'Orléans.

<figure>
    <img src="content/images/casestudies/on-sort-phones.png" alt="L'application On Sort sur téléphone">
    <figcaption>
      <span class="figure__legend">Une application d'abord pensée mobile</span>
    </figcaption>
</figure>

**Les contraintes étaient les suivantes :**

* Une interface simple permettant de trouver des événements culturels par thème et par date ;
* Une connexion avec OpenAgenda afin de récupérer les événements renseignés par les acteurs culturels de la métropoles et enrichir les données exposées par son API ;
* Une experience utilisateur de qualité sur desktop, mobile et tablette.

L'application a été réalisée en [Vue.js](#lien-page-techno) afin de proposer une interface intéractive et une expérience la plus fluide possible. L'application est **une SPA responsive pouvant être utilisée à la fois sur desktop, mobile et tablette**. Elle est alimentée par une API développée avec [Symfony](#lien-page-techno) et les données d'[OpenAgenda](https://openagenda.com/).


<figure>
    <img src="content/images/casestudies/on-sort-computer-phone.jpg" alt="L'application On Sort en responsive design">
    <figcaption>
      <span class="figure__legend">Une ergonomie pensée pour tous les devices</span>
    </figcaption>
</figure>

### Des fonctionnalités pour engager ses utilisateurs

Si l'ensemble des événements provient d'OpenAgenda, la mairie d'Oleans souhaitait pouvoir enrichir ces données afin d'animer l'application et d'engager ses utilisateurs.

La mairie peut mettre en avant certains thèmes et événements en lien avec une actualité par exemple.

Quant aux utilisateurs, ils peuvent s'inscrire et ajouter des événements en favoris.

Enfin, les données générées par l'application permettent à la mairie de mesurer les envies et les besoins de la population en terme de culture.

<figure>
    <img src="content/images/casestudies/on-sort-tablet.png" alt="L'application On Sort sur tablette">
    <figcaption>
      <span class="figure__legend">Une SPA compatible tablette</span>
    </figcaption>
</figure>
