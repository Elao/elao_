---
type:           "post"
title:          "Notre quotidien d'équipe projet auto-organisée"
date:           "2016-12-01"
publishdate:    "2016-12-01"
draft:          false

description:    "Retour d'expérience sur la vie d'une équipe projet auto-organisée. Voici ce que nous avons mis en place progressivement, nos expérimentations, nos succès et nos échecs."

thumbnail:      "images/posts/thumbnails/equipe_projet.jpg"
header_img:     "images/posts/2016/equipe-projet/equipe.jpg"
tags:           ["agile", "user stories"]
categories:     ["methodo"]

author: "rhanna"

---

Cela fait maintenant un an que nous travaillons sur un projet aux multiples facettes dont l'équipe est constituée de quatre développeurs côté Elao, un *Business Analyst* et un [*Product Owner*](https://fr.wikipedia.org/wiki/Scrum_(m%C3%A9thode)#Propri.C3.A9taire_du_produit) côté client.
Notre méthodologie est très inspirée de [Scrum](https://fr.wikipedia.org/wiki/Scrum_(m%C3%A9thode)) et de [XP](https://fr.wikipedia.org/wiki/Extreme_programming).
Notre équipe s’est construite avec l'aide de notre manager et coach Agile. Il nous épaule et nous challenge souvent pour faire émerger des améliorations.
Voici ce que nous avons mis en place progressivement, nos expérimentations, nos succès et nos échecs.

## Daily standup

C'est l'outil de synchronisation quotidienne de l'équipe et qui dorénavant démarre en [musique](https://www.youtube.com/watch?v=F69PBQ4ZyNw), c'est toujours plus fun.
Cela dure environ 5 minutes.

## Nos outils

- Des [*User Stories*](https://fr.wikipedia.org/wiki/R%C3%A9cit_utilisateur) dans un board Kanban sur Jira.
- *Confluence* pour consolider les spécifications. En vérité, c'est un échec pour l'instant. On doit s'améliorer à ce sujet.
- Board physique contenant :
    - Le numéro et dates début et fin du sprint
    - Le Sprint Backlog : les *users stories* sous forme de Post-it, son numéro Jira, l'estimation en nombre de points
    - Un Kanban avec les colonnes suivantes : Doing (en cours) / To review / Ready to demo / Demo / Done
    - Un Burn Down Chart

<div class="text-center">
    <img src="/images/posts/2016/equipe-projet/board.jpg" alt="Board projet" width="300" />
</div>

- Skype, avec le client, en direct et au quotidien, par échanges textes, par audio ou partage d'écran.
- Gitter, pour les besoins de discussions asynchrones de l'équipe en interne. Sinon on passe par des échanges oraux !
- Très peu ou pas du tout d'échanges emails. Car l'email c'est le mal.
- Le Story Mapping

## Atelier

Au début du projet, toute l'équipe s'est rendue sur le terrain pour s'imprégner du domaine d'activité de notre client.
Oui c'est "Vis ma vie".
Cela ne peut prendre qu'une demie-journée et c'est très instructif. On sait pourquoi on va développer tel ou tel outil.
C'était finalement notre premier atelier.

L'atelier se déroule avec un ou deux développeurs et le *Product Owner* durant une demie-journée.
Le but de nos ateliers est :

- d'aider le Product Owner à concevoir son produit,
- comprendre le besoin,
- décrire le besoin,
- dessiner car un dessin est plus parlant que des mots,
- challenger le PO,
- découper et prioriser les chantiers,
- trouver des solutions techniques plus simples ou moins coûteuses.

Et pour cela, il faut :

- Définir un ordre du jour (qui n'est pas toujours facile à tenir).
- Découper l'atelier en 3 parties (et entre parenthèses, la durée réelle moyenne) : le prochain sprint (50%), le sprint d'après (30%), ce qui va arriver après (20%)
Le résultat de l'atelier est ce qui est écrit sur le board physique et les Post-it. On prend une photo pour conserver le résultat.

Prochaine étape après l'atelier : écrire les Users Stories dans Jira.

On essaye d'avoir une [démarche *LEAN*](https://fr.wikipedia.org/wiki/Lean) ou au moins d'insuffler cette démarche à notre client pour la conception du produit.

De plus, chaque membre de l'équipe doit participer aux ateliers à tour de rôle ; ceci améliore l'implication et la connaissance du produit de tous les membres de l'équipe.

Cependant, nous n'avons que rarement eu à nos ateliers des utilisateurs de notre application (les parties-prenantes).
C'est quelque chose sur lequel nous avons beaucoup insisté mais il n'est pas toujours possible d'avoir un cadre idéal.

## Sprint

Le sprint a une durée fixe de 2 semaines ; durant l'été, nous avons effectué un sprint d'un mois, ce fut un échec.

Après avoir rencontré des *erreurs applicatives* durant nos démos avec le client en raison de déploiements réalisés la veille au soir, nous avons alors décidé d'arrêter le développement la veille de la cérémonie vers 16h.
Ceci afin de finir les derniers *code review* (les *stories* pas prêtes à partir en démo, tant pis !), préparer la démo du lendemain et faire une rétrospective interne.

La *Cérémonie* se déroule avec tous les membres de l'équipe, pour que tout le monde ait le même niveau d'informations et participe aux estimations.

La *Cérémonie* est composée de 3 étapes :

- Une démo de ce qui a été fait durant le dernier sprint (30 min)
- Rétrospective (30 à 45 min).
- Sprint planning : cela dure 2h dans les meilleurs des cas, 3 à 4h lorsqu'il y a un peu de travail d'atelier pour finaliser les User Stories.
Nous avons tenté plusieurs fois d'améliorer cela, notamment en préparant mieux les *stories* en atelier.
Après avoir testé les cartes à jouer, nous réalisons maintenant les estimations à la main en *Shifumi*, avec comme repères pour les complexités, la suite de Fibonacci : 1, 2, 3, 5, plus rarement 8 car cela fait utiliser les deux mains et surtout cela signifie que la *story* est trop complexe ; il faut la découper.
Nous avons donc considéré que l'estimation à la main est beaucoup plus efficace ; pas de cartes à manipuler.

Récemment, nous avons mis en place avec notre client 1/2 journée par sprint pour traiter la dette technique, dette technique que nous récoltons sous forme d'*issues* sur *github*.

La célérité d'un sprint n'était pas très bien ou pas du tout suivi au début. Un problème de sous-estimation d'un sprint avec trop de stories et trop de complexité (le fameux sprint d'un mois dont je parle plus haut) nous a fait vite recadrer cela.

Enfin, on a abandonné la définition d'un *Sprint Goal* en cours de route car cela prenait du temps d'en établir un et il ne nous apportait pas grand chose.

## Definition of done

Voici la définition de fini pour le développement d'une *User Story* que nous avons fait évoluer ensemble au fil du projet.

- Test d'acceptation de la *Story* respecté.
- Tests fonctionnels (Behat) et unitaires (Phpunit) qui passent.
- Indicateur de qualité de code [*Insight*](https://insight.sensiolabs.com/) en *platinum*.
- Indicateur de qualité de code [*Scrutinizer*](https://scrutinizer-ci.com/) sans erreur majeure.
- Code *reviewé*.
- La [*Pull Request*](https://help.github.com/articles/about-pull-requests/) sur [*Github*](https://github.com/) ne doit pas avoir de conflits avec la branche `master` (ou les résoudre dès que possible).
- Générer les migrations de la base de données s'il y a un changement apporté.

Autant vous dire tout de suite : un développeur qui ne respecte pas ce *DoD* prend cher ! :)

## Auto organisation et communication

L'équipe projet n'a pas de chef de projet.
Une personne (moi) joue plus ou moins le rôle de "scrum-master" mais c'est surtout pour organiser et faire le *daily standup*, la *rétrospective*, ...
A terme ce rôle devrait être tournant dans l'équipe.
Chaque personne de l'équipe donne son avis, peut déployer en démo ou en prod ou faire quoi que ce soit sur le projet.

Toute l'équipe participe à la conception du produit ou aux choix d'architecture logicielle notamment via les revues de design dont je parle plus bas.

Par ailleurs, il n'y a pas de personne attitrée pour discuter avec le client.
Au début, un junior demande souvent "Peux-tu dire au client de...". On lui répond systématiquement : "Dis-le-lui toi-même !".
Nous privilégions ainsi une relation directe avec le *Product Owner*.
Responsabilisation++ de chacun dans l'équipe.

Et un mot sur la transparence ?
On ne se cache rien (ou presque) : à la fois en interne ou vis à vis du client.
On informe dès qu'il y a un problème ou un changement.
Le client nous dit quand cela ne va pas ou nous fait des retours utilisateurs dès qu'il en a.

## Organisation du développement

- Revue de design
    - Au cours du projet, pour lever tout doute et éviter une mauvaise compréhension du besoin, on s'est imposé qu'une Story ne peut commencer que si une revue de design est faite par toute ou partie de l'équipe.
    Une revue de design est effectuée au tableau physique : un dessin tout simplement ou parfois plus rarement on regarde directement dans le code.
    - Le résultat peut être un dessin et une liste des tâches au tableau puis reportée sur la *PR* sur *Github*.

<div class="text-center">
    <img src="/images/posts/2016/equipe-projet/designreview.jpg" alt="Revue de design" width="300" />
</div>

- Revue de code (qu'on ne présente plus). Parfois, on explique à l'oral ce qu'on a reporté à l'écrit et ça passe mieux et beaucoup plus vite.
- Une Story ou une PR n'appartient pas à un développeur : il arrive souvent qu'une personne commence une Story et un autre la termine.
- Un développeur ne doit pas toujours faire la même chose ni être le seul dans l'équipe à maitriser une technique.
Par exemple, dans notre projet, nous avons un moteur de recherche à facettes basé sur Elasticsearch. Chaque développeur de l'équipe a participé à une partie de sa conception.
- [*Pair programming*](https://fr.wikipedia.org/wiki/Programmation_en_bin%C3%B4me) : nous permet de transmettre des connaissances ou résoudre plus facilement un problème en mutualisant les cerveaux !
- Nous avons également expérimenté le [*Mob programming*](https://en.wikipedia.org/wiki/Mob_programming). Les points positifs qu'on retient :
    - Vraiment utile lorsqu'il faut résoudre une fonctionnalité compliquée en mobilisant toute l'équipe derrière un même ordinateur.
    - Apprendre à écouter les idées de chacun.
    - Cohésion d'équipe++

## Rétrospective

La rétrospective agile est pour nous un superbe outil d'amélioration continue.
Nous faisons deux rétrospectives par sprint :

- Une rétrospective interne
    - Se dire des choses qui ne concernent pas forcément le client.
    - Se dire les choses même quand c'est négatif, mais toujours dans un esprit constructif.
    - Améliorer des processus internes ou des considérations techniques. Les améliorations qui émergent sont parfois partagées avec le client.
- Une rétrospective avec l'équipe entière, c'est à dire avec le client lors de la cérémonie de début de sprint.

<div class="text-center">
    <img src="/images/posts/2016/equipe-projet/retrospective.jpg" alt="Rétrospective" width="300" />
</div>

Le résultat de ces rétrospectives sont des axes d'améliorations reportés sur des Post-it qui sont revérifiés au prochain sprint.
Si le problème est résolu, on jette le Post-it.

Enfin, nous expérimentons au fur et à mesure des formats différents de rétrospectives pour casser la routine et faire émerger de nouvelles améliorations.

## Estimation du reste à faire

Au début du projet, l'estimation du reste à faire était toujours reléguée pour plus tard, car "on a le temps, c'est de l'agile".
Quand le temps alloué au projet a fondu, il a bien fallu s'y mettre; un outil existe pour cela : le *Story Mapping*.
Il est facile de réaliser un *Story Mapping* sous forme de Post-it pour avoir une vision long terme et faire une estimation macro pour l'atterrissage.

<div class="text-center">
    <img src="/images/posts/2016/equipe-projet/storymapping.jpg" alt="Revue de design" width="300" />
</div>

## Et maintenant ?

Et bien maintenant, nous pensons qu'il reste toujours des choses à améliorer.
Aujourd'hui l'équipe est colocalisée et nous allons très bientôt intégrer des développeurs qui seront à distance dans notre équipe projet auto-organisée.
Il faudra sans doute trouver de nouvelles solutions pour maintenir la communication et l'équilibre.

## tl;dr

\#AutoOrganisation \#Communication \#Transparence \#AméliorationContinue

Résultats :

- Équipe qui vit et travaille bien ensemble.
- Relation saine et gagnant-gagnant avec notre client.
- Satisfaction client et utilisateurs qui a un impact super positif sur l'équipe.
