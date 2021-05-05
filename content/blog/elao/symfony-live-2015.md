---
type:               "post"
title:              "Retour sur le Symfony Live 2015"
date:               "2015-04-10"
lastModified:       ~

description:        "La septième édition du SymfonyLive Paris s'est tenue le jeudi 9 avril à la Cité Internationale Universitaire de Paris. Retour sur cet évènement."

thumbnail:          "images/posts/thumbnails/SymfonyLive.png"
banner:             "images/posts/headers/php_forum_team_elao_2.jpg"
tags:               ["Conférence", "Symfony Live", "Conférence"]
categories:         ["conference", "Symfony Live"]

author:    "rhanna"
---

La septième édition du SymfonyLive Paris s'est tenue le jeudi 9 avril à la Cité Internationale Universitaire de Paris. Retour sur cet évènement.

## 10 ans déjà… quid de Symfony 3.0 ? par Fabien Potencier

Tout en décontraction, Fabien Potencier nous avoue qu'il n'est pas très "tech" ni très connecté. Il a découvert Whatsapp il y a 6 mois grâce à sa… mère.

Petite anecdote, le premier site développé avec Symfony était un site marchand de lingerie. Le client n'a pas payé et c'est pourquoi il a été décidé de livrer Symfony à la communauté Open Source. À la base, le framework s'appelait "Sensio Framework" et le "f" de Symfony vient de "Framework".

Fabien nous parle de la responsabilité du développement de Symfony vis à vis de ses utilisateurs, les versions LTS, la roadmap… Le passage de la version 1.4 à la version 2 a été douloureuse mais nécessaire. Symfony 3 ne sera pas une révolution mais une évolution. Exemple avec le composant Asset qui a été recodé complètement. L'ancien et le nouveau cohabitent sur la version 2.7 pour laisser le temps aux applications de migrer. C'est la philosophie actuelle du développement de Symfony : préserver la rétro-compatibilité. La version 3 sera une version réduite des composants dépréciés. Symfony 3 est en réalité déjà prête car toutes les fonctionnalités sont dans la 2.7. Il ne reste donc plus qu'à "nettoyer" les composants dépréciés. Il y aura une version 2.8 qui sortira en même temps que la 3.0 et qui contiendra les hotfix et refactoring réalisés sur la version 3. La 2.8 sera également LTS.

Fabien nous avoue que ce qu'il voulait faire avec le micro-framework Silex, Laravel l'a fait, et a bien largement trouvé sa communauté. Is Silex dead?

Fabien se lâche. Il trouve que PSR-7 créé par le PHP-FIG est une… connerie ! Son souhait est de faire non pas une interface d'interopérabilité mais une implémentation commune.

Il nous annonce que pour les 10 ans de Symfony, la SymfonyCon à Paris aura lieu les 3 et 4 décembre… aux Folies Bergères.

La certification Symfony proposée par SensioLabs reste en Sf2.3 et passera directement à la 3.0.

Y aura t-il un ORM Symfony en parallèle de Doctrine ou Propel ? Réponse catégorique : non ! Aucun intérêt de réinventer la roue.

Un dernier conseil de Fabien : toujours se poser la question sur l'utilisation d'un bundle open source : m'apporte t-il de la valeur ? Répond t-il exactement à mon besoin ? N'est ce pas plus simple d'en créer un spécifique à mon besoin ?


## Développer avec le SyliusResourceBundle par Arnaud Langlade

Au cours de son développement Sylius, l'équipe s’est rendu compte qu’elle dupliquait énormément de code pour gérer ses CRUDs. Ne voulant pas réinventer Symfony ou utiliser un admin generator, elle décida de créer un bundle simple et flexible: SyliusResourceBundle. Il a été pensé afin de pouvoir supporter plusieurs types de drivers (DoctrineORM, PHPCR). De plus, il permet de construire rapidement une API grâce au FOSRestBundle.

Ce n'est pas un admin generator. Il faut faire ses templates, ses routes… Mon avis : sympa pour réaliser rapidement un POC ou une application simple. Mais fort couplage au framework et à l'ORM, donc cela semble moins souple.

[Voir la doc très complète de SyliusResourceBundle](http://docs.sylius.org/en/latest/bundles/SyliusResourceBundle/)


## Repousser les limites : HTTP cache et utilisateurs connectés par David Buchmann et Jérôme Vieilledent

Il s'agit d'une présentation des différentes manières de cacher du contenu personnalisé. Au travers du concept du "user context", comment mutualiser du cache entre des utilisateurs connectés et partageant le même profil de permission ? Nous étudierons également les Edge Side Includes (ESI) et comment cacher des fragments différemment. Ces concepts sont rendus possibles avec Varnish et le composant HttpCache de Symfony, grâce à FOSHttpCacheBundle, que des projets comme le CMS Open Source eZ Publish utilise.

Le cache dans le contexte user a toujours semblé impossible.
Introduction de "l'User context Hash" :
* transparent : reverse proxy
* footprint pour chaque user

Faire attention :
* à nettoyer le cookie header quand le hash est *fetché*
* à vérifier que les Vary header sont corrects

Combiner plusieurs Vary Header :
* Différentes règles de cache : no caching, Vary: user context, Vary: Cookie
* ESI de différentes règles pour des fragments spécifiques

La présentation se termine par un use case sous forme d'une pièce de théâtre assez drôle avec des vrais cookies (oui le biscuit) : accès à un résumé d'article (user anonymous) ou à l'article complet (user authentifié).

Ressources :
* [Voir la doc de FOSHttpCacheBundle](https://github.com/FriendsOfSymfony/FOSHttpCacheBundle)
* et plus spécifiquement [la doc sur le User Context](https://github.com/FriendsOfSymfony/FOSHttpCacheBundle/blob/master/Resources/doc/features/user-context.rst)


## Laisse pas traîner ton log ! par Olivier Dolbeau

Introduction à comment tirer parti des logs grâce à Monolog et à la stack ELK (Elasticsearch / Logstash / Kibana) et en faire des dashboards.

En savoir plus : [Speakerdeck Laisse pas traîner ton log !](https://speakerdeck.com/odolbeau/laisse-pas-trainer-ton-log)


## Retour d’expérience : attention chérie, ça va trancher par Bastien Jaillot

Pitch :

> Vous vous sentez comme un membre d’équipage dont le navire est sur le point de se briser ? Votre environnement de travail est si triste que vous n’avez plus confiance en l'humanité ? Google vous propose une solution : http://goo.gl/I4jM4n Notre histoire, se déroule au sein d’une équipe technique au bord de l'apoplexie tant en terme humain que technique. Elle va progressivement remonter la pente et gagner ses lettres de noblesses pour enfin brandir son glaive et s’écrier “For The Victory!”. Comment passer d’un monolithe à une architecture microservices (à base de composants Symfony2) ? En quoi l’arrivée d’un chef de projet et de nouveaux process nous a sauvé la mise ? En quoi l’attention portée sur l'humain a favorisé l’émergence d’un collectif technique ? Comment favoriser l’insertion d’une nouvelle personne dans l’équipe ? En quoi l'utilisation pragmatique de technologies reconnues et émergeantes (elk, docker) nous a aidés au jour le jour.

Retour d'expérience sur la refonte du site MediaPart d'une architecture monolithique Drupal 6 vers des microservices basés sur ElasticSearch et Symfony2.
"Tout logiciel reflète l'organisation qui l'a créée" (loi de Conway). Tout ce qui se rapporte à l'informatique arrive aux développeurs. Le SI est devenu lui même un monolithe.

Il s'agit non pas d'une présentation technique mais d'un retour d'expérience sur le changement.

Électro-choc et solution pour amorcer le changement :
* Démarrer un projet ambitieux.
* Une personne pour gérer le "bruit" (gestion du parc SI).
* Ne plus corriger les anomalies non bloquantes, ni d'évolutions. Site en jachère.
* Redémarrer le développement du site en partant sur de meilleures bases et recrutement de profils spécialisés développement web.

Ne pas oublier :
* Faire attention à la communication interne.
* Prendre du temps, avoir du recul.
* Être à l'écoute des problématiques rencontrées par l'entreprise et réfléchir à comment les résoudre.
* Se baser sur l'humain.


## Symfony et Sonata Project chez Canal+ par Thomas Rabaix

Pitch :
> La présentation abordera l’usage de Symfony2, l’organisation du code et l’usage du projet Sonata chez Canal+ pour répondre aux enjeux de la refonte d’une partie de la plateforme web. La plateforme présente de nombreux challenges techniques: SDK, API privée, API publique restful+hal, création de contenus riches, 5 applications différentes avec une base de code commune.

En vrac :
* Un même socle Symfony
* Composants partagés
* Applications découplées
* Tests unitaires sur l'ensemble des applications
* Très peu de bundles utilisés : FosRest, FosUser, Nelmio, LiipMonitor, Bundles Sonata
* SonataRestAdminBundle : administrer des contenus depuis plusieurs sources via APIs. Devrait être *open sourcé* sous peu.
* Nginx pour gérer le crop/resize des images afin de ne pas stocker les images temporaires sur le filesystem
* Création d'un [client pour consommer une API Hateoas (HAL)](https://github.com/ekino/php-hal-client)


## ElasticSearch dans une infrastructure Symfony2 par Nicolas Badey

Présentation d'ElasticSearch avec Elastica et son intégration dans Symfony2.

[Speakerdeck de la présentation ElasticSearch](https://speakerdeck.com/nicolasbadey/elasticsearch-with-elastica-in-symfony2-architecture)


## OpenClassrooms - Le pattern View Model avec Symfony2 par Kuzniak Romain

Le pattern View Model est un pattern simple qui permet d’apporter des solutions à beaucoup de problèmes :
* Découpler la logique métier de la présentation
* Permettre le refactoring
* Faciliter les tests
* Permettre de paralléliser le travail front et back
* Construire des ressources API
* Contrat entre le front et le back sur le contenu d'un ViewModel

Superbe et simple introduction à ce pattern. En savoir plus : [Pattern Modèle-Vue-VueModèle](http://fr.wikipedia.org/wiki/Mod%C3%A8le-Vue-VueMod%C3%A8le)


## Meetic backend mutation with Symfony par Joris Calabrese

Comment Meetic opère son changement technologique sur son SI. De la création d’API jusqu’à la mise en place d’une démarche qualité tout en passant par l'adoption du Behavior Driven Development.

En vrac :
* Migration d'un code monolithique vers des API REST en Sf2
* Exemple de microservices : AB Test, GEO, Permission, Configuration
* Déploiement avec Composer, Satis, Sf2 et Capistrano sur des centaines de serveurs
* Démarche Qualité (Back, Front, App)
* Méthodologie : Agilité, DevOps, TDD, BDD.
* Next steps : Kafka, Continuous Delivery.

Monter en compétences :
* Learning permanent
* Gitlab, merge request, code review
* Metrics jenkins sur ce qui ne va pas / CI jenkins
* Dashboard physique
* Personne extérieures qui viennent présenter des choses nouvelles et enrichir l'équipe.

LA phrase du jour : L'intégration continue c'est "Harder better faster stronger" (Daft Punk) :)

[Superbe présentation à découvrir sur Slideshare](http://fr.slideshare.net/meeticTech/meetic-backend-mutation-with-symfony)


## Faites plaisir à vos utilisateurs : surveillez votre prod par Grégoire Pineau

Développer une application Symfony est maintenant chose commune, mais en connaissez-vous vraiment le comportement en production ? [Le SpeakerDeck Monitorer sa prod](https://speakerdeck.com/lyrixx/symfony-live-2015-paris-monitorer-sa-prod)


## Symfony Debug et VarDumper, ou comment debugger comfortablement par Nicolas Grekas

Il n'y a que des bugs faciles à résoudre… quand on a de quoi les cerner ! [Voir la présentation Symfony Debug et VarDumper](http://fr.slideshare.net/nicolas.grekas/symfony-debug-vardumper)


## Construire des applications API-centric avec Symfony par Kévin Dunglas

Présentation très intéressante sur une architecture qui permet de construire des applications performantes, évolutives et interopérables :
* Un modèle de données dérivé du vocabulaire Schema.org généré avec [PHP Schema](https://github.com/dunglas/php-schema).
* Une API REST hypermedia et auto-découvrable (JSON-LD + Hydra) réalisée avec [DunglasJsonLdApiBundle](https://github.com/dunglas/DunglasJsonLdApiBundle) (pour Symfony 2.7+).
* Sérialisation et validation avancées des données grâce aux nouvelles fonctionnalités du composant Serializer de Symfony 2.7 et du Validator.
* Authentification stateless (cookie-less) avec JSON Web Token et [LexikJWTAuthenticationBundle](https://github.com/lexik/LexikJWTAuthenticationBundle).
* BDD et web acceptance testing avec Behat, Mink et Behatch.


## Le DIC, ce chef d'orchestre! par Adrien Brault

[Introduction au conteneur d'injection de dépendances (DIC) de Symfony](https://speakerdeck.com/adrienbrault/le-dic-ce-chef-dorchestre) et à une utilisation plus avancée.


## Docker dans le développement web et l'intégration continue par Jérémy DERUSSÉ

Pitch :

> Les containers sont venus bousculer le monde de la virtualisation, et Docker est devenu un outil incontournable. Nous verrons comment l'utiliser avec Symfony et surtout comment l'ajuster pour résoudre les problèmes courants, améliorer les performances ainsi que l’expérience du développeur. Nous nous intéresseront également à son utilisation dans le processus d'intégration continue, nous verrons comment Docker peut simplifier et améliorer l’exécution des tests fonctionnels.

Très intéressante présentation de Docker et de ses possibilités (surtout les problèmes qu'il résout. [Les slides sont à voir ici.](http://slides.com/jeremyderusse/docker-dev)


## Une API et une admin en moins de 10 minutes ? Challenge accepted! par Jonathan Petitcolas

Créer une API REST… Une mission simple en apparence, mais pouvant s'avérer chronophage, et ce même avec certains bundles reconnus tels le FOSRestBundle. Heureusement, d'autres bundles existent, privilégiant les conventions par delà la configuration, ce qui est le cas du [StanLemon/RestBundle](https://github.com/stanlemon/rest-bundle).

Petit instant "magique" dans cette Symfony Live durant lequel Jonathan de Marmelab live-code en une dizaine de minutes et en quelques lignes de code, une API REST pleinement fonctionnelle. Il implémente aussi une interface d'administration basée sur [ng-admin](https://github.com/marmelab/ng-admin).


## Mais aussi…

A partir de là, comme beaucoup de personnes dans la salle, j'avoue avoir déconnecté. Les talks suivants ont consisté à la présentation monotone de solutions Open Source. Voici les pitchs.


### Retour d'expérience sur l'édition d'un "Enterprise Software" basé sur Symfony par Nicolas Dupont

> Akeneo PIM est un outil de gestion de catalogue produits open source basé sur Symfony et Doctrine. Le développement a démarré il y a deux ans, l'équipe produit a évolué de 2 à 12 personnes, nous proposons une version communautaire et une version entreprise, utilisées par des clients grands comptes au sein de leur SI. En tant qu'éditeur, nous nous concentrons sur le développement des nouvelles fonctionnalités et la maintenance, les développements spécifiques étant réalisés par un réseau de partenaires intégrateurs. Durant cette conférence, sous la forme d'une rétrospective, nous souhaitons présenter notre retour d'expérience technique et méthodologique sur la création de ce produit. Nous présenterons les avantages d'utiliser Symfony comme socle technique, comment rendre une application extensible et maintenable, les écueils techniques que nous avons rencontrés ainsi que les choix techniques que nous ferions aujourd'hui avec l'expérience acquise.

À noter : avant de résoudre un bug un test behat est écrit pour reproduire ce bug, afin d'améliorer la couverture du code.


### Symfony pour construire des sites e-commerce de nouvelle génération par Manuel Raynaud

> Le modèle très populaire "catalogue de produits en ligne" est largement adressé par des solutions génériques telles que Magento, Prestashop ou Shopify. Depuis quelques années sont arrivés des services tels que AirBnB ou Uber qui représentent la nouvelle génération de sites e-commerce. Ces projets sont des applications connectées de commerce électroniques, elles sont au cœur d'un écosystème e-commerce complexe comprenant les terminaux mobiles, des systèmes de réservation, des objets connectés,.. Or, ces sites innovants sont développés from scratch car les solutions génériques proposent une réponse pour le plus grand nombre mais n'offrent pas la flexibilité et l'interopérabilité attendue. Thelia, en intégrant des composants Symfony, permet à la fois d'avoir les briques nécessaires pour un site ecommerce tel que la gestion du catalogue ou du panier. Mais il permet aussi d'étendre ses capacités grâce à l'ajout du container Symfony ou de l'Event Dispatcher.


### BackBee - The NextGen Content Manager par Mickaël Andrieu, Charles Rouillon

> Backbee est un CMS reposant sur des composants Symfony et Doctrine. Il offre aux contributeurs des sites une expérience ergonomique inédite leur assurant une prise en main rapide et une large autonomie de mise à jour de leurs contenus. La définition souple des droits, des processus de publication et la mise en version systématique des contenus leur assure de plus une grande sécurité éditoriale. BackBee est distribué sous licence open source GPL3.


## Conclusion

Nous étions plus de 700 personnes à ce Symfony Live, un record. On a beaucoup parlé de micro-services et d'API. Le format mono-track des conférences est idéal. Cependant le niveau des présentations était assez inégal. Certaines étaient trop courtes car on aurait aimé en savoir plus, d'autres trop longues.
Cerise (de Groupama) sur le gâteau, j'aurai le droit de revenir, car j'ai gagné ma place pour la SymfonyCon de décembre prochain ! Merci Sensio !
