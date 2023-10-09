---
title: "Tribü"
lastModified: "2021-01-17"
date: "2019-01-09"

# Params
metaDescription: "Tribü — recyclage intelligent de déchets."
description: "Tribü propose un dispositif permettant aux entreprises un recyclage intelligent de leurs déchets."
websiteUrl: http://www.tribu-recyclage.fr/
shortDescription: "Solution de gestion de déchets en entreprise"
clients: Tribü
size: 3 mois
services: ["Accompagnement", "Développement"]
terms: ["symfony", "react", "html", "css", "svg"]
members: ["aldeboissieu", "tjarrand", "adefrance", "xgorse"]
images: ["content/images/casestudies/tribu-reporting.png"]
---

## Le contexte du projet

**Tribü est une entreprise de recyclage : elle propose la mise en place un dispositif de tri des déchets chez ses clients** (entreprises disposant de bureaux, chantiers de construction, restaurants collectifs…). Sa force est de proposer **un suivi précis récapitulé dans un reporting mensuel**, afin que les entreprises clientes puissent connaître exactement les quantités de déchets produits par catégorie et avoir une visibilité sur la bonne réalisation du tri. L'enjeu de Tribü est avant tout de faire de la pédagogie auprès de ses clients pour que les déchets soit de mieux en mieux triés, de mois en mois et d'année en année.

**Tribü a confié à Elao le développement de son applicatif métier lui permettant d’assurer à la fois la gestion quotidienne de ses activités de tri, la supervision par entreprise et de proposer à ses clients un espace de reporting complet.**

Un projet dont les valeurs nous ont beaucoup fait écho et que nous avons pris beaucoup de plaisir à réaliser.

## L'expertise Elao déployée pour l'application Tribü

### Ateliers de recueil du besoin
L'équipe Elao s'est déplacée dans les ateliers de Tribü afin de comprendre les différentes étapes du tri (pesée, saisie des données, réalisation des rapports) et proposer une solution pertinente quant à leurs besoins. Quand nous avons fait les premières visites, Tribü réalisait la saisie de façon manuelle.
À partir de ces ateliers, nous avons été en mesure de proposer une feuille de route fonctionnelle permettant d'arriver à une première version du projet.
**Les besoins étaient double : proposer une solution efficace pour les techniciens et un rapport pour les entreprises qui soit le plus clair possible.**

### Ateliers UX/UI
Les développeurs Elao sont avant tout des concepteurs et n'hésitent pas à être force de proposition d'un point de vue fonctionnel.
Les étapes de conception des parcours utilisateurs (UX) et des maquettes d'interface utilisateurs (UI) ont été réalisées main dans la main avec Mathilde Vandier, designer freelance, avec laquelle nous avons itéré du début à la fin du projet.

### Phase de build (développement)
Anne-Laure, Thomas et Amélie ont mené de front toute la phase de développement, **accompagnant l'équipe de Tribü dans la rédaction des spécifications fonctionnelles** pour anticiper tous les cas nominaux du projet. **Ils ont ensuite posé les bases techniques, développé chaque fonctionnalité, réalisé les tests automatisés et la recette fonctionnelle, jusqu'à l'intégration HTML / CSS aux petits oignons.** La mise en production de la première version a permis à Tribü de tester très vite ses applicatifs et les proposer rapidement à leurs clients dans la foulée.

### Phase de run (évolutions fonctionnelles et maintenance applicative)
Depuis la mise en production de ses applications, Tribü fait appel à Elao de façon régulière pour faire évoluer son produit en fonction des besoins remontés par ses techniciens et ses clients.


## Les applications

### Pour les techniciens Tribü dans l'atelier : une application fluide et collaborative

Tribü avait besoin d'automatiser la remontée des données saisies par les techniciens, chargés de trier et peser les ordures, dans l’atelier.

**Les contraintes étaient les suivantes :**

* Une ergonomie pensée pour des utilisateurs munis de gants et souhaitant aller au plus vite pour saisir les métriques ;
* Une interface collaborative, permettant à plusieurs personnes de travailler en parallèle sur une même fiche et voir les ajouts de ses pairs.

Pour répondre à ces besoins et aux spécifications fonctionnelles du produit Tribü, l'équipe technique d'Elao a fait le choix d’utiliser React, un framework JavaScript, afin d'avoir une interface fluide, dynamique et surtout, **agréable à utiliser**. De plus, pour permettre la collaboration sur une même page, les données se rafraîchissent sans avoir besoin de recharger la page.

<figure>
    <img src="content/images/casestudies/tribu-saisie.png" alt="Application de saisie de tri de déchets">
    <figcaption>
      <span class="figure__legend">L'interface de saisie côté technicien</span>
    </figcaption>
</figure>

### Pour les entreprises clientes Tribü : une représentation élégante et dynamique des données

**L’un des points forts de Tribü est de proposer à ses clients de suivre la quantité de déchets jetés chaque mois, et surtout de connaître le résultat du tri.** Il est par exemple possible, pour le carton ou le papier, de connaître le pourcentage de “bon” tri, afin d’améliorer la démarche de recyclage au sein de l’entreprise. Par exemple, une cannette jetée dans un bac papier est considérée comme "mauvais tri" et sera remontée dans l'interface du client. Ce dernier peut alors se servir de toutes ces données pour sensibiliser ses collaborateurs.

<figure>
    <img src="content/images/casestudies/tribu-reporting.png" alt="Rapport de tri de déchets">
    <figcaption>
      <span class="figure__legend">L'interface de reporting côté client</span>
    </figcaption>
</figure>

Pour  l’intégration du design, nous avons opté pour SVG, un format de données ASCII, afin de tracer les graphiques. Les clients ont ainsi accès à une interface performante, très rapide à charger, avec le maximum d’informations, tout en restant lisible grâce à cette technologie. Thomas, développeur sur le projet, en parle [sur son blog personnel](https://thomas.jarrand.fr/blog/symfony-twig-svg/).

**En complément de l'interface, les entreprises utilisatrices de Tribü ont également la possibilité d'exporter un document PDF de leur rapport afin de l'afficher dans leurs locaux et sensibiliser leurs collaborateurs au bon tri.**

<figure>
    <img src="content/images/casestudies/tribu-reporting-pdf.png" alt="Rapport de tri de déchets en PDF imprimable">
    <figcaption>
      <span class="figure__legend">Le rapport de tri en PDF imprimable</span>
    </figcaption>
</figure>

> Chez TRiBü, nous sommes pleinement satisfaits de notre collaboration avec ELAO. Leur équipe a parfaitement su comprendre notre projet, nos besoins.

> La mise en œuvre opérationnelle a été maitrisée et le résultat répond complètement à nos attentes, et surtout aux besoins de nos clients utilisateurs.
