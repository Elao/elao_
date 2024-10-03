---
title: "Marque blanche MJC"
lastModified: "2024-09-06"
date: "2024-09-06"

# Params
metaDescription: "MyJob.Company - Conception d'une marque blanche"
description: "MyJob.Company - Conception d'une marque blanche reposant sur le principe de la cooptation"
websiteUrl: https://app.myjob.company/
shortDescription: "MyJob.Company - Conception d'une marque blanche reposant sur le principe de la cooptation"
clients: MyJob.Company
size: 1 an
services: ["Développement", "Conception", "Maintenance"]
terms: ["symfony", "elasticsearch", "next.js", "react", "graphql", "pwa"]
members: ["mcolin", "qbrouillet", "adefrance", "lvilleneuve"]
images: ["content/images/casestudies/mjc-marqueblanche/ecran-dashboard-mjc.jpg"]
---

## Le contexte du projet

MyJob.Company est une plateforme de **mise en relation** entre les candidats et les recruteurs basée sur la **cooptation** (technique de recrutement qui consiste à recommander un profil de son réseau). <a href="https://www.elao.com/etudes-de-cas/mjc" target="blank">Cliente d’Elao depuis plusieurs années</a>, MJC nous a contactés dans le but de faire évoluer <a href="https://myjob.company/" target="blank">son produit</a> en proposant des fonctionnalités de **marque blanche** et de cooptation interne.

Face à une demande croissante de leurs clients et à une concurrence proposant des services de cooptation interne en marque blanche, il est apparu naturel pour MyJob.Companny de s'ouvrir à ce marché. À cela, s'ajoute le besoin de diversifier leur **modèle économique**, de capitaliser sur leur expérience du recrutement par cooptation et sur le développement réalisé dans le cadre du **jobboard** MyJob.Company. 

En parallèle, après avoir constaté que plus de la moitié de leur trafic provenait du **mobile**, MyJob.Company a saisi l'occasion de ce projet en marque blanche pour repenser l'**interface** de MyJob.Company en privilégiant une conception "mobile first".

La société a donc confié à Elao la conception et le développement de cette marque blanche. On vous explique plus en détail les tenants et aboutissants du projet 👇

<figure>
    <img src="content/images/casestudies/mjc-marqueblanche/ecran-mobile-mjc.jpg" alt="Version mobile marque blanche">
    <figcaption>
      <span class="figure__legend">Version mobile de la marque blanche</span>
    </figcaption>
</figure>

## L'expertise Elao déployée pour la marque blanche MyJob.Company

### Phase de conception 

Comme a l'accoutumée, Elao a mis en place plusieurs ateliers de recueil du besoin afin de cadrer plus précisément les attentes et le périmètre du projet. 

Tatiana, Product Owner du chez MyJob.Company, a réalisé en amont des **wireframes** permettant d'exprimer les besoins et les grandes fonctionnalités attendues. 
C'est ensuite la société <a href="https://www.bien-fonde.com/fr/" target="blank">Bien Fondé</a> qui a pris le relais et qui a réalisé les **parcours utilisateurs** (UX) ainsi que les **maquettes** (UI) des écrans principaux, en mobile et en desktop. 

En parallèle, nous avons conçu le board projet, le **backlog** avec les différents tickets composant les grandes fonctionnalités et les **itérations** de la marque blanche. 


### Phase de développement (build)

Lors de la phase de développement, l'enjeu principal a été de **maintenir** l'application historique, tout en introduisant la notion de marque blanche et en développant les nouvelles fonctionnalités requises pour le lancement.

L'application historique utilisait une **architecture monolithique**. Développée avec <a href="https://www.elao.com/glossaire/symfony" target="blank">**Symfony**</a>, une seule application comprenait la partie front et la partie admin.

La nouvelle architecture est désormais composée d'une **API**, développée avec Symfony sur la base de code existante et de deux nouvelles applications (front et admin) développées en <a href="https://www.elao.com/glossaire/react" target="blank">**React**</a>.

#### Première étape

La première étape a été d'introduire la notion de marque blanche, puis de cloisonner les données par marques blanches et de développer une API exposant le code métier existant. Cela fut rendu facile par l'utilisation d'une <a href="https://www.elao.com/blog/dev/architecture-hexagonale-symfony" target="blank">**architecture hexagonale**</a> depuis le début du projet en 2018. Nous avons donc pu **réexploiter le code métier existant** avec très peu de modifications. Cela a également permis de minimiser les **régressions**, de ne pas avoir à tout redévelopper et de n'avoir qu'une seule base de code à maintenir pendant la cohabitation avec l'application en production et le développement de la nouvelle version.

Les **tests unitaires** et fonctionnels couvrant déjà une très grande partie de l'application historique ainsi que des déploiements fréquents nous ont permis d'avancer sereinement en vérifiant qu'aucun bug n'était introduit pendant cette phase. Les ajouts et adaptations étaient régulièrement mergés / intégrés dans la base de code de production et déployés, nous assurant ainsi une retro-compatibilité constante.

<figure>
    <img src="content/images/casestudies/mjc-marqueblanche/ecran-mes-offres-mjc.jpg" alt="Écran mes offres">
    <figcaption>
      <span class="figure__legend">Écran "Mes offres"</span>
    </figcaption>
</figure>

#### Seconde étape

La seconde étape a été de développer les deux applications **front** et **admin** en React. Ces deux applications communiquaient avec le métier via la nouvelle API évoquée plus haut. 

Moins risqué car utilisé par un nombre restreint d'utilisateurs, nous avons mis en ligne en premier la nouvelle application d'administration. D'abord en cohabitation avec l'ancien admin pour que les utilisateurs puissent nous faire des retours, tester les nouvelles fonctionnalités, le tout sans être bloqué en cas de régression. Une fois l'application **stabilisée** et jugée assez **complète** par les utilisateurs, l'ancien admin a été débranché.

Nous nous sommes ensuite concentrés sur la finalisation de l'application front. Un **MVP** (Minimum Viable Product) a été défini comprenant les fonctionnalités historiques ainsi que quelques nouveautés justifiant la bascule. Une fois l'ensemble de ces fonctionnalités développées, et après une phase de **recette** intense, le front a été à son tour basculé vers la nouvelle application.

Les nouvelles applications étant alimentées par la même source de données que l’ancienne application monolithique, les bascules ont été transparentes pour les utilisateurs qui ont pu **conserver leurs données ainsi que leurs identifiants de connexion**.

### Phase de mise en production 

La mise en production s'est faite fin mai 2024 après avoir développé toutes les fonctionnalités nécessaires afin d'obtenir un produit opérationnel. 

### Phase de run ou plutôt de build actif 

Aujourd'hui, MJC a encore de nombreuses idées pour faire évoluer sa plateforme de marque blanche, beaucoup de nouvelles fonctionnalités sont à développer et à mettre en place pour étoffer le produit. 
L'équipe Elao continue d'avancer quotidiennement sur le projet en collaboration avec l'équipe de MJC.

<figure>
    <img src="content/images/casestudies/mjc-marqueblanche/ecran-notifications-cv-mjc.jpg" alt="Écran notifications marque blanche">
    <figcaption>
      <span class="figure__legend">Écran notifications marque blanche</span>
    </figcaption>
</figure>


## La marque blanche 

### Le principe d'une nouvelle marque blanche 

Qui dit marque blanche dit **personnalisation**. Couleurs, logos, titres, ... le design des marques blanches de MyJob.Company est **configurable** pour correspondre à l’identité de chaque client. De nombreuses fonctionnalités sont activables et paramètrables par marque blanche selon l’expérience utilisateur souhaitée. MyJob.Company propose également l’intégration avec de nombreux acteurs du marché afin de mettre à disposition une interopérabilité avec les **SI** de ses clients.

À long terme, MyJob.Company a pour ambition de rendre **autonomes** les clients dans la création et la configuration de leur propre marque blanche. Actuellement, c'est aux administrateurs MJC de les créer. Ainsi, un espace admin a été conçu, permettant à MyJob.Company de créer et piloter la gestion de leurs nouveaux clients. 

Une fois son accès créé, le client est autonome sur la création et la gestion de son espace (offres d'emploi, candidats, recruteurs, coopteurs, traitement des candidatures, etc.) et le fonctionnement que l'on connaît déjà à travers MyJob.Company reste le même. 

Les contraintes étaient les suivantes :

* Cloisonnement des données entre marques blanches
* Personnalisation du design par marque blanche
* Activation et paramètrage des fonctionnalités par marque blanche (feature flags)

<figure>
    <img src="content/images/casestudies/mjc-marqueblanche/ecran-marqueblanche-mjc.jpg" alt="Déclinaison d'un écran pour plusieurs marques blanches">
    <figcaption>
      <span class="figure__legend">Déclinaison d'un écran pour plusieurs marques blanches</span>
    </figcaption>
</figure>

### La cooptation interne 

La **cooptation** est le coeur de métier de MyJob.Company. Il consiste à proposer à tout un chacun de recommander des proches, des connaissances ou des membres de son réseau professionnel pour des offres d’emploi. Les recruteurs touchent ainsi un public différent de celui des jobboards classiques. Les coopteurs (c’est comme cela que l'on appelle les utilisateurs de la plateforme) obtiennent une prime lorsque leur recommandation débouche sur une embauche.

La cooptation interne reprend ce principe mais à l’échelle d’une unique entreprise ou d’un groupe. Les coopteurs sont les employés de cette entreprise et les offres d’emploi sont des postes à pourvoir dans l’entreprise. MyJob.Company propose donc son expérience du recrutement par cooptation en marque blanche, permettant aux entreprises de mobiliser les réseaux de l’ensemble de leurs employés pour dynamiser leurs recrutements.

La cooptation interne peut être également utilisée pour favoriser la **mobilité interne**. Les employés ayant accès aux offres d’emploi de leur entreprise, ils peuvent soit eux-mêmes postuler pour changer de poste, soit recommander un collègue qui souhaite rester dans l’entreprise mais changer de poste.

Les contraintes étaient les suivantes :

* Possibilité de faire une plateforme privée, accessible uniquement aux employés de l’entreprise
* Permettre l’interfaçage avec le SI de l’entreprise (connexion ATS, import des offres, etc)

> L’équipe avec laquelle je collabore est compétente dans le conseil et le développement pur. Souvent même le résultat dépasse nos attentes ! Ils sont réactifs sur des sujets urgents, au-delà d’être de bon conseil. 
> — Tatiana
