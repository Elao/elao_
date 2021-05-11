---
title: "MyJob.Company"
lastModified: "2021-04-16"
date: "2021-04-16"

# Params
metaDescription: "MyJob.Company — Recrutement par cooptation."
description: "MyJob.Company propose une plateforme d'offres d'emploi fonctionnant sur le principe de la cooptation."
websiteUrl: https://myjob.company/fr/
shortDescription: "Recrutement par cooptation."
clients: MyJob.Company
size: 6 mois
services: ["Refonte", "Développement"]
terms: ["symfony", "elasticsearch", "vue-js", "html", "css", "payment"]
members: ["mcolin", "adefrance"]
images: ["content/images/casestudies/headers/mjc-banner.jpg"]
---

## Le contexte du projet

**MyJob.Company est une entreprise de recrutement : elle propose une plateforme d'offres d'emploi (job board) utilisant la cooptation.** Sa force est d'avoir une base de d'environ 60&nbsp;000 coopteurs capables de recommander des personnes de leurs réseaux pour les offres d'emploi publiées sur la plateforme. Les coopteurs perçoivent une prime pour les cooptations menant à un recrutement et les recruteurs ont accès une grande communauté de coopteurs.

**MyJob.Company a confié à Elao le développement de la refonte de leur plateforme ainsi que le développement de nouvelles fonctionnalités dans le cadre d'un changement de business model.**

Profitant de cette refonte, MyJob.Company a souhaité intégrer davantage de transparence et d'ouverture dans sa plateforme, ce qui a fait écho aux valeurs d'Elao.

## L'expertise Elao déployée pour l'application MyJob.Company

### Ateliers de recueil du besoin
Elao a animé plusieurs ateliers d'expression du besoin, dans une démarche de co-conception. Ces ateliers nous ont permis de comprendre les enjeux de la refonte ainsi que les principes de la cooptation et la recommandation. Nous avons défini ensemble un premier périmètre fonctionnel couvrant l'existant et permettant une bascule vers la nouvelle plateforme le plus tôt possible.
**Les besoins étaient double : proposer une refonte technique et accompagner le changement de business model.**

### Ateliers UX/UI
Les étapes de conception des parcours utilisateurs (UX) et des maquettes d'interface utilisateurs (UI) ont été réalisées main dans la main avec <a href="http://www.mvandier.com/" target="_blank">Mathilde Vandier</a>, designer freelance, avec laquelle nous avons itéré du début à la fin du projet.

### Phase de build (développement)
Maxime et Amélie ont mené de front toute la phase de développement, **accompagnant l'équipe de MyJob.Company dans la rédaction des spécifications fonctionnelles** pour anticiper tous les cas nominaux du projet. **Ils ont ensuite posé les bases techniques, développé chaque fonctionnalité, réalisé les tests automatisés et la recette fonctionnelle, jusqu'à l'intégration HTML / CSS aux petits oignons.** La mise en production de la première version a permis à MyJob.Company de proposer rapidement l'outil à ses utilisateurs.

### Bascule
La bascule vers la nouvelle plateforme a été l'un des principaux enjeux de ce projet. La base de données contenant plusieurs dizaines de milliers d'utilisateurs et d'offres d'emplois a dû être migrée et la bascule a dû être réalisée de la façon la plus transparente possible pour les utilisateurs.

### Phase de run (évolutions fonctionnelles et maintenance applicative)
Depuis la mise en production, nous avons continué à développer de nouvelles fonctionnalités tout en faisant évoluer l'application en fonction des besoins remontés par l'équipe de MyJob.Company et par ses utilisateurs.

## Les applications

### Pour les recruteurs : un ATS pour publier des offres et suivre les candidures

Un <abbr title="Applications Tracking System">ATS</abbr> est un logiciel permettant gérer un flux de candidatures à travers un parcours de recrutement. MyJob.Company souhaitait offrir à ses clients recruteurs davantage de contrôle et de suivi sur les candidatures. L'application devait proposer plusieurs parcours configurables afin de s'adapter aux différents processus de recrutement de ses clients.

L'autre enjeu était la qualité des offres proposées sur la plateforme. Grâce à son savoir-faire dans le domaine du recrutement, MyJob.Company a conçu un parcours de rédaction d'offres permettant d'apporter des informations pertinentes et de qualité.

**Les contraintes étaient les suivantes :**

* Une interface claire et simple pour les recruteurs.
* Un parcours personnalisé, permettant de s'adapter aux différents besoins des clients.
* Une gestion des candidatures la plus fluide possible.

<figure>
    <img src="content/images/casestudies/mjc-recruteur-mes-offres.png" alt="Recruteur - Mes offres d'emploi">
    <figcaption>
      <span class="figure__legend">Recruteur - Mes offres d'emploi</span>
    </figcaption>
</figure>

### Pour les coopteurs : un moteur de recherche et un suivi de leurs cooptés

Le coopteur devait pouvoir accéder aux offres d'emploi grâce à un moteur de recherche multi-critères et géolocalisé. MyJob.Company souhaitait également offrir davantage de suivi et de transparence à ses coopteurs. L'application devait donc leur permettre de suivre l'avancement des candidatures qu'ils ont générées ainsi que leur statut : si elle ont été acceptées ou refusées et pour quelle raison.

**Les contraintes étaient les suivantes :**
* Permettre de recommander simplement un profil ou partager l'offre à ses réseaux ;
* Avoir un suivi en temps réel et de façon transparente des candidatures ;
* Proposer un moteur de recherche puissant et pertinent.

Pour le moteur de recherche, nous avons utilisé [ElasticSearch](../technologies/elasticsearch.md) qui permet à la fois une recherche par pertinence et une recherche par géolocalisation.

<figure>
    <img src="content/images/casestudies/mjc-coopteur-recherche.png" alt="Coopteur - Moteur de recherche">
    <figcaption>
      <span class="figure__legend">Coopteur - Moteur de recherche</span>
    </figcaption>
</figure>

### Pour les candidats

Les candidats devaient également pouvoir suivre leurs candidatures et pouvoir utiliser l'application comme un job board plus classique, c'est-à-dire en ayant la possibilité de rechercher une offre d'emploi et de postuler directement.

**Les contraintes étaient les suivantes :**
* Permettra au candidat de retrouver toutes ses candidatures ;
* Trouver facilement une offre d'emploi grâce au moteur de recherche.

<figure>
    <img src="content/images/casestudies/mjc-candidat-suivi.png" alt="Candidat - Suivi de la candidature">
    <figcaption>
      <span class="figure__legend">Candidat - Suivi de la candidature</span>
    </figcaption>
</figure>
