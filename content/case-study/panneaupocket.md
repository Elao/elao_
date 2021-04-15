---
title: "PanneauPocket"
lastModified: "2021-04-12"
date: "2021-04-12"

# Params
metaDescription: "PanneauPocket - informer et alerter les habitants partout en France."
description: "PanneauPocket est une solution mobile et web permettant aux mairies et acteurs de la vie publique de communiquer des informations essentielles et des alertes aux habitants."
websiteUrl: https://www.panneaupocket.com/
shortDescription: "Les panneaux municipaux dans la poche"
clients: PanneauPocket
size: 3 mois
services: ["Accompagnement", "Développement"]
technologies: ["symfony", "react-native"]
members: ["aldeboissieu", "tjarrand", "adefrance", "cmeiller", "xgorse"]
images: ["images/casestudies/headers/panneaupocket-banner.jpg"]
---

## Le contexte du projet

**N°1 dans son domaine en France, PanneauPocket a pour mission de fluidifier la communication entre les municipalités, les intercommunalités, et les habitants de leurs territoires. La solution a été également choisie pour équiper toutes les forces de Gendarmerie partout en France.** 

L'intérêt réside dans le système de notification push qui permet à chacun d'être informé et alerté, encore plus pertinent dans une période où il est nécessaire de rester chez soi. Une coupure de courant ou de l'eau ? Une fête de village ? Des informations sur la situation sanitaire ? Les habitants ayant téléchargé l'application sont notifiés dès que la mairie le souhaite.

En plus d'une application mobile développée avec React Native, PanneauPocket s'est dotée d'une application web lui permettant d'être encore plus accessible.

**PanneauPocket a confié à Elao le développement de son application mobile à destination des habitants, de son application de back-office à destination des municipalités et de son application web.**

Un projet dont l'utilité n'est plus à démontrer : les volumes de notifications envoyées (plus de 260 000 en 2020), les métriques des visites quotidiennes et panneaux créés ont obligé PanneauPocket à muscler encore plus leur infrastructure avec [Rix](https://rix.fr). 

## L'expertise Elao déployée pour les applications PanneauPocket

### Ateliers de recueil du besoin
Le fondateur de PanneauPocket a fait appel à Elao pour l'aider à re-structurer une seconde version de l'application en mode MVP. Il fallait alors trouver le bon équilibre entre l'offre (les collectivités) et la demande (les habitants) et développer le fonctionnel nécessaire à chacun des groupes d'utilisateurs pour répondre à leurs besoins dès la première version. Les développeurs Elao ont fait évoluer le back-office et l'application de la première version de l'application main dans la main avec PanneauPocket.

### Phase de build (développement)
Thomas, Anne-Laure et Christophe ont mené de front toute la phase de développement, **accompagnant l'équipe de PanneauPocket dans la rédaction des spécifications fonctionnelles** pour anticiper tous les cas nominaux du projet. **Ils ont ensuite posé les bases techniques, développé chaque fonctionnalité, réalisé les tests automatisés et la recette fonctionnelle.** La mise en production de la première version a permis à PanneauPocket d’avoir un socle solide pour toutes ses futures évolutions fonctionnelles..

### Phase de run (évolutions fonctionnelles et maintenance applicative)
Depuis la mise en production de ses applications, PanneauPocket fait encore appel de manière très régulière à Elao pour les évolutions de ses applications, qui ont chacune une feuille de route très fournie. Par ailleurs, PanneauPocket a ouvert son application non plus seulement aux mairies mais également aux gendarmeries et aux communautés de communes, ce qui représente de nouveaux challenges fonctionnels.

## Les applications

### Pour les collectivités clientes et les gendarmeries : une interface de gestion des panneaux et un widget à intégrer

PanneauPocket a depuis le début une volonté réelle de simplification de ses interfaces, tout en proposant un maximum de fonctionnalités. Un défi à relever à chaque nouvelle page !

**Quelques fonctionnalités clés**

* Des types de panneaux différents (alertes et informations) ;
* Un système d'envoi et de fréquence de notification personnalisés par panneau ;
* L'ajout de contenu riche pour répondre aux besoins exprimés par les créateurs de panneaux : couleurs, taille, emojis, images, PDF, etc. ;
* L'affichage de statistiques pertinentes (nombre de mises en favoris, d'affichages des panneaux, etc.).

Pour répondre à ces besoins et aux spécifications fonctionnelles du produit PanneauPocket, l'équipe technique d'Elao a réalisé les applications web avec Symfony et l'intégration a été réalisée avec Bootstrap dans une logique de MVP.

<figure>
    <img src="images/casestudies/panneaupocket-adminmairie.png" alt="Application de gestion de panneaux d'informations">
    <figcaption>
      <span class="figure__legend">L'interface de gestion de panneaux côté client</span>
    </figcaption>
</figure>

Pour compléter les services rendus aux mairies, Elao a développé un widget en JS pouvant être intégré dans n'importe quelle page web. Le widget reprend la charte des panneaux de l'application mobile et permet à tout client de communiquer depuis un support web. Les mairies, par exemple, l'ajoutent sur le site internet de leur commune, augmentant encore l'accessibilité de l'information sans plus d'effort de leur part.

### Pour les citoyens : une application mobile et une application web de visualisation des panneaux

#### L'application mobile
Le cœur de PanneauPocket réside dans l'application qui va permettre aux mairies de communiquer le plus aisément possibles des informations clés à ses habitants. 

Par volonté d'être le plus simple possible à utiliser, l'utilisateur cherche sa mairie grâce à un champ de recherche et peut l'ajouter en favori. Il sera alors notifié de tous les nouveaux panneaux publiés par la mairie. 
Le partage de panneaux étant aussi un élément central de visibilité des informations, PanneauPocket a souhaité l'intégrer dès les premières versions de son application. 

<figure>
    <img src="images/casestudies/panneaupocket-mobile.png" alt="Application mobile de visualisation de panneaux">
    <figcaption>
      <span class="figure__legend">L'application mobile</span>
    </figcaption>
</figure>

L'application mobile a été développée en JS avec ReactNative et est donc accessible sur iOS et Android sans coût de développement supplémentaire.

#### L'application web

Parmi les évolutions récentes, PanneauPocket a souhaité proposer un site internet avec la possibilité pour chacun de visualiser et partager tous les panneaux disponibles, triés par commune.

**L'application permettait de répondre à deux besoins**

* Côté mairie : permettre l'accessibilité des panneaux sans nécessite le téléchargement d'une application ;
* Côté utilisateur : accéder et pouvoir partager des panneaux sans se demander si la personne qui les reçoit a téléchargé l'application ;
* Côté PanneauPocket : accentuer la visibilité des panneaux et communes clientes pour son référencement naturel.


<figure>
    <img src="images/casestudies/panneaupocket-web.png" alt="Application web de visualisation de panneaux">
    <figcaption>
      <span class="figure__legend">L'application web</span>
    </figcaption>
</figure>


### Pour l'équipe PanneauPocket : une interface d'administration des clients

L'équipe PanneauPocket ayant un besoin quotidien d'administration (ajout / modification de clients, gestion des droits des utilisateurs), Elao a développé une interface de gestion sur-mesure, adaptée à leurs besoins métier afin d'être plus efficaces au quotidien. 


<figure>
    <img src="images/casestudies/panneaupocket-admin.png" alt="Interface d'administration">
    <figcaption>
      <span class="figure__legend">L'interface d'administration de PanneauPocket</span>
    </figcaption>
</figure>
