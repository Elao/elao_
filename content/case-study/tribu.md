---
title: "Tribü"
lastmod: 2021-01-17
date: "2019-01-09"
name: "Tribü"

# Params
metaDescription: "Tribü — recyclage intelligent de déchets."
description: "Tribü propose un dispositif permettant aux entreprises un recyclage intelligent de leurs déchets."
caseUrl: http://www.tribu-recyclage.fr/
clients: Tribü
users: Techniciens Tribü et entreprises engagées dans le tri de leurs déchets
size: 3 mois
services: ["Accompagnement", "Développement"]
technologies: ["symfony", "react", "html", "css", "svg"]
members: ["aldeboissieu", "tjarrand", "adefrance", "xgorse"]
images: ["images/casestudies/headers/tribu-banner.jpg"]
---

## Le contexte du projet

Tribü est une entreprise de recyclage : elle propose la mise en place un dispositif de tri des déchets chez ses clients (entreprises disposant de bureaux, chantiers de construction, restaurants collectifs…). Sa force est de proposer un suivi précis récapitulé dans un reporting mensuel, afin que les entreprises clients puissent connaître exactement les quantités de déchets produits par catégorie et avoir une visibilité sur la bonne réalisation du tri. L'enjeu de Tribü est avant tout de faire de la pédagogie auprès de ses clients pour que le tri soit de mieux en mieux effectué, de mois en mois et d'année en année.

Tribü a confié à Elao le développement de son applicatif métier lui permettant d’assurer à la fois la gestion quotidienne de ses activités de tri, la supervision par entreprise et de proposer à ses clients un espace de reporting complet. 

Un projet dont les valeurs nous ont beaucoup fait écho et que nous avons pris beaucoup de plaisir à réaliser. 

## L'expertise Elao déployée pour l'application Tribü

### Ateliers de recueil du besoin
L'équipe Elao s'est déplacée dans les ateliers de Tribü afin de comprendre les différentes étapes du tri (pesée, saisie des données, réalisation des rapports) et proposer une solution pertinente quant à leurs besoins. 
À partir de ces ateliers, nous avons été en mesure de proposer une feuille de route fonctionnelle permettant d'arriver à une première version du projet. 
Les besoins étaient double : proposer une solution efficace pour les techniciens et un rapport pour les entreprises qui soit le plus clair possible.

### Ateliers UX/UI
Les développeurs Elao sont avant tout des concepteurs. L'UX/UI a été réalisée main dans la main avec Mathilde Vandier, designer freelance, avec laquelle nous avons itéré du début à la fin du projet. 

### Phase de build (développement) 
Anne-Laure, Thomas et Amélie ont mené de front toute la phase de développement, accompagnant l'équipe de Tribü dans la rédaction des spécifications fonctionnelles pour anticiper tous les cas nominaux du projet. Ils ont ensuite posé les bases techniques, développé chaque fonctionnalité, réalisé les tests automatisés et la recette fonctionnelle, jusqu'à l'intégration HTML / CSS aux petits oignons. La mise en production de la première version a permis à Tribü de tester très vite ses applicatifs et les proposer rapidement à leurs clients dans la foulée.

### Phase de run (évolutions fonctionnelles et maintenance applicative)
Depuis la mise en production de ses applications, Tribü fait appel à Elao de façon régulière pour faire évoluer son produit en fonction des besoins remontés par ses techniciens et ses clients.  


## Les applications

### Pour les techniciens dans les ateliers : une application fluide et collaborative

Tribü avait besoin d'automatiser la remontée des données saisies par les techniciens, chargés de trier et peser les ordures, dans l’atelier. 

Les contraintes sont les suivantes :

* Une ergonomie pensée pour des utilisateurs munis de gants et souhaitant aller au plus vite pour saisir les métriques ;
* Une interface collaborative, permettant à plusieurs personnes de travailler en parallèle sur une même fiche et voir les ajouts de ses pairs.

L’équipe Elao, pour répondre à ces spécifications, a fait le choix d’utiliser React, un framework JavaScript, afin de répondre au besoin d’une interface dynamique et agréable à utiliser. De plus, les données se rafraîchissent sans avoir besoin de recharger la page, ce qui permet de collaborer à plusieurs sur la même page.

<figure>
    <img src="images/casestudies/tribu-saisie.png" alt="Application de saisie de tri de déchets">
    <figcaption>
      <span class="figure__legend">L'interface de saisie côté technicien</span>
    </figcaption>
</figure>

### Pour les entreprises : une représentation élégante et dynamique des données

L’un des points forts de Tribü est de proposer à ses clients de suivre la quantité de déchets jetés chaque mois, et surtout de connaître le résultat du tri. Il est par exemple possible, pour le carton ou le papier, de connaître le pourcentage de “bon” tri, afin d’améliorer la démarche de recyclage au sein de l’entreprise. Mais il existe de nombreuses catégories de déchets, et l’interface est limitée. Celle-ci doit permettre, d’un coup d’oeil, d’obtenir les informations principales et de pouvoir imprimer le résultat.

<figure>
    <img src="images/casestudies/tribu-reporting.png" alt="Rapport de tri de déchets">
    <figcaption>
      <span class="figure__legend">L'interface de reporting côté client</span>
    </figcaption>
</figure>

Pour  l’intégration du design, nous avons opté pour SVG, un format de données ASCII, afin de tracer les graphiques. Les clients ont ainsi accès à une interface performante, très rapide à charger, avec le maximum d’informations, tout en restant lisible grâce à cette technologie.

Pour compléter l'interface, les entreprises clientes de Tribü ont également la possibilité d'exporter un PDF de leur rapport afin de l'afficher dans leurs locaux et sensibiliser leurs collaborateurs au bon tri. 

<figure>
    <img src="images/casestudies/tribu-reporting-pdf.png" alt="Rapport de tri de déchets en PDF imprimable">
    <figcaption>
      <span class="figure__legend">Le rapport de tri en PDF imprimable</span>
    </figcaption>
</figure>
