---
title: "Marque blanche MyJob.Company"
date: "2023-12-07"

# Params
metaDescription: "MyJob.Company — Marque blanche de la cooptation."
description: "Évolution de leur plateforme d'emploi vers une marque blanche "
websiteUrl: https://myjob.company/fr/
shortDescription: "Recrutement par cooptation."
clients: MyJob.Company
size: 6 mois
services: ["Accompagnement", "Développement"]
terms: ["react.js, Next.js, GraphQL, PWA]
members: ["mcolin", "adefrance", "lvilleneuve", "equentin"]
images: ["content/images/casestudies/mjc/image-couverture-mjc.png"]
---

## Le contexte du projet

MyJob.Company, entreprise de recrutement basée sur le système de la cooptation, a sollicité les services d’Elao pour faire évoluer leur plateforme d’offres d’emploi ([déjà développée par nos soins](https://www.elao.com/etudes-de-cas/mjc)) autour du principe de la **marque blanche**. 

Actuellement, la plateforme permet déjà aux entreprises de créer des comptes recruteurs, de postuler à des offres d’emplois et de gérer leurs candidatures. Avec la marque blanche, MyJob.Company se concentre sur les besoins des recruteurs afin de leur proposer une plateforme plus personnalisée et en lien avec leurs besoins spécifiques.

La marque blanche repose sur 4 grands axes : 

* Un nom de domaine dédié au client recruteur 
* Un cloisonnement des données (le recruteur aura uniquement ses offres de visibles sur sa marque blanche)
* Une personnalisation du design permettant aux recruteurs d’avoir une plateforme correspondante à leur charte graphique, avec leur logo, leurs couleurs.
* Une personnalisation des fonctionnalités afin de coller au plus près des besoins du recruteur.

<figure>
    <img src="content/images/casestudies/mjc/marque-blanche-mjc.png" alt="Tidy Tab Titles">
    <figcaption>
      <span class="figure__legend">Marque Blanche</span>
    </figcaption>
</figure>


## L'expertise Elao déployée pour l'application MyJob.Company

### Ateliers de recueil du besoin

Plusieurs réunions afin de définir le besoin ont été mises en place de manière progressive et itérative. Le besoin initial était assez concis puisque reposait sur l’idée de la marque blanche avec des fonctionnalités pouvant être activées ou non et la personnalisation de l’interface. 

Au fil des ateliers, le besoin s’est complexifié. De nouveaux besoins sont apparus au niveau de la marque blanche, de nouvelles fonctionnalités sont arrivées comme la possibilité de postuler sans avoir d’offre ou encore la mise en place d’un système de matching. Face à ce développement, a été évoqué le fait de décorréler la marque blanche de MJC afin de concevoir une plateforme à part entière pouvant être vendue à des entreprises.


### Ateliers UX/UI

Comme lors de la conception [UX/UI](https://www.elao.com/glossaire/ui-ux) du projet MyJob.Company, nous avons travaillé avec [Bien Fondé](https://www.bien-fonde.com/fr) pour la réalisation des maquettes. Ces dernières ont été réalisées en **mobile first** afin de répondre au besoin de l'entreprise qui souhaitait être plus présente sur mobile.

Face au développement de la demande initiale et les fonctionnalités allant évoluer de manière personnalisée en fonction des clients, nous avons proposé la création d’un **design system** afin que les équipes de MyJob.Company puissent facilement maquetter de nouvelles fonctionnalités et que les développeurs puissent créer de nouveaux écrans tout en conservant une certaine homogénéité. 

<figure>
    <img src="content/images/casestudies/mjc/maquettes-mobile.png" alt="Tidy Tab Titles">
    <figcaption>
      <span class="figure__legend">Parsing de CV</span>
    </figcaption>
</figure>


### Phase de développement

Avant de se lancer dans le développement complet du projet, l’enjeu était de nous assurer que la stack technique choisie répondait bien à l’ensemble des besoins. Maxime a donc réalisé un POC **[React](https://www.elao.com/glossaire/react)/Next.js** avec du **GraphQL**, du **SSR** et de la **PWA** autour d’un système de marque blanche. Puis, il a bootstrapé le design system avec **Storybook** et **Tailwind**. Il a créé quelques composants afin de vérifier également que cette stack correspondait aux attentes. 

Ensuite, l’équipe front a pris le relais, Laurie et Amélie sont intervenues pour intégrer tous les composants et les maquettes. En parallèle, Maxime avançait sur l’API et le fonctionnel.


## Les évolutions

### De la personnalisation

Avec la marque blanche, MyJob.Company donne l’opportunité à ses clients d’évoluer dans un environnement qui leur est propre, à l’image de leur société. L'interface est adaptée aux couleurs de leur charte graphique, avec leur logo et chacun peut choisir d’activer ou non certaines fonctionnalités (ou features flags).

### Une nouvelle manière de postuler 

Outre le principe de la marque blanche, l’évolution principale se trouve du côté du système de candidature. Jusqu’à maintenant, les candidats pouvaient postuler uniquement en fonction d’une offre. Désormais, ils peuvent mettre à disposition leur CV, qui sera **parsé** et mis en relation avec des offres de la base de données. L’utilisateur pourra ainsi consulter des offres en lien avec sa recherche, via un système de **matching** entre son CV et ces offres d’emploi.

<figure>
    <img src="content/images/casestudies/mjc/parsing-cv.gif" alt="Tidy Tab Titles">
    <figcaption>
      <span class="figure__legend">Parsing de CV</span>
    </figcaption>
</figure>

### Un vivier d'évolution

S'ajoutent à cela plusieurs **évolutions fonctionnelles** afin de proposer **une meilleure interface pour les recruteurs**. Ces améliorations se sont axées autour de la gestion des collaborateurs, du développement de nouveaux filtres dans la liste des offres ou encore du dashboard.

<figure>
    <img src="content/images/casestudies/mjc/dashboard-recruteur.png" alt="Tidy Tab Titles">
    <figcaption>
      <span class="figure__legend">Dashboard Recruteur</span>
    </figcaption>
</figure>

### Une présence sur mobile 

Face aux nouveaux besoins de ce projet (mobile first, design system, personnalisation etc.) et l’intérêt grandissant de développer l’inter-connectivité de la plateforme, nous avons choisit de nous orienter vers le développement d’une application React avec du PWA pour le mobile et du Next.js pour bénéficier du Server Side Rendering (principe de développement reposant sur la création des pages HTML côté serveur afin de les envoyer directement finalisées au navigateur) . 
Du côté du back, nous sommes restés sur le code existant en ajoutant une API GraphQL.

<figure>
    <img src="content/images/casestudies/mjc/favoris-abaka.png" alt="Tidy Tab Titles">
    <figcaption>
      <span class="figure__legend">Offres favorites</span>
    </figcaption>
</figure>


## Les contraintes

	
Avec le côté personnalisable de la marque blanche, beaucoup de fonctionnalités ont dû être paramétrables ce qui a ajouté du challenge à l’équipe.

L’une des contraintes notables fût la **migration continue de l’application monolithe et mono-client** vers une architecture API - Front React - Marque blanche (multi-client) tout en conservant l’application existante fonctionnelle, de la façon la plus transparente pour les utilisateurs.

Également, nous avons dû maintenir l’existant (avec de la TMA et quelques évolutions) tout en nous assurant que les nouvelles fonctionnalités s’inscrivaient dans la même démarche que la marque blanche. 
 
