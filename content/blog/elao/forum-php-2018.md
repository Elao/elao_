---
type:               "post"
title:              "Retour sur le Forum PHP 2018"
date:               "2018-11-29"
lastModified:       ~

description:        "En attendant l'AFUP Day, voici notre retour sur le Forum PHP 2018."

thumbnail:          "images/posts/thumbnails/forumphp2018-team.jpeg"
banner:             "images/posts/headers/forumphp2018-team.jpeg"
tags:               ["Développement", "Web", "afup", "Conférence", "ForumPHP"]
categories:         ["conference"]
author:             ["rhanna", "tjarrand", "aldeboissieu", "ndievart"]
---

Cette année, le Forum PHP s'est achevé sur l'annonce d'un nouvel évènement organisé par l'AFUP : les [Afup Days](https://event.afup.org/), qui auront lieu simultanément à Lille, Lyon et Rennes.
En attendant le 17 mai et la publication prochaine du programme, revenons sur le Forum PHP où une partie de l'équipe d'Elao s'est rendue.

## Nous avons aimé revenir aux fondamentaux
- <a href="https://www.youtube.com/watch?v=v3IPU3F_0JIY" target="_blank">Beyond design patterns and principles - writing good oo code</a> par Matthias Noback


Ces rappels (ou découvertes pour certains) de l'utilisation de l'objet dans son code permettent de revenir aux fondamentaux et d'ouvrir de nouvelles perspectives.

- Cessons les estimations par Frédéric Leguedois

Cette conférence nous a permis de questionner nos approches de l'agilité : les estimations sont-elles fiables ? Est-il vraiment possible de compter dessus ?
SCRUM est-elle vraiment une méthodologie agile ? Autant de questions nécessaires pour réfléchir sur nos organisations au sein d'équipes de développement.
Au fait, [on en avait déjà parlé !](https://blog.elao.com/fr/elao/mixit-2018/#cessons-les-estimations)

- <a href="https://www.youtube.com/watch?v=aXq05_mdCqE" target="_blank">Comment j’ai commencé à aimer ce qu’ils appellent « design pattern »</a> par Samuel Roze

Ou comment les patrons de conception Adapter, EventDispatcher et Decorator sont concrètement utilisés pour avoir un code propre et découplé.

- <a href="https://www.youtube.com/watch?v=7TvIIt4c8uY" target="_blank">Générateurs et Programmation Asynchrone</a> par Benoît Viguier

Oui, on peut faire de l'asynchrone en PHP, et cela peut s'avérer très pratique. Benoît et l'équipe tech de M6 Web ont open-sourcé une librairie pour simplifier l'utilisation de l'asynchrone et l'écriture des tests en PHP: [Tornado](https://github.com/M6Web/Tornado).

Si vous voulez en savoir plus, vous pouvez retrouver l'épisode du [podcast écho](https://twitter.com/podcastecho) qui lui est consacré&nbsp;:
<iframe width="100%" height="166" scrolling="no" frameborder="no" allow="autoplay" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/532349232&color=%23ff5500&auto_play=false&hide_related=true&show_comments=false&show_user=true&show_reposts=false&show_teaser=false"></iframe>

## Nous avons aimé les retours d'expérience

- <a href="https://www.youtube.com/watch?v=Cq1sR005B2E" target="_blank">Docker en prod ? Oui, avec Kubernetes !</a> par Pascal Martin

Retour d'expérience très intéressant sur le passage à Docker en prod d'une application à fort trafic (M6 Web).

- <a href="https://www.youtube.com/watch?v=av9Z7NqMxFs" target="_blank">Boostez vos applications avec HTTP2</a> par Kevin Dunglas

Vous n'êtes pas encore passés au protocol HTTP2 sur vos applications ? Kevin nous a convaincus de faire la migration en quelques arguments simples :

Activez-le en une ligne dans NGINX: `listen 443 ssl http2;`, c'est sans impact sur vos applications PHP et vous bénéficiez d'emblée d'un gain de performance de l'ordre de x2 sur le temps de vos requêtes HTTP.

En plus de ça, vous serez prêts à utiliser les nouvelles fonctionnalités HTTP2 comme le `server_push` et le nouveau composant Symfony qui lui est dédié : [Mercure](https://github.com/symfony/mercure) !

En d'autres termes : pourquoi s'en priver ?

## Nous avons adoré nous faire mener en bateau 😏

- <a href="https://www.youtube.com/watch?v=i4LTeeDZaJg" target="_blank">Développeurs de jeux vidéo : les rois de la combine</a> par Laurent Victorino

Au gré d'anecdotes sur de célèbres jeux vidéo, Laurent (des studios [Monkey Moon](http://monkeymoon.net/)) nous a montré que les développeurs sont de parfaits magiciens capables de transformer bugs et contraintes techniques en irrésistibles features.
Bref, on a adoré en apprendre plus sur ces combines ... et nous faire mener en bateau par le conférencier lui-même.

Laurent s'est preté aux questions de Richard pour un épisode du [podcast écho](https://twitter.com/podcastecho) sur le métier de développeur de jeux vidéo indépendant, si vous voulez le découvrir :
<iframe width="100%" height="166" scrolling="no" frameborder="no" allow="autoplay" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/525153006&color=%23ff5500&auto_play=false&hide_related=true&show_comments=false&show_user=true&show_reposts=false&show_teaser=false"></iframe>

## Les conférences sur lesquelles on n'est pas très objectifs puisque nos collègues les ont données 😘
- <a href="https://www.youtube.com/watch?v=LuCXFhaRXcw" target="_blank">Voyage au centre du cerveau humain ou comment manipuler des données binaires en JavaScript</a> par Thomas Jarrand

Thomas, développeur chez Elao, a partagé un retour d'expérience : charger une IRM dans le navigateur, pour les besoins d'une Université.
Tout en apportant des éléments concrets et une démo béton, Thomas a conquis son auditoire par son humour hors du commun 🤓

- <a href="https://www.youtube.com/watch?v=gW_TJ7kAu78" target="_blank">Mentorat & parcours de reconversion : comment faciliter l'apprentissage ?</a> par Éric Daspet & Anne-Laure de Boissieu

Retour d'expérience sur plus d'un an et demi de mentorat. Concrètement, qu'est-ce que cela représente de mentorer, et qu'est-ce que cela peut vous apporter ?

Anne-Laure et Éric ont également profité du Forum PHP pour enregistrer un épisode du podcast écho où ils reviennent sur ce sujet, vous pouvez le retrouver ici :
<iframe width="100%" height="166" scrolling="no" frameborder="no" allow="autoplay" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/528315084&color=%23ff5500&auto_play=false&hide_related=true&show_comments=false&show_user=true&show_reposts=false&show_teaser=false"></iframe>

- Lightning-talk : <a href="https://www.youtube.com/watch?v=9tnkySxEoKA&feature=youtu.be&t=366" target="_blank">Comment commander une application par le texte</a> par Richard Hanna

Comment peut-on exploiter le routing de Symfony pour accéder rapidement aux ressources de son back-office ?
Richard nous a montré comment y parvenir et, en plus, accéder aux paramètres.

- Lightning-talk : <a href="https://www.youtube.com/watch?v=9tnkySxEoKA&feature=youtu.be&t=366" target="_blank">La randonnée à vélo 🚲</a> par Thomas Jarrand

Et si les vacances démarraient en bas de chez vous, devant votre portail, avec un vélo et deux sacoches ?
C'est ce dont nous a parlé Thomas, qui nous a montré que la rando vélo est accessible à tous et permet de découvrir des paysages insoupçonnés de l'Hexagone !

## Pour conclure

L'organisation était au top et les conférences de très bon niveau. Que demander de plus ? Ah oui, passer un moment agréable en retrouvant des connaissances ou en rencontrant de nouveaux pairs. Mission accomplie !
Nos prochains RDV (et on trépigne d'avance) : <a href="https://event.afup.org/" target="_blank">Afup Day</a> le 17 mai (Lyon, Rennes et Lille), et <a href="https://mixitconf.org/" target="_blank">MiXiT</a> à Lyon les 23 & 24 mai 2019 !
