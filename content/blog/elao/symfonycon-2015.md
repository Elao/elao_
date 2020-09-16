---
type:               "post"
title:              "Retour sur la SymfonyCon 2015"
date:               "2015-12-04"
publishdate:        "2015-12-17"
draft:              false

description:        "Nous étions à la SymfonyCon 2015 pour fêter les 10 ans de Symfony"

thumbnail:          "/images/posts/thumbnails/symfonycon-2015.jpg"
header_img:         "/images/posts/headers/foliesbergeres.jpg"
tags:               ["Conférence", "Symfony", "SymfonyCon"]
categories:         ["conference"]

author:    "rhanna"
---

## Symfony a 10 ans

Symfony a fêté ses 10 ans pendant deux jours dans un lieu très spécial, les Folies Bergères à Paris. Et nous avions la
joie d'y être !

### Keynote d'ouverture

La keynote d'ouverture animée par Fabien Potencier a mis en lumière les visages des acteurs clés ayant contribué à la
réussite de ce framework : les dévelopeurs, les ambassadeurs et les contributeurs à sa documentation.

À retenir :

* Le passage de Symfony de la version 1 à la 2 a pris 4 ans. De la version 2 à la 3, également 4 ans. Symfony 4 est
prévue plus tôt, dans deux ans, soit en 2017;
* La certification Symfony précédemment liée à la version 2.3 est maintenant disponible pour la version 3.0;
* L'un des chantiers pour le futur de Symfony est de rendre le *filesystem*, *read-only*, ce que signifie que la
gestion du cache devra être revue.

### Dig in Security with Symfony by Sarah Khalil

Plongeon au coeur du composant *Security* de Symfony. Ce qu'il faut retenir c'est qu'il ne faut plus utiliser les
[ACL](http://symfony.com/doc/current/cookbook/security/acl.html)
mais les [Voters](http://symfony.com/doc/current/cookbook/security/voters.html).
Rien de nouveau donc, dommage que cela soit une redite de la documentation.

[Les slides](https://speakerdeck.com/saro0h/symfonycon-paris-dig-in-security)

### The cloud is the future, and your architecture isn't ready by David Zuelke

Retour d'expérience d'Heroku sur les bonnes pratiques pour rendre une application éligible au Cloud.

À retenir, en vrac :

* Ne pas mettre la configuration de vos environnments (dev, prod...) dans votre code mais directement sur la plateforme.
* Utiliser  *getenv($var)* dans le code pour récupérer une variable d'environnement.

Pour en savoir plus sur l'ensemble de ces bonnes pratiques, un site a été créé : [12factor](http://12factor.net/).

### Building high profile webapps with Symfony and API Platform by Kévin Dunglas

Retour d'expérience sur un projet pour beIN SPORTS : l'implémentation d'une plateforme API et de multiples consommateurs
de cette API de natures différentes : backoffice en Symfony, site web, applications mobiles, Xbox.

À retenir, en vrac :

* Toute la logique métier est gérée par l'API.
* Les consommateurs de l'API ne gèrent que la présentation des informations.
* La plateforme API est sous Symfony ; les consommateurs côté *frontend* ne génèrent pas de route en PHP ou Symfony,
ils se calquent sur l'URI de l'API.

J'ai beaucoup aimé ce retour d'expérience même si l'on peut regretter le manque de spontanéité de la présentation.

[Les slides](https://speakerdeck.com/dunglas/a-high-profile-project-with-symfony-and-api-platform-bein-sports)

### Symfony routing under the hood by David Buchmann

Idem que pour le composant Security, un plongeon en détail dans le composant *Routing*, son fonctionnement et comment
l'optimiser.
Un tour d'horizon de l'éco-système de bundles permettant de l'étendre (gestion d'URL traduites par exemple avec
[JMSI18nRoutingBundle](https://github.com/schmittjoh/JMSI18nRoutingBundle)).

Quelques astuces à retenir :

* L'ordre de déclaration des routes est important. Pour gagner en performance, déclarer les routes les plus utilisées
en premier. Cela permet au moteur Symfony de correspondance entre URL et routes de ne pas parcourir beaucoup de routes
avant de trouver la bonne.
* Plus la route est restrictive, plus il faut l'avoir haut dans la liste des routes.
* Eviter les expressions régulières lorsque c'est possible.

[Les slides](http://davidbu.ch/slides/2015-12-03-symfony-routing.html)

### Doctrine 2: To Use or Not to Use by Benjamin Eberlei

Benjamin Eberlei, qui participe au développement de l'ORM Doctrine a expliqué dans quels cas utiliser Doctrine et dans
quels cas il ne faut pas.

La frustration des développeurs par rapport à un ORM, notamment le temps perdu à trouver comment gérer un cas complexe
résulte du fait que les développeurs se trompent sur l'objectif de l'ORM.

Par exemple, Doctrine n'est pas fait pour des requêtes complexes, pour des écritures en lot ou bien pour stocker des
statistiques et réaliser des analyses de *logs*. Eviter également l'héritage de tables et les évènements Doctrine. En
effet, il ne faut pas coupler votre domaine métier et un ORM. Doctrine est lent lors d'utilisation de *DQL*,
*fetch joins* et *pagination*. Il vaut mieux utiliser les
[Value Objects](http://doctrine-orm.readthedocs.org/projects/doctrine-orm/en/latest/tutorials/embeddables.html)
et construire un
[Data Transfer Objects (DTOs)](http://doctrine-orm.readthedocs.org/projects/doctrine-orm/en/latest/reference/dql-doctrine-query-language.html#new-operator-syntax).

J'ai beaucoup apprécié la franchise de Benjamin. La présentation était sobre et pédagogique, un modèle du genre.

[Les slides](https://qafoo.com/talks/15_09_symfony_live_london_doctrine2_to_use_or_not_to_use.pdf)

### A Journey Down the Open Road by Yoav Kutner

Le fondateur de Magento puis de la suite Oro (OroCRM, OroPlatform, OroCommerce...) revient sur dix ans d'entreprenariat
dans la création d'outils Open Source. La présentation rondement menée a été finalement écourtée faute de temps, dommage
c'était passionnant !

### Guard Authentication: Powerful, Beautiful Security by Ryan Weaver

[*Guard*](http://symfony.com/blog/new-in-symfony-2-8-guard-authentication-component) disponible
avec Symfony 2.8/3.0 est un nouveau composant de sécurité permettant la mise en place d'un mécanisme d'authentification
facilement personnalisable.
Contrairement à ce qui se faisait avant avec le composant *Security*, la configuration *yaml* est allégée et la logique
est davantage dans une classe PHP.
Ryan Weaver, pédagogue et speaker de qualité, nous donne vraiment envie de vite essayer ce composant.

[Les slides](http://www.slideshare.net/weaverryan/guard-authentication-powerful-beautiful-security)

### How Symfony 3.0 moves forward without letting anyone behind by Nicolas Grekas

Le passage de Symfony 1 à la version 2 a été très douloureuse pour les développeurs ayant très tôt adopté ce
framework.
La version 2 était une refonte complète du framework et cassait donc toute compatibilité avec la version 1.

Au contraire, le passage de la version 2 à la version 3 de Symfony se fait plus en douceur pour plusieurs raisons :

* Pas de refonte complète du cœur de Symfony.
* Les versions 3 et 2.8 sont identiques à la différence que la 2.8 contient la rétro-compatibilité avec les versions
antérieurs. La version 3 n'est donc que la 2.8 allégée des fonctions ou composants dépréciés.
* Des outils d'analyses statiques de code ont été créés pour aider les développeurs à détecter les dépréciations afin
de les corriger :
    * [Deprecation detector](https://github.com/sensiolabs-de/deprecation-detector)
    * [Symfony Upgrade Fixer](https://github.com/umpirsky/Symfony-Upgrade-Fixer)

[Les slides](https://speakerdeck.com/nicolasgrekas/how-symfony-3-dot-0-moves-forward-without-letting-anyone-behind)

### Matters of State by Kris Wallsmith

L'idée, à l'état de concept, de Kriss Wallsmith est de mettre en place des évènements sur le modèle métier ou sur certains
attributs du modèle en s'inspirant de ce qui se fait en React.
Cela ressemble fortement également aux évènements Doctrine mais avec une granularité plus fine et un couplage à l'ORM
moins forte. A suivre, donc.

[Les slides](http://fr.slideshare.net/kriswallsmith/matters-of-state-55843873)

### New Symfony Tips and Tricks by Javier Eguiluz

Javier est déjà connu pour animer le blog officiel de Symfony et pour distiller ses bonnes astuces. Il vient nous présenter
une nouvelle très bonne fournée d'astuces en vrac principalement focalisées sur les nouveautés de Symfony 3 et Twig 2.

[Les slides à savourer ici](http://www.slideshare.net/javier.eguiluz/new-symfony-tips-tricks-symfonycon-paris-2015).

### "Perfect" caching with FOSHttpCache by Andre Rømcke

Nous avions déjà assisté à cette présentation au Symfony Live 2015.
Il s'agit d'une présentation des différentes manières de cacher du contenu utilisateur.

Le cache dans le contexte user a toujours semblé impossible. Or, "l'User context Hash" est possible :
* transparent : *reverse proxy*
* empreinte pour chaque utilisateur

Sous forme d'une pièce de théâtre, les *speakers* ont illustré un *use case* :

* accès à un résumé d'article (utilisateur anonyme)
* ou à l'article complet (utilisateur authentifié).

Ressources :
* [Voir la doc de FOSHttpCacheBundle](https://github.com/FriendsOfSymfony/FOSHttpCacheBundle)
* et plus spécifiquement [la doc sur le User Context](https://github.com/FriendsOfSymfony/FOSHttpCacheBundle/blob/master/Resources/doc/features/user-context.rst)

### New Symfony3 Form component by Bernhard Schussek

Bernhard Schussek est celui à qui nous devons le composant Form de Symfony. Il revient dans cette présentation sur les
changements dans Symfony 3. Cela ne va pas faire que des heureux ! En vrac :

* Plus de nom de Type, utiliser la syntaxe : `TextType::class` au lieu de `'text'`
* Ne pas utiliser les *Form theme*, mais coder les champs du formulaire en HTML (étonnant ?)

[Les slides](https://speakerdeck.com/webmozart/symfony2-forms-dos-and-donts)

### Behind the Scenes of Maintaining an Open Source Project by Jordi Boggiano

Jordi Boggiano a créé et maintient Composer. Cet outil de gestion des dépendances dédié à PHP a très vite été adopté
par les développeurs et l'écosystème PHP. Symfony a sans doute également contribué à populariser Composer.
Moins qu'une présentation technique, Jordi a fait un retour d'expérience sur plusieurs années de maintenance d'un
 projet Open Source comme Composer :

* la fierté d'avoir développer un outil adopté par beaucoup de développeurs
* les super retours des développeurs

Mais aussi les mauvais côtés :

* les trolls (sur GitHub, sur Twitter)
* les contributeurs fous qui font des Pull Request sur le dépôt GitHub de Composer contenant une refonte complète
du code ou la modification du Coding Styles en "Airbnb Code Style";
* le burnout du contributeur open source;

[Les slides](http://slides.seld.be/?file=2015-12-04+Behind+the+Scenes+of+Maintaining+an+Open+Source+Project.html)

### Comparing Symfony2 perfs in PHP7 migration by Julien Pauli

Julien Pauli nous amène dans les profondeurs du fonctionnement interne de PHP7 pour nous expliquer l'augmentation
notable de performance de PHP7.

Ce qu'il faut retenir :

* La compilation de PHP7 est plus gourmande que PHP5, ce qui n'a pas d'importance au final avec le cache d'opcode
indispensable sur des frameworks comme Symfony.
* Beaucoup de travail fait dans le cœur de PHP pour exploiter au mieux les caches L1 & L2 des CPU, ce qui explique la
plus faible consommation mémoire et la rapidité de ce dernier.
* Ces changements internes ont eu beaucoup d'impact sur les extensions de PHP qui ont dû être adaptées.

### Symfony: Your next Microframework by Ryan Weaver

Ryan nous montre comment on peut exploiter la full stack symfony dans un seul fichier, comme le fait Silex, puis faire evoluer la structures des fichiers au fur et à mesure que l'on rajoute des composants. Cette approche à le merite d'etre très didactique en nous montrant le roles de chaque brique.

Ryan nous montre comment on peut exploiter la full stack Symfony dans un seul fichier, comme le fait Silex. Puis faire
évoluer la structure des fichiers au fur et à mesure que l'on rajoute des composants. Cette approche a le mérite d'être
très didactique en nous montrant le rôle de chaque brique.

[Les slides](http://www.slideshare.net/weaverryan/symfony-your-next-microframework-symfonycon-2015)

## Conclusion

Ce SymfonyCon à Paris pour fêter dignement les 10 ans de Symfony a permis de réunir des développeurs du monde entier :
Croatie, Suisse, Espagne, Etats-Unis, Bangladesh...

Cependant on est resté sur notre faim quant aux conférences et comme la critique doit être constructive, voici ce que
nous souhaiterions voir être amélioré :

* Avoir moins de conférences mais de qualité. On aurait pu se passer par exemple des
présentations classiques sur les composants, des informations que l'on trouve dans la documentation.
* Avoir les conférences sur un seul track ; certaines conférences plus intéressantes ont été données dans le théâtre
 voisin. Idem, des conférences de qualité se sont déroulées en même temps dans les deux théâtres.
* Les *speakers* étaient trop seuls sur scène. Le staff aurait pu aider le conférencier notamment pour la gestion des
questions en fin de présentation.
* Travailler le rythme des conférences, le contenu de certaines semblait très intéressant mais la présentation
soporifique n'a pas permis de faire passer le message. Dommage !

Le pitch et d'autres avis sur les conférences sont disponibles sur
[la page évènement SymfonyCon Paris 2015](https://joind.in/event/view/4063) sur Joind.in.

La prochaine SymfonyCon est annoncée à Berlin.
