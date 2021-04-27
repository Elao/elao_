---
title: "Musique & Music"
lastModified: "2021-04-21"
date: "2021-04-21"

# Params
metaDescription: "Musique & Music — librairie musicale pour les professionnels"
description: "Musique & Music permet aux professionnels de la vidéo d'enrichir leurs productions avec de l'illustration sonore."
websiteUrl: https://www.musique-music.com/
shortDescription: "Librairie musicale pour les professionnels"
clients: Musique & Music
size: 3 mois
services: ["Accompagnement", "Développement"]
technologies: ["symfony", "react", "html", "css", "algolia", "svg"]
members: ["bleveque", "tjarrand", "adefrance", "msteinhausser", "aldeboissieu", "xavierr"]
images: ["images/casestudies/headers/musique-music-banner.jpg"]
---

## Le contexte du projet

**Musique & Music est un éditeur spécialisé dans la musique de production dédiée aux professionnels. L'application web permet aux monteurs vidéos de chercher facilement des sons afin d'illustrer leurs productions.** Parmi les atouts de l'application, il y a notamment la richesse du catalogue, la fluidité de la recherche et la pertinence des résultats proposés. Une recherche par similarité permet aux clients de Musique & Music de rechercher finement un style de musique en important des fichiers audio.

**Musique & Music a confié à Elao le développement de son application web ainsi que de son back-office,** permettant d'avoir la main sur tous les aspects de son site.

## L'expertise Elao déployée pour l'application Musique Music

### Ateliers de recueil du besoin
Musique & Music n'en était pas à sa première version, l'application existait déjà depuis plusieurs années mais la dette technique et l'obsolescence du code existant a décidé les fondateurs à repartir d'une feuille blanche.
Il a donc fallu tirer l'essence de l'existant afin de proposer une version que l'on pouvait rapidement mettre en production, tout en assurant que les fonctionnalités clés soient présentes afin que les utilisateurs retrouvent leurs petits.
À partir de ces ateliers, nous avons été en mesure de proposer une feuille de route fonctionnelle permettant d'arriver à une "nouvelle première version" du projet.
Très rapidement, outre le front-office, le besoin d'un back-office efficace a émergé, dans le but de rendre plus efficaces les équipes Musique & Music.

### Ateliers UX/UI
Les développeurs Elao sont avant tout des concepteurs et n'hésitent pas à être force de proposition d'un point de vue fonctionnel.
Les étapes de conception des parcours utilisateurs (UX) et des maquettes d'interface utilisateurs (UI) ont été réalisées main dans la main avec Mathilde Vandier, designer freelance, avec laquelle nous avons itéré du début à la fin du projet.

### Phase de build (développement)
C'est avec Anne-Laure et Xavier qu'Antoine, CTO de Musique & Music, a mené toute la phase de spécifications fonctionnelles. Il était nécessaire sur ce projet d'apporter une vraie force humaine, car les délais étaient serrés et nous n'étions pas trop de trois. Benjamin, Thomas et Amélie ont eux, mené de front toute la phase de développement : **ils ont ensuite posé les bases techniques, développé chaque fonctionnalité, réalisé les tests automatisés et la recette fonctionnelle, jusqu'à l'intégration HTML / CSS pixel-perfect.** La mise en production de la première version a permis à Musique & Music d'avoir une première version fonctionnelle à proposer à sa base existante d'utilisateurs.

### Phase de run (évolutions fonctionnelles et maintenance applicative)
Depuis la mise en production de ses applications, Musique & Music fait appel à Elao de façon régulière pour faire évoluer son produit en fonction des besoins remontés par ses équipes et ses utilisateurs. Parmi les quelques évolutions, nous avons automatisé un grand nombre d'imports et d'exports, aussi bien pour de l'interne que pour des services tiers.

<figure>
    <img src="images/casestudies/musique-musique-homepage.jpg" alt="Accueil de l'application Musique & Music">
    <figcaption>
      <span class="figure__legend">Page d'accueil</span>
    </figcaption>
</figure>

## Les applications

### Pour les professionnels du montage vidéo : une bibliothèque de musique fluide et dynamique

Musique & Music met un point d'honneur à proposer les interfaces les plus fluides possibles et une expérience utilisateur de haut niveau. Il était nécessaire d'aller vers un maximum de réactivité de l'applicatif.

Pour répondre à ces besoins et aux spécifications fonctionnelles du produit Musique & Music, l'équipe technique d'Elao a fait le choix d’utiliser React, un framework JavaScript, couplé à une api GraphQL, afin d'avoir une interface fluide, dynamique et surtout, **agréable à utiliser**.

<figure>
    <img src="images/casestudies/musique-musique-results.jpg" alt="La recherche Musique & Music">
    <figcaption>
      <span class="figure__legend">La recherche Musique & Music, propulsée par Algolia</span>
    </figcaption>
</figure>

Mais cette fluidité d'utilisation ne devait pas se faire au détriment des performances de référencement : c'est pourquoi toute la partie publique de Musique & Music est **pré-rendue coté serveur** avec le même code React piloté, exécuté puis servi par l'application Symfony.

Ainsi, toutes les pages sont servies avec leurs contenus et leurs méta-données complètes avant-même l'execution du Javascript dans le navigateur du client (ou des robots d'indexation des moteurs de recherche).

#### Côté recherche, la puissance d'Algolia
La base Musique & Music compte plus de 650 000 titres, chacun avec ses méta-données, ses labels. La pertinence et la vélocité de la recherche sont deux critères déterminants dans le produit M&M, c'est pourquoi nous avons fait le choix d'embarquer la solution Algolia. 

### Pour l'équipe Musique & Music, un espace d'administration adapté à leurs besoins

L'équipe de Musique & Music utilise au quotidien l'espace administrateurs : les commerciaux ont un œil sur les nouveaux inscrits, les responsables de production ajoutent quotidiennement de nouvelles playlists et de nouveaux albums. **Il leur fallait un espace d'administration leur permettant d'effectuer toutes ces tâches.**

<figure>
    <img src="images/casestudies/musique-musique-playlists.jpg" alt="Les playlists Musique & Music">
    <figcaption>
      <span class="figure__legend">Les playlists Musique & Music</span>
    </figcaption>
</figure>