---
title: "Tribü"
lastmod: 2021-01-17
date: "2019-01-09"
name: "Tribü"

# Params
metaDescription: "Tribü — recyclage intelligent de déchets."
description: "Tribü propose un dispositif permettant aux entreprise un recyclage intelligent de leurs déchets."
caseUrl: http://www.tribu-recyclage.fr/
clients: Tribü
users: Techniciens du tri et entreprises engagées dans le tri de leurs déchets
size: 3 mois
services: ["Accompagnement", "Développement"]
technologies: ["symfony", "react", "html/css", "svg"]
members: ["aldeboissieu", "tjarrand", "adefrance", "xgorse"]
images: ["images/etudes-de-cas/tribu-reporting.png", "images/etudes-de-cas/tribu-saisie.png"]
---

## Le contexte du projet

Tribü est une entreprise de recyclage : elle propose la mise en place un dispositif de tri des déchets chez ses clients (entreprises disposant de bureaux, chantiers de construction, restaurants collectifs…). Sa force est de proposer un suivi précis et un reporting mensuel, afin de connaître exactement les quantités de déchets par catégorie ainsi que la finesse du tri effectué. L'enjeu de Tribü est avant tout de faire de la pédagogie auprès de ses clients pour que le tri soit de mieux en mieux réalisé, de mois en mois et d'année en année.

Tribü a confié à Elao le développement de son applicatif métier lui permettant d’assurer à la fois la gestion quotidienne de ses activités de tri, la supervision et de proposer à ses clients un espace de reporting complet. 

Un projet dont les valeurs nous ont beaucoup fait écho et que nous avons pris beaucoup de plaisir à réaliser. 

## L'expertise Elao déployée pour l'application Tribü

### Ateliers de recueil du besoin
L'équipe Elao s'est déplacée dans les ateliers de Tribü afin de comprendre les différentes étapes du tri (pesée, saisie des données, réalisation des rapports) et proposer une solution pertinente quant à leurs besoins. 
À partir de ces ateliers, nous avons été en mesure de proposer une feuille de route fonctionnelle permettant d'arriver à une première version du projet. 
Les besoins étaient double : proposer une solution efficace pour les techniciens et un rapport pour les entreprises qui soit le plus clair possible.

### Ateliers UX/UI
Les développeurs Elao sont avant tout des concepteurs. L'UX/UI a été réalisée main dans la main avec Mathilde Vandier, designer freelance, avec laquelle nous avons itéré du début à la fin du projet. 

### Phase de build (développement du projet) 
L'ensemble des flux d’échange de données entre le SI d’Unicil et l’application a été défini. Un POC technique a permis de valider le bon fonctionnement de la solution d’échange retenue.

### Phase de run (évolutions fonctionnelles et maintenance applicative)
Tribü fait toujours appel à Elao pour faire évoluer son produit régulièrement en fonction des besoins remontés par ses techniciens et ses clients.  


## Les applications

### Pour les techniciens dans les ateliers : une application fluide et collaborative pour les techniciens

Tribü cherchait à obtenir une interface à l’attention des techniciens, chargés de trier et peser les ordures, dans l’atelier. 
Les contraintes sont les suivantes :

* Une ergonomie pensée pour des utilisateurs utilisant des gants et souhaitant aller au plus vite pour saisir les métriques
* Plusieurs utilisateurs peuvent travailler en parallèle sur une même fiche et voir les ajouts des autres utilisateurs (saisie collaborative)

L’équipe, pour répondre à ces spécifications, a fait le choix d’utiliser React, un framework JavaScript, afin de répondre au besoin d’une interface dynamique et agréable à utiliser. De plus, les données se rafraîchissent sans avoir besoin de recharger la page, ce qui permet de collaborer à plusieurs sur la même page.

<figure>
    <img src="images/etudes-de-cas/tribu-saisie.png" alt="Application de saisie de tri de déchets">
    <figcaption>
      <span class="figure__legend">L'interface de saisie côté technicien</span>
    </figcaption>
</figure>

### Pour les entreprises : une représentation élégante et dynamique des données

L’un des points forts de Tribü est de proposer à ses clients de suivre la quantité de déchets jetés chaque mois, et surtout de connaître le résultat du tri. Il est par exemple possible, pour le carton ou le papier, de connaître le pourcentage de “bon” tri, afin d’améliorer la démarche de recyclage au sein de l’entreprise. Mais il existe de nombreuses catégories de déchets, et l’interface est limitée. Celle-ci doit permettre, d’un coup d’oeil, d’obtenir les informations principales et de pouvoir imprimer le résultat.

Pour  l’intégration du design, nous avons opté pour SVG, un format de données ASCII, afin de tracer les graphiques. Les clients ont ainsi accès à une interface performante, très rapide à charger, avec le maximum d’informations, tout en restant lisible grâce à cette technologie.

Pour compléter l'interface, les entreprises clientes de Tribü ont également la possibilité d'exporter un PDF de leur rapport afin de l'afficher dans leurs locaux et sensibiliser leurs collaborateurs au bon tri. 

<figure>
    <img src="images/etudes-de-cas/tribu-reporting.png" alt="Rapport de tri de déchets">
    <figcaption>
      <span class="figure__legend">L'interface de reporting côté client</span>
    </figcaption>
</figure>
