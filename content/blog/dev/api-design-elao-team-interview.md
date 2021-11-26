---
type:               "post"
title:              "Conception et développement d'API : l'interview croisée de l'équipe Élao"
date:               "2017-11-27"
lastModified:       ~

description:        "Interview croisée des développeurs d'Élao à propos de leurs diverses expériences en conception et développement d'API"
tableOfContent:     2

thumbnail:          "images/posts/thumbnails/api-interview-micro-phone.png"
banner:             "images/posts/headers/api-interview-micro-phone.png"
tags:               ["API", "Conception", "REST", "API Design"]
categories:         ["Dev"]

authors:            ["elao"]
---

Concevoir et développer une API n'est pas un exercice trivial. La littérature en la matière et les ressources sur Internet abondent, mais au moment d'implémenter une API, le développeur reste confronté à de nombreux choix.

Plutôt qu'énumérer une litanie de bonnes pratiques pontifiantes, nous donnons la parole à nos développeurs pour qu'ils partagent leurs expériences, vous livrent leurs points de vue ainsi que des conseils utiles sur les nombreux aspects techniques qui touchent aux API.

<!--more-->

## Pouvez-vous résumer votre expérience des API en quelques mots ?

__Yves__ : Mon expérience concerne principalement des API privées, pour lesquelles les contraintes de versioning ou de documentation ne constituent donc pas des enjeux forts. Les API sur lesquelles j'ai travaillé étaient principalement basées sur les principes REST, conçues la plupart dans le contexte de micro-services ou de Backends applicatifs. J'ai eu l'occasion d'utiliser différents formats d'API : [Collection+JSON](http://amundsen.com/media-types/collection/format/), [HAL](http://stateless.co/hal_specification.html), [json:api](http://jsonapi.org/), etc.

__Maxime S.__ : J'ai pris part à de nombreux développements d'API, qu'elles soient internes ou à l'usage des clients. Je me suis très tôt frotté aux API. Une de mes premières expériences consistait à développer un store d'applications privées, consommées par plusieurs clients. Globalement, lorsque je développe une API, j'adopte les conventions communes et m'efforce de respecter la spécification HTTP (et notamment l'usage des méthodes HTTP), mais je n'essaie pas de coller à tout prix aux standards REST, mais les implémente plutôt à ma façon. Je m'autorise par exemple à utiliser des verbes dans mes URI avec la méthode `PATCH` pour coller au mieux au métier, et n'utilise quasiment jamais `PATCH` pour des mises à jour partielles.

__Nicolas__ : J'ai également pris part au développement d'APIs privées pour plusieurs projets, principalement en REST et plus récemment j'ai pu développer une API en GraphQL pour un projet client. Je suis de plus en plus confronté à la réalisation d'APIs afin de faire communiquer un frontend en javascript (React, VueJs) et un backend en Symfony.

## Y a-t-il un code HTTP peu connu que vous utilisez régulièrement ?

__Yves__ : J'utilise régulièrement le code 422 (extension du protocole HTTP [Webdav](https://fr.wikipedia.org/wiki/WebDAV)) pour remonter des erreurs de validation métiers, et les distinguer ainsi du code 400 que l'on réserve habituellement aux requêtes mal formées. Cette distinction conventionnelle offre une meilleure lisibilité des erreurs côté consommateur.

__Maxime S.__ : Yves m'a fait découvrir le code 422, qui est le principal code d'erreur que j'utilise depuis. Je réserve le code 400 aux requêtes mal formées (lorsque la requête n'est pas au format JSON, par exemple).

__Nicolas__ : J'utilise également assez régulièrement le code 422 pour les APIs REST. Par contre, pour le développement d'API GraphQL, il est beaucoup plus difficile de retourner des codes spécifiques étant donné la nature même de GraphQL qui permet de faire plusieurs requêtes simultanées. Donc le code ne serait pas pertinent si une partie des requêtes arrive à leur terme.

!!! note ""
    Voir la [liste complète des codes HTTP](https://en.wikipedia.org/wiki/List_of_HTTP_status_codes)


## Choisir entre les méthodes `POST`/`PATCH`/`PUT` : conseils, critères de choix ?

__Yves__ : J'utilise souvent la méthode `PUT` dans le cadre d'une relation 0:1 : si la ressource n'existe pas, elle est créée, dans le cas contraire, la totalité de la ressources est mise à jour. Cela permet d'implémenter pleinement l'idempotence de la méthode `PUT` (l'URI peut être appelée plusieurs fois, elle laissera toujours la ressource dans le même état). J'essaie dans la mesure du possible d'éviter l'utilisation de la méthode `PATCH`, car c'est un format d'opération somme toute assez complexe (cf. [RFC 6902](https://tools.ietf.org/html/rfc6902#section-4)). Quant à la méthode `POST`, je l'utilise pour la création de ressource, comme une méthode _factory_. Noter d'ailleurs que je m'autorise parfois quelques infractions aux principes REST, mais sans en abuser. Il m'arrive par exemple d'utiliser la méthode `POST` avec une URI qui comporte un verbe, même s'il ne s'agit pas à proprement parler d'une création de ressource. Exemple : `POST  /ma-resource/{id}/change-address`. Je m'autorise cette infraction lorsque j'estime qu'elle apporte une meilleure compréhension du métier, et également pour obtenir des logs plus parlants.

__Maxime S.__ : J'essaie au maximum de respecter la sémantique des méthodes HTTP telles qu'elles sont définies dans la spécification. Comme je l'ai dit tout à l'heure, je m'autorise à utiliser des verbes dans mes URI avec la méthode `PATCH` pour coller au mieux à la logique métier (Exemple d'URI pour activer/désactiver un utilisateur : `PATCH /users/{id}/lock|unlock`).

<p class="text-center">
    {{< figure class="text-center" src="images/posts/2017/api-interview/postman.png" title="Credits: https://www.getpostman.com/" alt="Postman">}}
</p>

## Formats de sortie : privilégiez-vous le tout JSON ?

__Yves__ : J'ai effectivement pour habitude de privilégier ce format par défaut, mais les contraintes métiers vous obligent parfois à prévoir d'autres formats de sortie, comme le PDF par exemple. Lorsque je travaille sur des API assez complexes, j'ai tendance à favoriser le format json:api.

__Maxime S.__ : Je privilégie également le format unique JSON et implémente d'autres formats seulement lorsque les contraintes métiers l'imposent.

__Nicolas__ : Je retourne essentiellement du JSON. Mais comme Maxime S. il peut arriver d'implémenter d'autres formats comme le XML pour certains besoins clients.

## Gestion des erreurs, erreurs de validation : formalisme, pratiques ?

__Yves__ : J'ai eu l'occasion de tester plusieurs formats de sortie, et au final, je me suis plié à l'usage courant en adoptant le format `application/problem+json` . Je m'appuie bien entendu sur les codes HTTP pour retourner des erreurs, mais je peux parfois les compléter par des codes métiers _custom_ transmis dans le corps de la réponse, lorsque j'estime que cela apporte quelque chose. J'essaie néanmoins de limiter cette pratique pour ne pas avoir à maintenir un référentiel des codes erreurs personnalisés.

__Maxime S.__ : J'utilise énormément le code 422 et tout comme Yves, j'implémente le format `application/problem+json`. Pour le contenu de la réponse, je m'inspire de l'implémentation d'[API Platform](https://api-platform.com/) et complète le corps de la réponse JSON avec une liste de violations, que je construis en m'appuyant sur l'interface `ConstraintViolationListInterface` de Symfony. En ce qui concerne les autres codes d'erreur, je retourne le code d'erreur HTTP qui décrit le mieux la situation et j'évite d'introduire des codes d'erreur _custom_.

__Nicolas__ : Tout comme Yves et Maxime S., le format de sortie est essentiellement du `application/problem+json` avec un code HTTP spécifique, et il est assez fréquent également que je liste dans le retour la liste des violations rencontrées lors de l'appel. Concernant GraphQL, la chose est assez simple à gérer car les schémas de requêtes sont définis en amont et donc une grande partie des problèmes est gérée directement par l'implémentation de GraphQL utilisée.

## Le versioning d'API : quelle stratégie préconisez-vous ?

__Yves__ : J'ai eu l'occasion d'implémenter les deux stratégies (version dans l'URL ou dans un _header_) et au final, j'ai une nette préférence pour la version incluse dans l'URL, car cela facilite la tâche côté utilisateur ; de plus, cette stratégie a le mérite de la visibilité, en particulier dans les fichiers de log.

__Maxime S.__ : Lorsque j'utilisais le JMS Serializer, j'implémentais ses annotations de versioning sur les propriétés des entités. Désormais, j'utilise le composant Serializer de Symfony, et je ne mappe plus directement mes entités à l'API. Je passe aujourd'hui par des annotations au niveau des actions de contrôleur pour versionner mes API et la démarcation se fait donc au niveau de mes contrôleurs. Quant au versioning dans l'URI ou dans les _headers_, je n'ai pas d'avis arrêté. Je note cependant que les headers offrent plus de souplesse, pour gérer par exemple des versions mineures (ex: version 1.1).

## Documenter votre API : quels outils ?

__Nicolas__ : Pour ma part, lors des développements d'API GraphQL, j'utilise [GraphiQL](https://github.com/graphql/graphiql) qui met à disposition directement dans le navigateur un outil de requêtage auto documenté grace à la description que l'on rédige lors de l'exposition des schémas. Les requêtes disponibles ainsi que leur format sont alors proposés directement à l'utilisateur et il peut même les tester immédiatemment grâce au requêtage intégré.

__Maxime S.__ : Aujourd'hui j'utilise principalement le bundle [NelmioApiDocBundle](https://github.com/nelmio/NelmioApiDocBundle) pour générer la documentation de mes API. Il est parfois utile de générer la documentation à la main lorsque l'on souhaite fournir une documentation d'API plus complète. La documentation générée est généralement suffisante pour des API consommées par des développeurs, mais me semble trop sommaire pour des API publiques. Je profite également de cette question pour mentionner la librairie PHP [elao/api-resources-metadata](https://packagist.org/packages/elao/api-resources-metadata) que nous avons initiée. Elle est encore embryonnaire, mais pour l'heure, elle s'interface avec le bundle de Nelmio pour documenter des ressources PHP à partir d'un schéma YML ou des _doc blocks_. Elle sera sans doute enrichie, pour s'interfacer avec d'autres librairies et ajouter d'autres fonctionnalités, telles que la génération de _normalizers_ ou de contraintes de validation basiques (`NotNull`, `Type`, ...) par exemple.

<p class="text-center">
    {{< figure class="text-center" src="images/posts/2017/api-interview/swagger.png" title="Credits: https://swagger.io/" alt="Swagger">}}
</p>

## Comment tester son API ?

__Maxime S.__ : Je privilégie les tests fonctionnels en m'appuyant sur l'outillage de Symfony (en particulier la classe [`WebTestCase`](https://github.com/symfony/framework-bundle/blob/master/Test/WebTestCase.php)) et réserve mes tests unitaires pour le métier. Les tests fonctionnels permettent de tester davantage de _use cases_ et constituent à l'intention des développeurs un excellent complément à la documentation du projet.

__Nicolas__ : Pour tester nos APIs, qu'elles soient en REST ou en GraphQL, j'utilise essentiellement des tests fonctionnels Behat avec l'extension [Behatch](https://github.com/Behatch/contexts) qui permet d'utiliser le langage Gherkin.

## Avez-vous des pratiques particulières concernant les URI ? Bannissez-vous sytématiquement les verbes ? Dans quels cas les utilisez-vous ?

__Yves__ : Concernant les verbes dans les URI, j'ai déjà eu l'occasion d'y répondre. Sauf cas particulier (exemple: une arborescence de fichiers), je m'efforce généralement de ne pas aller au-delà de deux niveaux de ressources dans mes URI. Exemple : `/users/{id}/friends`. J'utilise toujours le pluriel pour mes ressources et m'autorise des pluriels anglais peu académiques lorsque j'estime que cela améliore la lisibilité des ressources (exemple: `persons` au lieu de `people`). Pour les mots composés, je privilégie l'usage du tiret (plutôt que le _camelCase_) car je trouve ça plus lisible. Je n'hésite pas non plus à utiliser des noms de ressources verbeux et des termes orientés métiers. En d'autres termes, je n'hésite pas à privilégier la lisibilité et la verbosité au détriment de la concision.

## Un petit mot sur HATEOAS ?

__Yves__ : J'ai été un adepte de la première heure et j'ai été dès le début séduit par sa philosophie, en particulier le concept de découverte d'une API par l'usage. Il faut dire qu'à l'époque, les API exposaient facilement leurs opérations de lecture, mais il était moins aisé de découvrir les opérations d'écriture. Aujourd'hui, je suis moins sensible à HATEOAS, pour plusieurs raisons : la maintenance que cela implique, le peu d'intérêt que cela présente pour les développements Frontend et le nombre d'appels nécessaires pour trouver le _endpoint_ souhaité. En outre, le format json:api prévoit des fonctionnalités de navigation simplifiées qui suffisent amplement à mes besoins.

## Utilisez-vous une bibliothèque-cadre pour développer vos API ? Symfony REST edition ? API Platform ? Autre ?

__Yves__ : J'avais eu l'occasion de tester [api blueprint](https://apiblueprint.org/) et j'avais notamment apprécié ses générateurs (documentation, tests, code client) mais je me suis aussi heurté à certaines de ses limites (il fallait parfois que j'adapte ma conception à l'outil). Aujourd'hui, je n'utilise pas de librairie orientée API. Mes développements s'appuient essentiellement sur Symfony, et sur les écouteurs d'événements, pour traiter les erreurs (_Exception listener_) ou constuire mes ressources depuis la requête HTTP (_Request listeners_) ...

__Maxime S.__ : J'ai eu l'occasion de tester et contribuer à [API Platform](https://api-platform.com/) à l'époque où il était encore en _beta_. C'est un excellent outil dans le contexte de développements RAD orientés CRUD. Aujourd'hui, je continue de suivre l'évolution du projet, et le travail entrepris est titanesque. Mais à l'heure actuelle, je n'utilise pas de bibliothèque dédiée. Je travaille sur une stack Elao que nous améliorons et enrichissons progressivement au fil des projets.

__Nicolas__ : Pour ne pas trop répéter les propos de Yves et Maxime S., je vais plus parler de GraphQL. Nous avons utilisé [le bundle GraphQL réalisé par Overblog](https://github.com/overblog/GraphQLBundle) qui s'appuie sur [l'implémentaion PHP de GraphQL par webonyx](https://github.com/webonyx/graphql-php). Cela permet de facilement intégrer GraphQL dans nos projets ainsi que de décrire nos schémas de _queries_ et _mutations_ en Yaml.

## Normalizers / Serializers : composant `Serializer` de Symfony ou JMSSerializer ?

__Maxime S.__ : Aujourd'hui je n'utilise plus que le composant `Serializer` de Symfony, mais seulement comme un outil technique. J'entends par là que je ne mets plus aucune logique métier dans mes _normalizers_. Si je dois sérializer un objet complexe (comme des données agrégées par exemple), c'est un service dédié qui sera chargé de le construire et c'est cet objet que je passe ensuite directement au _Serializer_. Je n'écris quasiment plus de _normalizers_. Pour retourner des données après un appel `GET` (_queries_), je m'appuie sur des _converters_ qui créent la ressource à partir de la requête, laquelle est transmise au _Serializer_ de Symfony pour la retourner au format JSON. Pour les appels en écriture (_commands_), j'hydrate/désérialize au moyen du _Serializer_  Symfony un DTO de payload à partir de la requête, passé ensuite au validateur de Symfony, puis injecté dans une _command_ pour être traité par le _handler_ approprié. Noter que la désérialization doit être permissive, car je confie la validation du payload aux validateurs de Symfony et on s'autorise donc à instancier un payload invalide. Pour cela, je tire parti d'une mise à jour incluse dans Symfony 3.4 qui permet de passer outre la vérification du typage lors de la sérialization (Cf. [PR 8515](https://github.com/symfony/symfony-docs/pull/8515)).

__Nicolas__ : Tout comme Maxime S., je me sers exclusivement du composant `Serializer` de Symfony comme passe-plat. L'ensemble de ma logique est présent dans la couche métier et dans mes _query handlers_ ou _command handlers_.

## Communication développeurs Backend/Frontend : des conseils ?

__Yves__ : J'ai plutôt tendance à privilégier la discussion orale plutôt qu'une documentation "anémique" à outrance. Mais je dois avouer que j'ai essentiellement travaillé dans des petites équipes où la communication n'était pas entravée.

__Nicolas__ : Lors des développements GraphQL que j'ai pu faire récemment, l'utilisation de GraphiQL a permis de simplement documenter l'utilisation de l'API fournie par le backend et ainsi de permettre aux développeurs frontend de facilement savoir ce qu'ils pouvaient et comment ils pouvaient le requêter. Après, comme Yves le dit, nous travaillons essentiellement dans des petits équipes où la communication orale est très régulièrement utilisée. Donc quand un problème était rencontré, nous embrayions à l'oral pour faciliter les échanges et léver les incertitudes rapidement.

## Et GraphQL dans tout ça ?

__Yves__ : C'est une philosophie différente de REST, qui mérite que l'on s'y intéresse. Mais j'attends également avec impatience les apports du protocole HTTP/2 et je souhaite notamment voir s'il permettra de limiter les appels HTTP, qui est une des problématiques qu'entend adresser GraphQL.

__Maxime S.__ : Hormis quelques articles de François Zaninotto sur le sujet, je n'ai pas encore pris le temps de me pencher sérieusement sur la question. GraphQL laisse entrevoir de très belles promesses, mais je m'interroge notamment sur la sécurisation des API et les performances, en raison des requêtes potentiellement lourdes qu'autorise GraphQL à un client.

__Nicolas__ : Définitivement testé et adopté!

<p class="text-center">
    {{< figure class="text-center" src="images/posts/2017/api-interview/graphql-logo.png" title="Credits: https://commons.wikimedia.org" alt="Logo GraphQL">}}
</p>

## Un ouvrage ou un site de référence à conseiller ?

__Maxime S.__ : Il y a quelques années, je consultais régulièrement le [blog de William Durand](https://williamdurand.fr/). En ce moment, je suis avec intérêt les [articles de blog](https://marmelab.com/blog/2017/09/04/dive-into-graphql-part-i-what-s-wrong-with-rest.html) de François Zaninotto au sujet de GraphQL. Et je continue également à suivre régulièrement l'activité du [dépôt Github d'API Platform](https://github.com/api-platform/api-platform).

__Nicolas__ : Le [site de GraphQL](http://graphql.org/) est assez bien fait et propose une rubrique _Learn_ bien fournie qui permet une première entrée en matière. La [démo](http://graphql.org/swapi-graphql/) proposée par l'outil GraphiQl est aussi très intéressante pour faire ses premières requêtes GraphQL sans rien n'avoir à installer.

## Quel conseil donneriez-vous à un développeur qui débute dans les API ?

__Yves__ : Il ne faut pas hésiter à s'inspirer des API existantes développées par de grands acteurs du Web (Spotify, Github, etc.). Ils ont eu à se frotter aux principales problématiques qu'impliquent la conception et le développement d'une API et on aurait tort de se priver de leur expérience. Quoi qu'il en soit, je pense que quelles que soient les règles que vous vous fixez, le plus important (mais pas le plus simple!), c'est de conserver une cohérence globale.

__Maxime S.__ : S'efforcer de faire simple et surtout, quels que soient vos choix techniques et les principes qui ont guidé votre conception, s'y tenir tout au long du projet pour maintenir une cohérence globale.

__Nicolas__ : Je pense que Yves et Maxime S. ont bien résumé ce que j'aurais pu dire : éviter de changer de façon de faire en cours de projet pour garder une cohérence. Après, si vous débutez dans les APIs, n'hésitez pas à jeter un oeil à GraphQL qui, selon moi, est assez mature pour être utilisé sur de nouveaux projets.

## Une question que vous auriez aimé que l'on vous pose à propos des API ? Ou bien quelque chose à ajouter ?

__Yves__ : N'hésitez pas à consulter la liste des headers HTTP natifs. Nous connaissons tous les headers d'authentification, mais il en existe bien d'autres qui peuvent être tout-à-fait adaptés aux informations que l'on souhaite retourner. Exemples : les headers d'authentification, les headers de langue et d'internationalisation. Il faut également savoir qu'il existe des headers proposés par des extensions HTTP et il arrive parfois que ces headers entrent dans la spécification HTTP (comme par exemple le header `x-forwarded-by` objet de la RFC 7239). En revanche, avant d'adopter un header, qu'il soit standard ou extrait d'une extension, assurez-vous que vos proxies HTTP les supportent. On peut parfois être amené à enfreindre des standards lorsque l'outillage ou l'infrastructure nous y contraint. En résumé, il ne faut jamais perdre de vue l'infrastructure qui accueillera votre API au moment de la concevoir et c'est un piège que l'on a souvent tendance à négliger.

!!! note ""
    Voir la [liste complète des headers HTTP](https://en.wikipedia.org/wiki/List_of_HTTP_header_fields)
