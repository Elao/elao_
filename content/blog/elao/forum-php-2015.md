---
type:               "post"
title:              "Retour sur le Forum PHP 2015"
date:               "2015-11-26"
publishdate:        "2015-11-26"
draft:              false

description:        "Nous étions au Forum PHP de l'AFUP, voici ce que l'on a retenu."

thumbnail:          "images/posts/thumbnails/haphpybirthday.jpg"
header_img:         "images/posts/headers/elephpant_elao_family.jpg"
tags:               ["Conférence", "ForumPHP"]
categories:         ["conference"]

author:    "rhanna"
---

## PHP a 20 ans et s'offre une cure de jouvence avec la version 7

Le Forum PHP de l'<abbr title="Association Française des Utilisateurs de PHP">AFUP</abbr> a réuni au Beffroi de Montrouge du beau monde
cette année, des membres de la Core team du développement de PHP et le créateur du langage, le groenlandais Rasmus
Lerdorf.
Et pour cause ! Nous fêtons les 15 ans de l'association et les 20 ans du langage. Cerise sur le gâteau, nous fêtons
également la sortie de PHP7, une version majeure déjà plébiscitée pour les gains en performance par rapport à PHP5.

<img src="/images/posts/2015/haphpybirthday.jpg" class="text-center">

Point d'orgue de la keynote d'ouverture, diffusion en avant première mondiale de la vidéo
["Haphpy Birthday"](https://www.youtube.com/watch?v=tHlCsZf3nmA),
projet collaboratif animé par notre ami [Julien](https://twitter.com/Woecifaun).
Un montage de vidéos et de photos provenant du monde entier a célébré l'amour des développeurs pour le PHP.
Oui en ces temps obscurs, célébrons l'amour !
L'amour pour PHP mais aussi des valeurs de partage véhiculées par ce langage Open Source.
Finalement nous avons la définition ultime de l'acronyme PHP : People Helping People.

## Les confs auxquelles nous avons assisté

Il y avait 3 tracks de conférences qui se sont déroulées sur deux journées et nous avons dû parfois faire des choix
cornéliens entre les différents sujets.
Voici notre retour sur les conférences auxquelles nous avons assisté.

### Taylorisme de la qualité logicielle par Jean-François Lépine

Il s'agit d'un retour d'expérience sur l'industrialisation des développements.
Nous retenons qu'il est inutile de vouloir tout automatiser.
Il faut surtout automatiser les processus qui sont à risque.
De plus il ne faut pas laisser la technique résoudre tous les problèmes.
L'Humain est au cœur de notre métier. Dans une équipe, il faut des meneurs, des gens motivés qui vont porter un projet
ou des évolutions, que ce soit au niveau technique ou en terme d'organisation.

### Comment Ansible et Docker changent notre environnement de mise en production par Simon Constans et Maxime Thoonsen

Sur des projets courts de quelques semaines à quelques mois, il est important de ne pas perdre de temps pour initier la
production. Cette présentation nous a montré comment tirer parti de Ansible et Docker pour être "up" en production en
très peu de temps.

### Scrum... et après ? par Yvan Wibaux et Olivier Madre

Un des cofondateurs, un architecte et un Developpeur d'Evaneos nous ont présenté comment cette start-up s'est
auto-ré-organiser.
Surtout à un moment critique où de nombreux développeurs sont arrivés en peu de temps.
Tout cela conjugué à une forte croissance et une importante demande en nouvelles fonctionnalités.
En vrac :

* Auto-organisation d'une équipe engagée
* Les développeurs ne sont pas des simples exécutants mais doivent être des développeurs-entrepreneurs
* Ré-organisation physique des bureaux pour mieux partager et échanger
* Amélioration de la gestion des *user stories* : abandon de Jira en faveur de *Trello* ou de simples *post-it*.
* Nouvelles fonctionnalités et stratégies portées par tous
* Le *Product Owner* partage avec les équipes plutôt que ne décide tout seul de l'orientation de son produit
* Organisation par *squad*. Les développeurs se positionnent dans les squads et sur les projets qui les intéressent.
* Encouragement aux projets personnels
* L'impact sur l'architecture technique : micro services dans containers Docker

Bref, un très bon retour d'expérience sur une tendance forte : la libéralisation de l'entreprise.

### Chronique d'un projet Driven Design par Alexandre Balmes

Très bonne introduction sur l'architecture logicielle tendance du moment : Domain Driven Design, Behavior Driven
Design... A Elao, nous avons déjà adopté ce style d'architecture pour des applications métiers complexes.

### CQRS de la théorie à la pratique par Nicolas Le Nardou

Bon retour d'expérience sur la gestion de stock du site ecommerce [materiel.net](http://www.materiel.net/) grâce au
*design pattern* <abbr title="Command Query Responsibility Segregation">CQRS</abbr>.
En résumé, ne mettez pas à jour votre stock en direct, historiser plutôt les évènements (ajout ou suppression d'items)
pour pouvoir "rejouer" l'historique.

[Les slides de CQRS de la théorie à la pratique](https://speakerdeck.com/niktux/cqrs-de-la-theorie-a-la-pratique).

### Halte à l'anarchitecture ! par Gauthier Delamarre

Encore une conférence sur l'architecture logicielle, sujet fort lors de ce Forum PHP. Intéressant retour d'expérience
sur le rôle de l'architecte. Il nous a manqué des exemples plus concrets. Dommage également que le propos ne soit pas
plus nuancé. Par exemple que l'architecture du logiciel ne soit pas plus "collaborative". Conférence intéressante tout
de même qui permet des poser les bases d'une application réussie.

[Les slides de Halte à l'anarchitecture !](http://slides.opcoding.eu/anarchitecture/?standalone#/)

### PHP 7 – What changed internally? par Nikita Popov

Nikita est l'un des développeurs de la *core team* de PHP. Il nous a expliqué très simplement comment PHP7 a gagné en
performance grâce à une meilleure gestion mémoire des variables, des *array* et des classes.

[Les slides de PHP 7 – What changed internally?](http://www.slideshare.net/nikita_ppv/php-7-what-changed-internally-forum-php-2015)

### Zoom sur les objets PHP par Julien Pauli

A l'heure du passage de la version 5 à la version 7, un sujet très intéressant sur le fonctionnement interne des
objets PHP, animé par un développeur de la *core team* de PHP. De temps en temps, cela ne fait pas de mal de regarder
sous le capot.

### Soyez spécifiques ? Un business clair et du code limpide avec RulerZ par Kévin Gomez

Connaissez-vous le *Pattern Specification* ? En gros, les règles métiers de votre application doivent être réunies à
un seul endroit en étant agnostique de votre framework ou autre CMS que vous utilisez.

Kévin Gomez a créé son implémentation de ce *pattern*, une librairie appelée [RulerZ](https://github.com/K-Phoen/rulerz)
disponible en bundle pour Symfony.
La particularité de cette implémentation ? Un langage d'expression proche du SQL pour faire des spécifications lisibles.
Nous avons trouvé cette conférence dynamique et claire avec une présentation de vrais cas concrets.

### L'architecture événementielle chez Meetic par Matthieu Robin et Benjamin Pineau

Meetic est l'une des plus belles réussites de l'écosystème start-up françaises. Au niveau technique, une importante
refonte est en cours pour passer d'une application monolithique vers des micro-services.
Qui dit micro-services, dit système de communication entre ces différentes briques et pour cela il était nécessaire
d'avoir un système de *message queue*. Après études des différentes solutions du marché, les équipes tech de Meetic
ont adopté [Apache Kafka](http://kafka.apache.org/), une solution Open Source qui offre à la fois simplicité d'utilisation et haute performance.
Nous avons été convaincus. Pour sûr, au prochain besoin en *queuing*, au lieu d'un lourd RabbitMQ, nous tenterons la
métamorphose avec Kafka :)

### Framework agnostic for the win par Jonathan Reinink

En bref, ne coder par votre librairie pour un framework en particulier. Rendez le disponible pour tous, en favorisant
l'interopérabilité.
Malheureusement, c'est resté trop "en surface" et on aurait aimé plus de "retour terrain", notamment car le conférencier est
l'auteur de la librairie de gestion d'image Glide.
Car il suffit de connaitre un peu [PHP-FIG](http://www.php-fig.org/) pour ne rien apprendre durant cette présentation.

[Les slides de Framework agnostic for the win](https://speakerdeck.com/reinink/framework-agnostic-packages-for-the-win)

### Suivre ses séries avec des API par Maxime Valette

Le créateur du célèbre site [viedemerde.fr](http://www.viedemerde.fr/) est venu parler de l'un de ses autres projets, en l'occurence BetaSeries, de
scraping (aspiration de contenus de sites web tiers), d'utilisation d'API hétéroclites et surtout de... débrouilles.
*"Librairie PHP"* très utile dans ce cas ? un simple *preg_match()* en PHP pour parser manuellement les sources.

### Un éléphant dans le monde des licornes par Matthieu Moquet

La vie du développeur chez Blablacar entre migration vers Symfony, *code legacy* et technologies nouvelles :
ElasticSearch, Cassandra, réplication des données sur différents serveurs de par le monde.
Toujours très intéressant de voir ce qu'il y a sous le capot des start-up françaises à succès.

### Insuffler la culture client dans une équipe de dev par Xavier Gorse

Par souci d'objectivité, nous nous abstriendrons de commenter cette présentation, même si nous avons trouvé ça très
bien :)
Voici le pitch :

> "L’approche agile impacte fortement la relation client avec les équipes de dev. L’approche Produit
axée autour de la valeur ajoutée des développements pour le projet a changé les relations et le fonctionnement des
équipes projets. Nous ferons le tour des grandes étapes et des écueils qui amenèrent à ce changement qui n’a qu’un seul
but : faire un projet de qualité qui réponde au besoin des utilisateurs".

Nous pouvons juste dire que nous avons eu quelques bons retours d'auditeurs disant se reconnaitre dans notre
discours. Tant mieux, nous allons dans le bon sens !

## Conclusion

Cette édition du Forum PHP a fait la part belle à l'arrivée de PHP7. D'autre part, nous retiendrons des grandes
tendances : l'organisation du travail et l'architecture logicielle. Les deux sont souvent assez liées finalement.

Le pitch des conférences et certains slides sont disponibles sur
[la page évènement Forum PHP](https://joind.in/event/view/3950) sur Joind.in.

L'organisation animée par des membres de la communauté PHP a été parfaite. Le rendez-vous est pris pour le PHP Tour qui
se déroulera les 23 et 24 mai prochain à Clermont-Ferrand.
