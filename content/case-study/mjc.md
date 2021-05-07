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
size: 2 ans
services: ["Refonte", "Développement"]
technologies: ["symfony", "elasticsearch", "vue-js", "html", "css"]
members: ["mcolin", "adefrance"]
images: ["images/casestudies/headers/mjc-banner.jpg"]
---

## Le contexte du projet

**MyJob.Company est une entreprise de recrutement : elle propose une plateforme d'offres d'emploi (job board) utilisant la cooptation.** Sa force est d'avoir une base de d'environ 60&nbsp;000 coopteurs capables de recommander leurs connaissances et des personnes de leurs réseaux pour les offres d'emploi publiées sur la plateforme. Les coopteurs perçoivent une prime pour les cooptations menant à un recrutement et les recruteurs ont accès une grande communauté de coopteurs.

**MyJob.Company a confié à Elao le développement de la refonte de leur plateforme ainsi que le développement de nouvelles fonctionnalités dans le cadre d'un changement de business model.**

Profitant de cette refonte, MyJob.Company a souhaité intégrer davantage de transparance et d'ouverture dans sa plateforme, ce qui a fait écho aux valeurs d'Elao.

## L'expertise Elao déployée pour l'application Tribü

### Ateliers de recueil du besoin
Elao a reçu l'équipe de MyJob.Company dans ses locaux afin de réaliser différents ateliers d'expression du besoin et de co-conception. Ces ateliers nous ont permis de comprendre les différents enjeux de la refonte ainsi que les différents principes de la cooptation et la recommandation. Nous avons défini ensemble un premier périmètre fonctionnel couvrant l'existant et permettant une bascule vers la nouvelle plateforme le plus tôt possible.
**Les besoins étaient double : proposer une refonte technique et accompagner le changement de business model.**

### Ateliers UX/UI
Les développeurs Elao sont avant tout des concepteurs, capables d'être force de proposition d'un point de vue fonctionnel.
Les étapes de conception des parcours utilisateurs (UX) et des maquettes d'interface utilisateurs (UI) ont été réalisées main dans la main avec [Mathilde Vandier](http://www.mvandier.com/), designer freelance, avec laquelle nous avons itéré du début à la fin du projet.

### Phase de build (développement)
Maxime et Amélie ont mené de front toute la phase de développement, **accompagnant l'équipe de MyJob.Company dans la rédaction des spécifications fonctionnelles** pour anticiper tous les cas nominaux du projet. **Ils ont ensuite posé les bases techniques, développé chaque fonctionnalité, réalisé les tests automatisés et la recette fonctionnelle, jusqu'à l'intégration HTML / CSS aux petits oignons.** La mise en production de la première version a permis à MyJob.Company de tester très vite la refonte auprès des ses utilisateurs.

### Bascule
La bascule vers la nouvelle plateforme a été un des principaux eujeux de ce projet. La base de données contenant plusieurs dizaines de milliers d'utilisateurs et d'offres d'emplois a dû être migrée et la bascule a dû être réalisée de façon la plus transparente possible pour les utilisateurs.

### Phase de run (évolutions fonctionnelles et maintenance applicative)
Depuis la mise en production, nous avons continué à développer de nouvelles fonctionnalités tout en faisant évoluer l'application en fonction des remontés de MyJob.Company et de ses utilisateurs.

## Les applications

### Pour les recruteurs : un ATS pour publier des offres et suivre les candidures

Un <abbr title="Applications Tracking System">ATS</abbr> est un logiciel permettant gérer un flux de candidatures à travers un workflow de recrutement. MyJob.Company souhaitait offrir à ses clients recruteurs d'avantage de contrôle et de suivi sur les candidatures. L'application devait proposer plusieurs workflows paramétrables afin de d'adapter aux différents processus de recrutement de ses clients.

L'autre enjeu était la qualité des offres proposer sur la plateforme. Grâce à son savoir faire dans le domaine du recrutement, MyJob.Company a conçu un tunnel de rédaction permettant de publier une offre avec les informations pertinantes et de qualité.

**Les contraintes étaient les suivantes :**

* Une interface claire et simple pour les recruteurs.
* Pouvoir s'adapter aux différents besoins des clients.
* Pouvoir traiter les candidatures grâce à un workflow.

<figure>
    <img src="images/casestudies/mjc-recruteur-mes-offres.png" alt="Recruteur - Mes offres d'emploi">
    <figcaption>
      <span class="figure__legend">Recruteur - Mes offres d'emploi</span>
    </figcaption>
</figure>

### Pour les coopteurs : un moteur de recherche et un suivi de leurs cooptés

Le coopteur devait pouvoir rechercher les différentes offres d'emploi grâce à un moteur de recherche multi-critères et géolocalisé. MyJob.Company souhaitait également offrir davantage de suivi et de transparence à ses coopteurs. L'application devait donc permettre aux coopteurs de suivre l'avancement des candidatures qu'ils ont généré, si elle ont été acceptées ou refusées et pour quelle raison.

**Les contraintes étaient les suivantes :**
* Pouvoir simplement recommander un profil ou partager l'offre a ses réseaux.
* Pouvoir suivre en temps réel et de façon transparante les candidatures.
* Un moteur de recherche pertinent.

Pour le moteur de recherche, nous avons utilisé [ElasticSearch](../technologies/elasticsearch.md) qui permet à la fois une recherche par pertinence et une recherche par géolocalisation.

<figure>
    <img src="images/casestudies/mjc-coopteur-recherche.png" alt="Coopteur - Moteur de recherche">
    <figcaption>
      <span class="figure__legend">Coopteur - Moteur de recherche</span>
    </figcaption>
</figure>

### Pour les candidats

Les canditats devaient également pouvoir suivre leurs candidatures et pouvoir utiliser l'application comme un jobboard plus classique. C'est à dire pouvoir rechercher une offre d'emploi et postuler directement.

**Les contraintes étaient les suivantes :**
* Retrouver toutes ses candidatures.
* Trouver facilement une offre d'emploi grâce au moteur de recherche.

<figure>
    <img src="images/casestudies/mjc-candidat-suivi.png" alt="Candidat - Suivi de la candidature">
    <figcaption>
      <span class="figure__legend">Candidat - Suivi de la candidature</span>
    </figcaption>
</figure>
