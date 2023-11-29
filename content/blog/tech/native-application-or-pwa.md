---
title: 'Application native ou PWA ?'
date: '2023-12-01' # Au format YYYY-MM-DD
lastModified: ~ # Au format YYYY-MM-DD. Pour indiquer explicitement qu'un article a été mis à jour
description: "Dans le domaine de l'accès aux applications depuis des appareils mobiles, deux mondes s'affrontent : les applications natives et les PWA. Mais à quoi correspondent ces deux termes et comment choisir l'option qui correspondra le mieux à son besoin ? Petit tour d'horizon des caractéristiques de ces deux alternatives"
authors: [qbrouillet] # (multiple acceptés)
tableOfContent: false # `true` pour activer ou `3` pour lister les titres sur 3 niveaux.
tags: ["PWA", "application", "mobile"]
thumbnail: content/images/blog/thumbnails/native-application-or-pwa.png
#banner: images/posts/headers/arc-max-quand-l-ia-simplifie-notre-navigation-sur-le-web.jpg # Uniquement si différent de la minitature (thumbnail)
#credit: { name: 'Thomas Jarrand', url: 'https://unsplash.com/@tom32i' } # Pour créditer la photo utilisée en miniature
tweetId: '' # Ajouter l'id du Tweet après publication.
outdated: false # `true` pour marquer un article comme obsolète ou une chaîne de caractère pour un message spécifique à afficher
---

[Il y a quelques mois déjà](https://webkit.org/blog/13878/web-push-for-web-apps-on-ios-and-ipados/), Apple annonçait avec l'arrivée d'iOS 16.4 une fonctionnalité qui allait quelque peu rebattre les cartes du match : la possibilité pour les appareils de la pomme de recevoir des notifications et d'afficher les badges associés pour les applications web.
Auparavant réservée aux applications natives, cette annonce marquera une avancée dans l'expérience utilisateur que vont pouvoir proposer les PWA sur les appareils de la firme de Cupertino.

Pour y voir plus clair, voyons rapidement ce qui différencie, en l'état actuel des connaissances, les applications natives des _progressive web apps_ (PWA).  

## L'application native, taillée sur-mesure pour le mobile

Imaginons un vêtement confectionné par un tailleur : ce dernier sera parfaitement ajusté à votre taille, à l'usage que vous souhaitez en faire, car conçu spécialement pour vous.

Pour une **application native**, c'est un peu la même chose. Elle va être développée spécialement pour un type d'appareil fonctionnant avec un système d'exploitation spécifique, les principaux étant aujourd'hui _iOS_ d'_Apple_ et _Android_ poussé par _Google_.

L'utilisation de langages natifs (_Swift_, _Java_, etc.) lui permet de fonctionner de manière optimisée et d'**accéder directement aux fonctionnalités matérielles et logicielles de l'appareil**, telles que la caméra, le GPS, le Bluetooth, les capteurs de mouvement, les notifications push ou encore la biométrie.
Avant d'être mise à disposition des utilisateurs à travers les _stores_, elle est compilée, c'est-à-dire construite et encapsulée sous la forme d'un unique fichier, puis validée par ces derniers.

L'application native offre généralement des **performances plus élevées** et une **meilleure intégration** avec l'écosystème de l'appareil par rapport aux applications web ou hybrides, mais nécessite en règle générale **davantage de temps** qu'une PWA, qu'il s'agisse du développement ou de la maintenance.

!!! note "Un code source pour les gouverner toutes"
    Depuis quelques années, l'utilisation de frameworks comme [React Native](../../term/react.md) permet, à travers un même code source, de produire des applications natives à destination des différentes plateformes. Ceci permet un gain de temps considérable, et offre des performances et une intégration à l'écosystème des différents appareils proche des applications développées en langages natifs.

## La progressive Web App (PWA), une adaptation poussée de votre site web

La **PWA** peut être définie comme un site internet, optimisé pour smartphone ou tablette, que vous pouvez installer de manière à y accéder rapidement depuis l'écran d'accueil, que vous soyez connecté ou non au réseau.
La PWA va utiliser en sous-marin votre navigateur pour s'ouvrir et être parcourue. Selon le travail réalisé lors du développement, l'expérience utilisateur (ou [UX](../../term/ui-ux.md)) peut aller de la navigation sur une version responsive de votre site web, à une expérience beaucoup plus poussée et proche de celle de l'application native.

Son gros avantage : elle n'a que faire de la marque ou du système d'exploitation de votre appareil, puisqu'elle exploite les fonctionnalités des navigateurs, comme _Firefox_, _Chrome_, _Edge_, _Safari_, etc. On parle alors d'**application multiplateforme**.

Techniquement, on peut résumer la PWA à un site web, agrémenté d'une couche de configuration spécifique, notamment via la mise en place de _manifestes_, de _Service Workers_ et l'exploitation d'API de stockage local.
Depuis la sortie de la version 16.4 d'iOS courant 2023, **une PWA peut recevoir des notifications web** sur ces appareils, ce qui la rapproche encore de l'expérience offerte par les applications natives.

Il reste toutefois quelques limitations avec _Apple_, par exemple :
- l'impossibilité d'ouvrir la PWA via un _deep link_, comme un lien dans un email, qui vous redirigera vers le site et non la PWA
- le non partage de session qui ne permet pas d'être connecté automatiquement dans la PWA si vous l'étiez préalablement sur le site

Certes marginales, ces menues différences peuvent, selon votre contexte, avoir leur importance. C'est pourquoi nous accompagnons et conseillons chacun de nos clients sur la solution la plus appropriée en fonction de ses enjeux, objectifs et moyens.

[Spotify](https://open.spotify.com), [X](https://twitter.com), [Pinterest](https://www.pinterest.fr/) ou encore le média [l'Équipe](https://www.lequipe.fr/) proposent des PWA depuis plusieurs années comme alternative aux applications natives. 

## Comment choisir ?

### Quand s'orienter vers une PWA ?

- **Délai court de mise sur le marché** : Pour lancer rapidement un produit sans les délais des cycles d'approbation des app stores.
- **Mises à jour fréquentes** : Si l'application nécessite des mises à jour régulières ou instantanées, une PWA est plus adaptée car elle se met à jour automatiquement à chaque visite.
Toutefois, cet argument peut être contrebalancé par l'utilisation de certains outils comme _React Native_ et _Expo_ qui permettent des mises à jour "à la volée" (_Over the Air updates_). Nous reviendrons sur cette approche, qui comporte ses avantages et ses limites, dans un prochain article. 
- **Audience large et diversifiée** : Pour les utilisateurs sur des appareils variés et avec des systèmes d'exploitation différents, une PWA offre une meilleure compatibilité sans le besoin de versions multiples.
- **SEO et accessibilité en ligne** : Si l'objectif est d'optimiser la présence en ligne grâce aux moteurs de recherche, une PWA est indexable et peut être avantageuse pour le SEO.
- **Budget limité** : Souvent moins coûteuse à développer et à maintenir qu'une application mobile native (dès lors qu'un site web est existant et se prête à une expérience mobile), la PWA offre un avantage certain lorsque les moyens pour le développement et la maintenance sont restreints.
- **Pas de nécessité de fonctionnalités avancées du périphérique** : Quand l'application n'a pas besoin d'un accès complet aux fonctionnalités hardware avancées (connectivité, partage spécifique, biométrie, etc.).

### Quand s'orienter vers une application native ?

- **Expérience utilisateur optimale** : Si l'application nécessite des animations complexes, une expérience de navigation avancée et une performance optimale, une application mobile native est souvent plus adaptée.
- **Accès aux fonctionnalités natives** : Quand l'application doit utiliser pleinement les capteurs et fonctionnalités du périphérique, comme la caméra, les données biométriques, l'interaction avec d'autres applications, etc.
- **Besoin d'une présence sur les app stores** : Pour bénéficier de la visibilité et de la confiance que représentent les stores d'applications.
- **Sécurité renforcée** : Si l'application gère des transactions sensibles ou des données critiques, les applications mobiles natives peuvent offrir des niveaux de sécurité plus élevés grâce aux protocoles des App Stores et aux fonctionnalités natives des appareils.
- **Notifications push plus robustes** : Pour intégrer un système de notifications push complexe et hautement personnalisable, les applications natives ont l'avantage.
- **Utilisation hors ligne avancée** : Les applications natives offrent une meilleure gestion des données hors ligne et du stockage local.

## En conclusion

Même si le choix entre ces deux approches ne peut se faire uniquement à la lecture de cet article, il est possible avec ces éléments de dégrossir le tableau selon son projet, son contexte et ses moyens.

**Si vous souhaitez obtenir davantage d'informations et de conseils**, n'hésitez pas à [nous contacter](./contact) et à [consulter les pages dédiées de notre site](./nos-services/application-web-et-mobile). Nous nous ferons une joie de partager notre expérience et d'avancer avec vous sur votre projet.
