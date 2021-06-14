---
type:               "post"
title:              "AFUP Day 2021, jour 2 : le compte-rendu de l'équipe"
date:               "2021-06-11"
lastModified:       ~

description:        "Notre compte-rendu du deuxième jour de l'Afup Day, édition 2021, à distance !"
thumbnail:          "images/posts/2021/afup-day-2021/afup-day-2021.png"
tags:               ["Développement", "afup", "Conférence", "afupDay"]
categories:         ["conference"]
author:             ["bleveque"]
---

Le deuxième jour de conférence de l'AFUP Day a eu lieu le 11 juin 2021, entièrement en ligne. Nous avons pu ainsi suivre les conférences organisées par les antennes locales de Toulouse et Tours. Bravo à eux et à toute l'équipe de l'AFUP pour cette édition très bien organisée qui, malgré la distance, parvient à donner le sourire :).  
Voici quelques notes de l'équipe d'Elao qui vous propose de faire un zoom sur trois conférences :

## Comment dompter un Legacy ? Parlons smoke testing, golden master et même migration progressive, par Estelle Le Cam

Retour d'expérience sur la migration continue d'un projet initialement développée en PHP 4 et qui a subie plusieurs migrations successives (PHP 5 avec un framework maison, puis PHP7 avec un framework opensource).

Il faut des tests. Le niveau de test le plus rapide à mettre en place est le **Smoke Testing** car il permet d'exécuter toutes les urls en GET de l'application pour vérifier qu'elle renvoient bien un code de retour valide.

Avant toute migration nous devons déterminer quels sont les différents composants métiers de notre application et comment ils interagissent entre eux, idéalement à partir d'une modélisation graphique simple (type MCD).

En plus des tests existants il peut être utile/recommandé de déterminer les différents chemins critiques de l'application et de les tester via un outil comme **Cypress**.

Au cours de la migration du produit d'Estelle, son équipe a mis en place le Pattern "Strangler Pattern", c'est-à-dire que l'on rajoute une couche devant l'application legacy qui va, en fonction de l'url, appeler soit l'ancienne application, soit la nouvelle.

Nous repartons de cette présentation avec quelques pistes pour migrer plus efficacement de futurs projets.

## Authentification : peut-on se passer du mot de passe ? Par Mathieu Passenaud

- Avec le SSO on peut gérer pratiquement tout les cas
- Magic Link : envoi d’un mail et connexion via un lien reçu
- Device Flow : C’est la technique utilisée lorsque l’on veux connecter sa télé sur une application que l’on utilise déjà sur son téléphone (par exemple Disney+)
- Certaines personne se connecte exclusivement via les procédures de mot de passe oublié
- Mais il faut laisser le choix aux utilisateurs en leur proposant plusieurs solutions technique


## Code d'équipe: clé de qualité et de solidarité, par Hélène Maitre-Marchois

Dans cette présentation Hélène nous a donnée plusieurs clés afin de mieux travailler en équipe.

- **Commitment** : en faire trop ce n'est pas s'engager, mais se cramer et mettre en danger les resources de l'équipe
- **Respect** : L'équipe peut dire non quand l'organisation va trop loin, il ne faut pas hésiter à dire que les choses ne vont pas. Cela aidera l'équipe a grandir.
- **Openness** : Les solutions correspondent à un besoin à un moment précis.
- **Focus** : l'équipe a besoin d'une vision claire du produit et du calendrier.
- **Courage** : il faut sortir de sa zone de confort et se remettre en question continuellement, il faut savoir dire "Je ne sais pas".


## Alors, cette édition ? 

Malgré une conférence une nouvelle fois en ligne, l'équipe de l'AFUP a parfaitement réussi à entretenir une ambiance sympathique et la bonne humeur régnait sur le chat et dans l'espace virtuel WorkAdventure. Tout était fluide et très bien organisé. Bravo à l'équipe !
