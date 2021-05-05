---
type:               "post"
title:              "Retour sur le Best Of Web 2015"
date:               "2015-06-12"
lastModified:       ~

description:        "La première édition du Best Of Web s'est tenue le Vendredi 5 Juin 2015 à Paris, et à rassembler le meilleurs des meetups de l'année. Retour sur cet évènement."

thumbnail:          "images/posts/thumbnails/geek_evolution.jpg"
banner:             "images/posts/headers/forum_php_elao.jpg"
tags:               ["Javascript", "Web","conference"]
categories:         ["Actualité", "Web", "conference"]

author:    "ndievart"
---

La première édition du Best of Web s’est tenue à Paris le vendredi 5 Juin 2015 à la Grande Crypte de Paris.
L’idée de cette rencontre est de rassembler les meetups parisiens orientés web et permettre à ceux qui les ont loupés de découvrir leurs meilleurs talks.
<!--more-->
Bien évidemment, une grosse majorité des talks étaient tournés vers le JS mais pas seulement, on a pu découvrir pas mal de choses.
L’événement était sur une seule trame et ce n’est pas plus mal, car cela permet de se confronter à des sujets qu’on n’aurait pas forcément choisi si le choix des conférences était plus ouvert.

## [Keynote d’ouverture](http://deliciousinsights.github.io/best-of-web-2015/) par Christophe Porteneuve
Pour commencer la journée et nous mettre en jambe, le best of web s’est ouvert par une Keynote de Christophe Porteneuve (Monsieur Node School). On a eu le droit à une bonne petite rétrospective du milieu du web et sa dose de trolls, un état des lieux d’aujourd’hui et sa vision (partagé par beaucoup) de demain où le JS rafle la mise sur tous les domaines (entreprises, os, etc…)

Après une keynote de 45 minutes, place aux talks. Les sujets abordés durant la journée étaient assez vastes et permettent de remettre en vision les différents axes du web.

## [The REST World](http://nodejsparis.bitbucket.org/20140312/rest_world/#/) par Virginie Bardales
Virginie Bardales a eu la dure tâche d’enchaîner après Christohpe Porteneuve, mais elle s’en est très bien sortie et nous a permis de revoir (et voir pour certains) les différents niveaux du REST. On a commencé par un petit rappel de ce qu’est une api, et on a embranché ensuite sur les différents niveaux, avec une explication simple et concise de ce que chaque méthode nous permet de faire. Et pour terminer, un petit aperçu du traitement REST via les frameworks node.js (sans oublier, en conclusion, un petit troll sur l’api 2.0 de mailchimp).
Je vous conseille grandement de jeter un œil aux slides, elles sont très instructives et permettent un bon petit rappel.

## [Backbone en 2015](http://slides.com/florentduveau/backbone2015-3#/) par Florent Duveau
Florent Duveau a commencé son talk par remettre en place backbone par rapport à ceux avec qui on le compare régulièrement (Ember et Angular). Effectivement, Backbone est souvent mis en compétition avec les autres frameworks js. Lors de son talk, il nous a permis de revoir les idées derrières sa conception, et les différents points qui sont traités par backbone, et pourquoi, il n’est pas pertinent de le comparer à Ember et Angular.
Ainsi, on a eu une petite piqûre de rappel sur les données, les événements, mais aussi, le fait que backbone n’est pas optimal pour gérer les vues et qu’il doit plus se penser comme un module auquel on vient ajouter d’autres briques qui font bien ce qu’elles traitent (qui a dit React ?).

## [Kinect en JS](http://fr.slideshare.net/3k1n0/ekino-dumand-mickaelkinecthtml5) par Mickael Dumand
Sujet très intéressant et peut être l’un de ceux qu’on attend le moins en pensant au web. Mikael Dumand nous a fait part de ses déboires avec Kinect lors d’un projet client et l’orientation qu’il a prise pour traiter son problème (le manque de documentation pour le développement JS pour Kinect). Il a partagé ses travaux et nous invite à forker et améliorer le repo de sa librairie pour améliorer l’init d’un projet JS avec Kinect : [https://github.com/ekino/ek-winjs-kinect](https://github.com/ekino/ek-winjs-kinect)
Si vous développez des jeux en JS, n’hésitez pas à y jeter un œil, cela peut donner des idées, sachant qu’il y a déjà certaines fonctions opérationnelles (identification d’individus dans un espace précis, drag and drop, click, …)

## [Workflow Cordova avec Tarifa CLI](http://42loops.com/tarifa-bestofweb2015.pdf) par Paul Panserrieu
Un sujet un peu plus complexe pour les non-initiés à Cordova et son workflow.
Paul Panserrieu nous a rappelé rapidement les différents points traités par Cordova et nous explique ce qu’il apporte avec Tarifa. Tarifa permet d’automatiser le workflow et de builder directement vos applications avec plusieurs configurations, directement depuis le terminal.
Et comme tout bon projet open-source, n’hésitez pas à apporter votre contribution (https://github.com/TarifaTools/tarifa).

## [Réactivité et visualisation avec d3.js](http://rluta.github.io/d3-realtime/) par Raphaël Luta
Pour terminer la matinée, Raphaël Luta nous a fait une petite démonstration de la visualisation avec d3.js et surtout de la réactivité. On a eu le droit à un petit test live avec l’écoute d’un hashtag pour faire une élection du meilleur super-héros et ainsi montrer la réactivité d’un dashboard avec d3.js. Ensuite, une bonne explication de comment optimiser la découpe de son code avec l’usage de workers pour permettre d’optimiser le flux des requêtes entrantes. Pour finir, Raphaël Luta nous a fait une démonstration époustouflante du traitement de près de 100 000 flux par secondes et leur affichage en live via son dashboard. Vraiment très bluffant et intéressant, qui donne envie de s’intéresser encore plus à ce que propose d3.js et l’optimisation des flux.

## [NodeWebkit]( https://speakerdeck.com/jacopodaeli/native-javascript-applications-with-nw-dot-js) par Jacopo Daeli
Pour commencer l’après midi, Jacopo Daeli nous a fait une démonstration de NodeWebkit, rebaptisé NW.js récemment suite à son basculement sur io.js. NW.js est un runtime pour application web basé sur chromium et NodeJs (IO.js maintenant). Il permet de développer des applications javascript, html et css et d’en créer une application native (Linux, OsX, Windows), mais l’avantage surtout est qu’il permet d’utiliser les fonctions de NodeJs directement dans le code html.
La configuration est assez intéressante, on peut définir les paramètres qu’on veut assigner à la fenêtre de l’application. Jacopo Daeli nous a fait une [petite démonstration]( https://github.com/JacopoDaeli/bestof-web-paris-2015) avec une application qui prend des photos via sa webcam à un intervalle très court et les stream à une url local, ce qui simule une vidéo. Un exemple d’utilisation de NW.js ? La version desktop de Pop Corn Time l’utilisait, ce qui de se rendre compte de toutes les opportunités de NW.js.

## [CSS Grid]( http://fr.slideshare.net/matparisot/css-grid-layout-le-futur-de-vos-mises-en-page) avec Mathieu Parisot
La suite est très intéressante malheureusement ce n’est pas utilisable immédiatement étant donné la compatibilité. Mathieu Parisot nous a présenté et fait une démonstration de CSS Grid, la spécification en cours de standardisation au W3C, qui permet enfin de s’affranchir de nombreuses contraintes connues du CSS.
Il nous a donc fait un petit récap de la situation actuelle et des solutions pour contourner le problème de faire des grilles en css (Bootstrap, 960 grid, Foundation, Flexbox) mais de la réalité de celles-ci qui ne sont pas encore optimale. On a pu ensuite assister à une démonstration de comment CSS Grid va régler tout cela (une fois supporté) grace à une syntaxe très simple sans avoir à faire appel à des librairies.

## La [« Winning Stack » et l’ES6]( http://fr.slideshare.net/SfeirGroup/es2015-ready-angular-web-stack-bestofweb-2015) par Douglas Duteil
Douglas Duteil nous a présenté sa winning stack avec angular et ES6. Il nous a parlé de systemJs pour le chargement dynamique de module, JSPM pour la gestion des packages, de quelques différences de ES6 (2015) et ES5 (2009), de babel, Istanbul ainsi que de [Isparta]( https://github.com/douglasduteil/isparta) (créé et maintenu par Douglas). D’ailleurs, si le projet Isparta vous intéresse, il cherche de l’aide pour le merger à Istanbul.

## [Web Audio API](http://ouhouhsami.github.io/2015-06-05-bestofweb-paris/) par Samuel Goldszmidt et Norbert Schnell de l'IRCAM
Samuel Goldszmidt et Norbert Schnell sont deux chercheurs et développeurs de l'[IRCAM](http://www.ircam.fr/). Ils nous ont fait découvrir les travaux actuellement en cours à l'IRCAM par rapport à l'utilisation des technologies Web et plus précisément la Web Audio API. Leur présentation était intéractive, ils ont commencé par nous montrer quelque travaux réalisés avec la Web Audio API (Amplificateur, filtre, édition de données temporelles, traitement audio). Ils nous ont ensuite présenté le Collective-Soundworks, un framework pour la création d'application audio et multimédia de manière collaborative auquel ils participent. Ensuite, ils nous ont fait une démonstration collaborative en nous demandant de nous connecter à une url pour que l'ensemble de nos terminaux joue certaines sonorités.
Une présentation assez captivante et qui donne beaucoup d'idées d'applications.

## Le [Material design avec Polymer](https://docs.google.com/presentation/d/1IzsxsE6HybPAdbrI8iIlF3Qaj9BQGEHmu3zX7xT9o8M/edit#slide=id.g3a6159c6f_024) par Martin Gorner
Martin Gorner a commencé sa présentation en nous rappelant ce qui constitue le Material Design, ces éléments (paper, touch first, couleur textuelle, animation, bouton flottant, ...). Il nous a ensuite fait une démonstration de Polymer (qui vient de sortir en version 1.0), quelques petits bower install plus tard et c'est parti, on charge polymer dans la balise script et on peut utiliser les paper-card en les important avec des ``<link rel="import" href`` et ils sont ensuite utilisable directement notre code html. La bibliothèque de base de Polymer est déjà bien fournie mais il est également possible de créer ses propres cards et de bien s'amuser avec le Material Design. Polymer donne vraiment envie. Pouvoir créer une interface Material Design en quelque coups d'imports, ça promet de belles choses.

## Le [Reactive Programming](https://speakerdeck.com/hugocrd/dealing-with-streams-using-rxjs) par Hugo Cordier
Les événements en Javascript sont l'un des composants importants, d'autant plus maintenant avec les frameworks js. Hugo Cordier nous a fait un petit récap' de ce que sont les événements et les promesses, puis présenté RxJs qui permet de faire du traitement de groupe d'événements (événements multiples, au cours du temps, faire des maps et des filters sur les événements). En bref un vrai petit couteau suisse de la gestion des événements en Javascript (il s'agit d'une librairie donc vous pouvez vous amusez avec à peu près tout: node, angular, backbone). Il nous a ensuite fait une démonstration de cas concret de RxJs comme par exemple de la recherche en temps réelle. N'hésitez pas à jeter un oeil à ses CodePens pour les démos, ils sont présents dans la présentation.


## [Ember.js en 2015](http://thau.me/2015/06/building-an-application-with-ember-js-in-2015) par Tom Coquereau
Tom Coquereau a fait un rapide état des lieux d'Ember dans sa version 2.0. Les changements par rapport à la version 1.3 sont seulement la suppression des éléments dépréciés. Il n'y aura donc pas de problème de compatibilité de vos applications si vous aviez déjà commencé à ne plus utiliser les éléments dépréciés avec la version 1.3. Il nous a également expliqué la vision qu'Ember a prise pour le futur, pourquoi certains choix ont été pris etc... Notamment dans le nouveau composant d'Ember permettant de gérer les vues. Tom nous a par ailleurs aussi expliqué un peu plus ce qui se cache derrière Ember CLI et son utilité, qui permet de créer un projet en quelques secondes en ligne de commande (tests, routes, fichiers, composants, la totale).

## Un mot sur l'organisation
Nous étions environ 500 pour cette première édition du Best Of Web, et pour une première édition, que ce soit niveau organisation et sujets abordés, on peut dire que c’est une réussite. Le déroulement des talks était plus que bien géré pour une première. On avait le droit régulièrement à des pauses de 30minutes, ce qui nous a permis de pas mal échanger avec les intervenants mais aussi avec les 500 participants de cette première édition. Mais aussi, de nous désaltérer car par près de 35 degrés dehors et pas de climatisation à l’intérieur, il faisait rapidement chaud. Les pauses étaient donc bienvenues. Petit point repas également, l’organisation était vraiment top, avec un grand choix et un bon moment pour échanger encore une fois avec les personnes présentes.
On a hâte de voir la programmation de l'année prochaine!
