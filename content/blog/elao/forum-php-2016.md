---
type:               "post"
title:              "Retour sur le Forum PHP 2016"
date:               "2016-11-07"
publishdate:        "2016-11-07"
draft:              false

description:        "Nous étions au Forum PHP 2016 de l'AFUP"

thumbnail:          "images/posts/thumbnails/forumphp2016-thumb.jpg"
header_img:         "images/posts/headers/forumphp2016.jpg"
tags:               ["Développement", "Web", "afup", "Conférence", "ForumPHP"]
categories:         ["conference"]
author:    "elao"
co_authors:         ["tjarrand", "bleveque", "lhoizey", "rchalas", "mcolin", "bviguier", "rhanna", "xgorse", "ndievart"]
---

Le Forum PHP 2016 de l’[AFUP](http://afup.org) s'est une nouvelle fois déroulé au Beffroi de Montrouge. [Le programme](http://event.afup.org/forum-php-2016/programme/) s'annonçait très alléchant et [nous n'avons pas été déçus](https://joind.in/event/forum-php-2016/schedule/list).

## Nous avons aimé les retours d'expérience

- La migration de Prestashop d'une architecture "custom" à Symfony.
A noter que Prestashop fait un gros effort de communication concernant la migration vers Symfony.
L'une des conséquences de cette migration est que la communauté Prestashop va un peu plus se professionnaliser.
La *core team* espère pouvoir terminer la migration d'ici deux à trois ans. Bon courage !

- [Une donnée presque parfaite](http://b-viguier.github.io/downloads/talks/ForumPhp-Une-Donne%CC%81e-Presque-Parfaite.pdf) par Benoit Viguier.
Disclaimer : Bien que travaillant pour M6Web, Benoit fait partie de l'équipe Elao, nous sommes donc forcément moins objectifs.
Retour d'expérience sur une application à fort trafic avec en prime une sorte de GraphQL fait maison à base d'API REST et du CQRS avec Cassandra en écriture et ElasticSearch en lecture.

<blockquote class="twitter-tweet" data-lang="fr"><p lang="und" dir="ltr"><a href="https://twitter.com/hashtag/simplify?src=hash">#simplify</a> <a href="https://twitter.com/hashtag/forumphp?src=hash">#forumphp</a> <a href="https://twitter.com/mattfrad">@mattfrad</a> <a href="https://t.co/e8j0A9N9KN">pic.twitter.com/e8j0A9N9KN</a></p>&mdash; Nicolas De Boose (@NicoDeBoose) <a href="https://twitter.com/NicoDeBoose/status/791945041459634176">28 octobre 2016</a></blockquote>

## Nous avons aimé des sujets très techniques

- [Pourquoi strlen("🌮") != 1 ?](https://jolicode.github.io/unicode-conf) par Damien ALEXANDRE ou comment faire un XSS avec une 🍕.
Très bonne conférence sur l'unicode qui démontre que les émojis c'est mignon mais mal gérés cela peut être dangereux.

<blockquote class="twitter-tweet" data-lang="fr"><p lang="fr" dir="ltr">&quot;Utilisez utf8mb4 comme encoding MySQL si vous ne voulez pas vous faire hacker par une part de pizza&quot; via <a href="https://twitter.com/damienalexandre">@damienalexandre</a>  <a href="https://twitter.com/hashtag/forumphp?src=hash">#forumphp</a> 😅 <a href="https://t.co/GBlkfxeySP">pic.twitter.com/GBlkfxeySP</a></p>&mdash; Matthieu Moquet (@MattKetmo) <a href="https://twitter.com/MattKetmo/status/791657024031432706">27 octobre 2016</a></blockquote>

- [MAKE is an actual task runner](https://speakerdeck.com/jubianchi/make-is-an-actual-task-runner) par Julien BIANCHI.
On utilise déjà *Make* chez Elao. Cette présentation nous en a montré davantage notamment concernant les tâches parallèles et l'exécution de tâches que si un fichier est modifié.

- [Boost up your code with Specifications](https://slides.pixelart.at/2016-10-28/forumphp/specifications/) par Patrik Karisch. Découplons notre code métier avec notamment le composant [Rulerz](https://github.com/K-Phoen/rulerz).

- [Headers HTTP: Un bouclier sur votre application](https://speakerdeck.com/romain/headers-http-un-bouclier-sur-votre-application) de Romain Neutron
ou comment protéger son site web avec les en-têtes HTTP assez méconnus mais absolument indispensables comme le *Content Security Policy (CSP)*.

- [Pattern ADR, PSR-7, actions framework-agnostic et autowiring avec Symfony](https://dunglas.fr/2016/10/slides-forum-php-create-symfony-apps-as-quickly-as-with-laravel-and-keep-your-code-framework-agnostic/) par Kévin Dunglas.
Une présentation inspirante pour mieux gérer nos contrôleurs Symfony.

- [Middlewares : Un vieux concept au coeur des nouvelles architectures](http://mnapoli.fr/presentations/forumphp-middlewares/#1) par Mathieu NAPOLI. Un middleware c'est quelque chose qui prend une `request` et qui retourne une `response`. On a retenu la leçon ! Conférence très pédagogique avec un speaker stimulant.

- [Ecrire du code PHP "framework-agnostic": aujourd'hui et demain](https://thecodingmachine.github.io/forumphp2016talk/) par David Négrier. L'un des sujets les plus pointus de ce Forum PHP, non moins intéressant.

- [Sylius eCommerce Framework](http://sylius.org/) par Paweł Jędrzejewski ou comment Sylius peut être utilisé tout ou en partie grâce aux composants.
Les tests fonctionnels de Sylius sont orientés métiers : cela nous donne de bonnes idées pour gérer nos propres tests Behat.

- Independence day par Frederic Bouchery. Composer et la Gestion sémantique de version sont indispensables aujourd'hui mais attention à trop de dépendances qui peuvent générer une catastrophe comme celle avec "leftpad" bien connu dans la communauté Javascript.

- Et puis mention spéciale pour - malheureusement - la seule conférence présentée par une femme : "Peut-on s’affranchir de SonataAdminBundle ?" par Suzanne Favot.

## Nous avons également aimé des sujets un peu plus "méthodo"

- [Affrontez la dette technique de votre projet en toute agilité](http://slides.com/maximethoonsen/agile-technical-debt-forum-php) par Maxime Thoonsen, une intéressante façon de mesurer et réduire la dette technique.

- Comment accueillir les nouveaux développeurs dans une entreprise et qu'ils soient "up" rapidement avec [Notre environnement de développement n’est plus un bizutage !](https://blog.pascal-martin.fr/public/slides-notre-environnement-de-developpement-nest-plus-un-bizutage-forum-php-2016/) par Pascal MARTIN.

- Il y avait aussi une conférence intéressante sur le travail en "remote", ses avantages, ses inconvénients et comment contourner ces derniers avec [Télétravail ? C'est bon, mangez-en !](http://raynaud.io/slides/remote) par Manuel RAYNAUD.

## Et puis...

"Allumez le feu" par Frédéric Hardy, une conférence sur... la conférence et comment un conférencier gère son trac (ou l'inverse) !
Très bonne présentation qui a pour ambition de nous donner envie de se jeter à l'eau et de transmettre le savoir en étant conférencier.

Les Lightning talks sérieux ou un peu plus débridés comme les "WTF" en PHP.

<blockquote class="twitter-tweet" data-lang="fr"><p lang="fr" dir="ltr">Si vous voulez vous faire peur pour Halloween, retrouvez mes slides sur PHP WTF ici <a href="https://t.co/OUkCpJgOAK">https://t.co/OUkCpJgOAK</a> 😨 <a href="https://twitter.com/hashtag/ForumPHP?src=hash">#ForumPHP</a></p>&mdash; Loïck P. (@pyrech) <a href="https://twitter.com/pyrech/status/791667201107460096">27 octobre 2016</a></blockquote>

Les "cliniques", ici par exemple PHP Metrics par Jean-françois Lépine :

<blockquote class="twitter-tweet" data-lang="fr"><p lang="fr" dir="ltr">Énormément de monde à la clinique phpmetrics par <a href="https://twitter.com/Halleck45">@Halleck45</a>  au <a href="https://twitter.com/hashtag/ForumPHP?src=hash">#ForumPHP</a> <a href="https://t.co/h3bDFqbbOy">pic.twitter.com/h3bDFqbbOy</a></p>&mdash; Richard HANNA (@richardhanna) <a href="https://twitter.com/richardhanna/status/791972248324337668">28 octobre 2016</a></blockquote>

Enfin, nous avons eu une table ronde concernant l'emploi des développeurs et la révélation du baromètre des salaires des développeurs PHP. Ce que l'on retient:
- Les salaires augmentent notamment grâce aux frameworks et à l'esprit devops qui ont un peu plus "industrialisé" notre profession
- L'écart du salaire moyen des développeurs PHP par rapport à celui des développeurs Java se réduit.
- L'écart du salaire entre hommes et femmes se réduit mais est toujours en moyenne plus bas pour les femmes.

Pour en savoir plus, rendez vous sur le [baromètre des salaires de l'AFUP](http://barometre.afup.org)

Le Forum PHP s'est terminé sur un "slideshow karaoké", un exercice d'improvisation vraiment pas facile. On a bien ri !

<blockquote class="twitter-tweet" data-lang="fr"><p lang="ht" dir="ltr">Slideshow karaoké au <a href="https://twitter.com/hashtag/forumphp?src=hash">#forumphp</a> <a href="https://t.co/U16PIFJLsG">pic.twitter.com/U16PIFJLsG</a></p>&mdash; Olivier Mansour (@omansour) <a href="https://twitter.com/omansour/status/792024824260468736">28 octobre 2016</a></blockquote>

Merci aux bénévoles de l'AFUP qui ont super bien géré cet évènement 👍  Vivement l'année prochaine !

<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
