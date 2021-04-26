---
type:               "post"
title:              "Sauvez un cookie 🍪, installez Matomo !"
date:               "2019-03-21"
publishdate:        "2019-03-21"
tableOfContent:     2
draft:              false

description:        "Chez Elao, nous mesurons désormais l'audience de nos propres sites grâce à Matomo. Retour d'expérience. "

thumbnail:          "images/posts/thumbnails/matomo.jpg"
header_img:         "images/posts/headers/matomo.jpg"
tags:               ["Seo", "RGPD", "Matomo"]
categories:         ["Dev", "Web"]

author:    "aldeboissieu"
---

Anciennement nommé Piwik, Matomo est une solution de mesure d'audience de sites web, alternative libre à Google Analytics. Chez Elao, nous avons souhaité l'utiliser définitivement pour analyser le trafic de notre site et de notre blog. Nous partageons avec vous quelques informations qui vous seront peut-être utiles si, vous aussi, **vous souhaitez rendre anonymes les informations relatives à vos visiteurs** 🔒.

## A quoi sert Matomo ?

Tout comme Google Analytics, Matomo permet de mieux comprendre la façon dont les utilisateurs arrivent sur nos sites. On peut ainsi envisager de répondre aux questions suivantes : d'où vient mon visiteur (réseaux sociaux ? Moteur de recherche ?) ? Quelles pages a-t-il visité ? Quelle a été la durée moyenne de sa visite ? Quelle est ma place dans l'univers ? Et autant d'autres questions qui permettent de **connaître le parcours de ses visiteurs**, afin d'améliorer l'ergonomie ou encore de remonter un éventuel trafic parasite.

- **Matomo est un logiciel libre** sous licence GPLv3, conçu pour être auto-hébergé;
- Toutes les données collectées sont stockées sur votre serveur et sont soumises à votre contrôle. Ainsi, **les informations de suivi de chaque visiteur ne sont pas partagées avec des tiers** contrairement à Google Analytics;
- Matomo peut être utilisé pour tous vos sites web, extranet et intranet.

![Matomo](images/posts/2019/matomo/logo-matomo.jpg)

## De quoi aurez-vous besoin pour installer Matomo ?

Il est possible d'installer un serveur spécialement pour Matomo et d'y configurer plusieurs sites, de la même façon que fonctionne le multi-compte de Google Analytics.

Les minima requis pour installer Matomo sont :

- Un serveur web (Apache, Nginx, etc.);
- PHP en version 5.5.9 ou plus;
- Une base de données : MySQL ou MariaDB;
- Les extensions PHP pdo.

Faites vos choix :

- installation [par archive](https://matomo.org/docs/installation/);
- installation [par package](https://debian.matomo.org/) : ```apt-get install matomo```;
- installation par [docker](<https://docs.docker.com/samples/library/matomo/>);
- solution [hébergée par matomo](<https://matomo.org/pricing/>).

Matomo indique quelques conseils pour [optimiser son infrastructure](https://matomo.org/docs/optimize/) pour les sites à très fort trafic / volumétrie. Bref, cette solution semble s'appliquer à toutes sortes de besoins.

![Installation de Matomo](images/posts/2019/matomo/install-matomo.png)



## Comment l'utiliser sur vos sites web ?

**Matomo fonctionne grâce à un marqueur JavaScript à insérer dans le header de votre site web**. Celui-ci vous est communiqué au moment de la création d'un compte pour un nouveau site. Tout comme le marqueur de Google Analytics, celui-ci est à insérer avant la fermeture de la balise ```</head>```.



## Conformité avec la RGPD

Les récents renforcements de la protection des données ne vous ont probablement pas échappé. Ainsi, dans sa documentation ["Solutions pour les cookies de mesure d'audience"](https://www.cnil.fr/fr/solutions-pour-les-cookies-de-mesure-daudience), **la CNIL détaille les obligations légales à mettre en place en matière de cookies**, dès lors qu'il y a données collectées liées à une visite. Miracle 🙌 ! **Utiliser un outil d'analyse d'audience tel que Matomo ou AT Internet (Xiti) permet de bénéficier de l'exemption de la demande de consentement avant de déposer un cookie**, permettant ainsi à nos visiteurs d'économiser un clic, ce qui est bon à prendre en ces temps de frénésie de bandeaux / pop-ups.

Les principaux points relatifs cités par la CNIL auxquels il faut être attentif et qui peuvent être paramétrés sont les suivants :

- **Les deux derniers octets de l’adresse IP recueillie doivent être supprimés,** au minimum, afin de s'arrêter à la seule localisation de la ville de l'internaute;
- Les cookies permettant la traçabilité des internautes et les adresses IP **ne doivent pas être conservées au-delà de 13 mois à compter de la première visite** ;
- **Les données de fréquentation brutes associant un identifiant** ne doivent pas non plus être conservées plus de 13 mois.
- **Mettre en place une solution d'Opt-out** pour permettre aux utilisateurs de s’opposer au dépôt de cookies. Pour cela il suffit d’insérer par exemple dans une page "Politique de confidentialité", l'iframe qui est fournie dans les paramètres Vie privée / Désinscription des utilisateurs.

<img src="https://media.giphy.com/media/wO9EzKpgf3pao/giphy.gif" />

L'interface de Matomo permet de facilement configurer ces choix, puisqu'un menu est dédié à la vie privée, mêlant à la fois documentation et réglages. A noter que ces réglages se font au niveau de l'instance, donc **ces choix s'appliqueront sur tous les sites configurés**. Il n'est pour l'instant pas possible de les régler site par site.

Les réglages possibles concernent :

- **L'anonymisation des données** de suivi :
  - Nombre d'octets de l'adresse IP masqués, de 1 à 3;
  - Masquage des adresses IP;
  - Remplacement de l'identifiant utilisateur par un pseudonyme.
- **Suppression régulière des données stockées en base** (cf le troisième point cité ci-dessus, exigé par la CNIL), en indiquant le nombre de jours. Attention, ces infos sont utilisées par Matomo pour nourrir les rapports à propos des top keywords ou top pages. Ainsi, il faut bien penser à activer [l'auto-archivage des rapports](https://matomo.org/docs/setup-auto-archiving/);
- Purges régulières de la base des données;
- **Anonymisation des données trackées qui ne l'étaient pas** encore, dès lors qu'elles n'ont plus d'intérêt à être conservées.

⚠️ : le [guide de conformité de Piwik (ancien nom de Matomo) proposé par la CNIL](https://www.cnil.fr/sites/default/files/typo/document/Configuration_piwik.pdf) ne semble plus à jour, puisqu'il pointe une modification du tag de tracking permettant d'indiquer une durée de timeout du cookie à 13 mois, alors que cette durée est désormais paramétrée par défaut, comme nous l'indique la [documentation de Matomo](https://developer.matomo.org/api-reference/tracking-javascript) (voir au paragraphe "Configuration of Tracking Cookies", pour la méthode ```setVisitorCookieTimeout```.



## Les fonctionnalités proposées par Matomo vs Google Analytics

Pour les habitués de Google Analytics, le passage à une nouvelle ergonomie n'est pas évidente. En comparant deux périodes sur l'année, nous avons déjà pu nous assurer qu'aucun visiteur n'est laissé au bord de la route : les données semblent cohérentes.

![Evolution de traffic](images/posts/2019/matomo/Evolution-trafic-matomo.png)

On retrouve les fondamentaux :

- **Données relatives à l'audience,** c'est à dire toutes les infos qui concernent les visiteurs (provenances géographiques, logiciels utilisés);
- **La carte en temps réel des visites**;
- **Les types d'acquisition** : il s'agit de l'ensemble de provenance des visites (réseaux sociaux, campagnes, moteurs de recherche, etc.);
- **Les informations concernant le comportement des visiteurs** : les pages d'entrées, de sorties, etc.
- **La mise en place d'objectifs** et les informations qui en résultent.

![Canaux d'acquisition](images/posts/2019/matomo/canaux-matomo.png)

On peut noter quelques fonctionnalités remarquables de Matomo  :

- **Matomo permet un accès facilité aux différents logs** : par exemple, on peut accéder via l'interface à la liste des visites avec toutes les informations collectées (localisation, OS, action...);
- Le fameux **flux de comportements des visiteurs**, bien connu des utilisateurs de Google Analytics, est également disponible;
- Pour obtenir les entonnoirs de conversion des visiteurs vers les objectifs et étudier les fuites, il faudra souscrire à la feature *Funnels* (ou bien établir son propre entonnoir de conversion personnalisé);
- Il est possible de **paramétrer ses écrans d'accueil en créant ses tableaux de bord personnalisés** et des gadgets;
- Les nouveaux segments se paramètrent en composant ses propres règles par expression;
- Un accès rapide permet d'accéder à la liste de **tous les liens sortants cliqués sur la période** (clic uniques et au total).

Pour les personnes qui ont l'habitude de faire des campagnes Google Ads, il est possible de créer ses propres campagnes pour les suivre.

A noter :

- Matomo propose depuis peu un [Tag Manager](https://matomo.org/docs/tag-manager/), qui permet de mettre en place un plan de marquage, d'ajouter des scripts de tracking ou encore des liens d'affiliation, par exemple, depuis la même interface.

![Fonctionnalités de Matomo](images/posts/2019/matomo/features-analytics.png)

## Fonctionnalités payantes

Utiliser les outils Google habitue à la "gratuité", mais n'oublions pas que Google tire profit des données qu'il stocke pour nous, relatives à la fréquentation de nos sites. Matomo se rémunère grâce aux dons et aux fonctionnalités vendues à ses utilisateurs.

[Des fonctionnalités supplémentaires pour les sites e-commerce](<https://matomo.org/docs/ecommerce-analytics/>), que nous n'avons pas eu l'occasion de tester pour l'instant, existent et proposent des features telles que l'analyse du panier, des produits cliqués, etc.);

Un panel de fonctionnalités payantes couvre un scope intéressant, comme par exemple la possibilité de faire de l'A/B testing, les Heat Maps ou encore les conversions multi-channels (pour une attribution plus fine des ventes selon les visites).

## Pour conclure

Certes, difficile de ne pas être décontenancé par l'ergonomie de l'outil quand on est habitué à plusieurs années d'utilisations de la suite Google. Pour autant, Matomo n'est pas à la traîne, car on retrouve les principales metrics et la plupart des possibilités permettant d'information ses metrics sont bien présentes. Couplé à un outil d'analyse de logs (d'ailleurs, Matomo propose une solution), rien ne pourra vous échapper. Mais surtout, garantir à nos visiteurs le respect de leur vie privée est totalement séducteur 🥰.

## En savoir plus / Sources

- [Matomo](https://matomo.org/)
- [Démo publique](https://demo.matomo.org/)
- [Comprendre et analyser les données avec Matomo](https://zestedesavoir.com/tutoriels/2508/matomo-analytics/partie-4-comprendre-et-analyser-les-donnees/)

Merci à [Sébastien Monnier](https://woptimo.com/) pour son feedback sur l'article :)
