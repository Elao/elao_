---
title: "Forum PHP 2026 : 221 téléphones, 7 minutes de jeu et zéro droit à l'erreur"
date: '2026-10-15'
lastModified: ~
description: "Pour l'ouverture du Forum PHP 2026, l'AFUP nous a confié un ice breaker : un jeu multijoueur en temps réel, joué par toute la salle sur téléphone. Retour sur le projet, la stack et les chiffres de la partie."
authors: [njacquot]
tableOfContent: 2
tags: [elao, afup, ForumPHP, symfony, frankenphp]
thumbnail: content/images/blog/2026/elao/afup-icebreaker/thumbnail.jpg
credits: { name: "Cecile Hamerel" }
---

Le jeudi 8 octobre, à 9 h 30, la plénière d'ouverture du **Forum PHP 2026** a commencé par un jeu multijoueur sur téléphone.
Toute la salle, répartie en deux équipes, **Volta** et **Edison**, s'est affrontée téléphone en main, sur fond
d'_Exposition universelle_.

Ce jeu, l'[AFUP](https://afup.org/home) nous a demandé de le concevoir et de le développer. Voici comment nous l'avons
construit, et ce qu'il s'est
passé pendant ces sept minutes.

## Le principe

Le joueur scanne un QR code projeté sur l'écran de la salle, son téléphone lui attribue une équipe, et la partie
s'enchaîne en quatre manches pilotées par l'animateur depuis une console d'administration :

1. **La marche** : on tape sur l'écran le plus vite possible ;
2. **La barque** : on rame, téléphone en main, grâce à l'accéléromètre ;
3. **La montgolfière** : on souffle dans le micro ;
4. **La ola** : chaque équipe se lève à son tour. Cette manche ne mesure rien, elle est purement scénique.

![L'écran joueur avant la partie : le joueur découvre son équipe, Volta, et vérifie ses quatre gestes (taper, ramer, autoriser le micro, souffler) pendant que la salle se remplit](content/images/blog/2026/elao/afup-icebreaker/ecran-joueur-intro.png)

![La console animateur : phase courante et boutons pour démarrer la partie ou passer à la manche suivante, compteurs d'appuis et de joueurs connectés par équipe, score en direct et aperçu de l'écran de salle avec son QR code](content/images/blog/2026/elao/afup-icebreaker/console-animateur.png)

Les trois premières manches rapportent un point à l'équipe gagnante. Puis, au moment d'annoncer le résultat, l'écran
projeté et tous les téléphones basculent sur une **fausse page d'exception Symfony**. Le résultat ne sera jamais donné : ce crash
lance le jeu de piste de l'AFUP, qui se poursuit sur `jeu.afup.org`.

## Une seule partie, en direct, sans reprise

C'est la contrainte qui a guidé tous nos choix : **le jeu ne se joue qu'une fois**. Pas de deuxième essai devant une
salle de plusieurs centaines de personnes si le serveur tombe à la première manche.

Nous attendions environ 500 joueurs. À 500 joueurs qui tapent frénétiquement, ça représente environ
**1250 requêtes par seconde** sur une seule route, pendant une vingtaine de secondes. Pour garder de la marge, nous
avons dimensionné et testé le jeu pour 650 joueurs.

### La stack

| Composant | Choix |
|---|---|
| Framework | [Symfony](https://symfony.com/) 7.4 LTS, PHP 8.4 |
| Serveur | [FrankenPHP](https://frankenphp.dev/), en **worker mode** |
| Temps réel | [Mercure](https://mercure.rocks/), le hub intégré à FrankenPHP |
| État partagé | [Redis](https://redis.io/), via [phpredis](https://github.com/phpredis/phpredis) en connexion persistante |
| Front | [AssetMapper](https://symfony.com/doc/current/frontend/asset_mapper.html) et [Stimulus](https://stimulus.hotwired.dev/), sans build Node |

Le **worker mode** est le cœur du dispositif : sans lui, chaque appui démarrerait un kernel Symfony complet. Avec lui,
le kernel reste en mémoire et chaque requête se contente d'incrémenter un compteur dans Redis.

Côté téléphone, on n'envoie pas une requête par appui : les gestes sont **regroupés par lots**, et la cadence d'un joueur
est plafonnée à 15 appuis par seconde pour éviter que quelques scripts bien sentis ne décident du résultat.

L'écran projeté et l'écran joueur sont deux surfaces de **la même application Symfony**, qui partagent la machine à
états et le topic Mercure. Pas d'application front séparée : elle aurait ajouté de la coordination sans rien résoudre.

![L'écran joueur pendant la première manche, la marche : deux éléphants, un par équipe, avancent à chaque appui sous un grand bouton « Tape ! »](content/images/blog/2026/elao/afup-icebreaker/ecran-joueur-marche.png)

## Tester avant le jour J

Pour s'assurer que tout fonctionnerait parfaitement le jour J, on a beaucoup testé :

- des **tests unitaires** avec [PHPUnit](https://phpunit.de/) contre un vrai Redis, pour vérifier l'atomicité des compteurs ;
- des **tests de bout en bout** avec [Playwright](https://playwright.dev/), d'abord sur mobile, sur Chromium et sur WebKit ;
- des **tests de charge** avec [k6](https://k6.io/), sur la configuration de production.

Les tests de charge ont été rassurants : sur une partie complète jouée par 650 joueurs simulés, aucune requête n'a
échoué et aucun appui n'a été perdu, avec des temps de réponse de 53,6 ms au p95, trajet internet compris.

## Le jour J, en chiffres

Le 8 octobre, je n'étais pas dans la salle : j'étais devant le monitoring, à surveiller que tout se passe bien. Une
façon un peu particulière de vivre une plénière. Pas de cris, pas d'applaudissements, mais des courbes qui s'envolent
d'un coup quand l'animateur lance la première manche, et l'attente que les temps de réponse, eux, restent bien à plat.

Et ils le sont bien restés ! Voici les chiffres de la partie, tirés de l'état du jeu conservé dans Redis et des logs d'accès du serveur :

![Les chiffres de la partie : 221 joueurs, 52 847 gestes, 12 081 requêtes dans la minute la plus chargée, 1,2 ms de traitement médian. Le détail est disponible dans la version accessible ci-dessous](content/images/blog/2026/elao/afup-icebreaker/chiffres-jour-j.png)

<details>
<summary>Version accessible</summary>
<ul>
<li>221 joueurs : 111 chez Volta, 110 chez Edison ;</li>
<li>52 847 gestes comptés, en 19 152 envois groupés ;</li>
<li>12 081 requêtes dans la minute la plus chargée, avec un pic à 520 requêtes dans la même seconde ;</li>
<li>1,2 ms de temps de traitement médian pour un appui (6,1 ms au p95).</li>
</ul>
</details>

Côté jeu, la partie a été serrée : Volta remporte la marche avec **225 appuis d'avance sur près de 28 000**, puis la
barque. Edison se rattrape sur la montgolfière. **Victoire de Volta, 2 à 1**… que la salle n'a jamais vue s'afficher,
crash oblige.

De mon côté de l'écran, le serveur ne s'est pas essoufflé une seule fois. La veille, notre dernier tir de charge simulait 650 joueurs : on était prêts
pour bien plus de monde, et on aurait aimé voir davantage de téléphones rejoindre la partie. Mais 221 joueurs qui tapent,
rament et soufflent en même temps, ça reste un sacré spectacle.

Mais des courbes ne disent pas tout. Ce n'est qu'au message de Xavier, sur place dans la salle, m'annonçant que tout
s'était bien passé, que j'ai vraiment pu souffler. Un vrai soulagement.

## Ce que j'en retiens

Un projet qui ne se joue qu'une fois, ça change la manière de travailler. Pendant des semaines, chaque choix technique
a généré la même question : _et si ça casse en direct ?_ On a testé, chargé, mesuré, et quand les tests automatisés ne
suffisaient plus, on a pris de vrais téléphones en main pour ramer et souffler dedans, encore et encore.

Et puis, il faut le dire : c'était un projet vraiment sympa à mettre en place. Développer un mini-jeu, avec des
montgolfières, des barques et une ola, ça change du quotidien. Et voir l'équipe de l'AFUP contente du rendu, ça fait
toujours plaisir.

Je suis très content d'avoir pu travailler pour l'AFUP. Le Forum PHP est un rendez-vous que je suis depuis
longtemps en tant que spectateur, et y contribuer, pour une fois, en coulisses, a été un vrai plaisir. Savoir que toute
une salle de développeurs et développeuses PHP a joué avec un projet sur lequel on a passé des semaines, c'est une
sacrée récompense.

Merci à l'AFUP pour sa confiance, et à [tilia](https://tilia.coop) qui a soutenu le jeu.
