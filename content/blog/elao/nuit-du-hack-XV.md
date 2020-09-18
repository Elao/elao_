---
type:               "post"
title:              "Nuit du Hack XV"
date:               "2017-06-29"
publishdate:        "2017-06-29"
draft:              false

description:        "La quinzième édition de la Nuit Du Hack (#NDHXV) s'est tenue le Samedi 24 Juin 2017 en région parisienne."

thumbnail:          "images/posts/thumbnails/ndhxv.png"
header_img:         "images/posts/headers/ndhxv.jpg"
tags:               ["SysAdmin", "Sécurité", "Conférence", "Hacking"]
categories:         ["conference"]
author:    "gfaivre"
---

Une éternité.

Mes derniers échanges avec la communauté HackerzVoice (The Hackademy) remontent à plus de 10 ans.
C'est donc avec un mélange d'appréhension, de nostalgie et d'excitation que je me suis rendu à la Nuit du Hack pour son 15ème anniversaire.
Et j'y ai pris une chouette claque, disparus les hangars, les cablâges à l'arrache et les montages approximatifs, l'évènement est rôdé et réglé comme du papier à musique. On y sent un professionalisme, un sens du détail et un investissement sans faille de la part des équipes.

# Le lieu

Depuis plusieurs années c'est l'[Hotel New York Convention Centre de Disney](https://www.google.fr/maps/place/Disney's+Hotel+New+York/@48.8706966,2.7785073,17z/data=!4m12!1m6!3m5!1s0x12a5621c93628f49:0x532927254141e03!2sDisney's+Hotel+New+York!8m2!3d48.8706966!4d2.780696!3m4!1s0x12a5621c93628f49:0x532927254141e03!8m2!3d48.8706966!4d2.780696?hl=en) qui accueille l'évènement.
Vigipirate oblige, la sécurité est sur les dents, les sacs sont scannés, les poches vidées et tout le monde a droit au détecteur de métaux.
La convention s'étend sur près de 6000 m2 pour environ 2000 participants ce qui en fait l'une des principales conventions d'Europe.

Divisé en plusieurs «zones», l'évènement offre la possibilité à chacun de découvrir l'ensemble des activités qui gravitent autour du milieu du hacking.

{{< figure src="/images/posts/2017/ndhxv/map.png" alt="Carte de la conférence">}}

Le choix assumé de faire figurer conférences et sponsors dans la même salle surprend de prime abord car peu conventionnel mais l'on s'y fait rapidement.
L'avantage immédiat de ce système est que l'on peut discuter avec les exposants pendant les confs (ceux-ci n'en étant eux-mêmes pas privés) ce qui renforce d'autant la proximité et qui permet en outre de garder la main jamais très loin des goodies, bonbons et boissons ( qui a dit bière ? ).

Ce souci d'accessibilité va même jusqu'à diffuser en simultané les conférences dans la salle du WarGame et du HackDating.
En revanche il est vrai que lorsque l'on s'éloigne un peu de la scène, il est plus difficile d'entendre correctement le speaker en raison du brouhaha ambiant.

# L'ambiance

L'ambiance n'a guère changé. On ressent en effet une bienveillance générale de l'ensemble des participants et une vraie volonté de partage tout en gardant cet aspect de défiance dès que l'on rentre un peu trop dans le vif du sujet.
On sent également la professionalisation du métier et l'intérêt, plus qu'identifié, des différents acteurs du marché. Les différents exposants étant clairement à la recherche de profils à recruter et ne s'en cachant pas.

{{< instagram BVu5JDdlCKi >}}

# Les conférences

J'ai malheureusement raté la keynote de Guillaume aka [free\_man\_](https://twitter.com/free_man_)), trop occupé à échanger avec l'ami Korben sur son projet «[Yes we hack](https://yeswehack.com)», mais j'ai réussi à suivre le reste des conférences malgré la diversité des ateliers, des exposants, des challenges et des personnes. Mention spéciale à Gael aka [RatZillaS](https://twitter.com/RatZillaS) qui a fait le show avec ses Tesla et [Virtualabs](https://twitter.com/virtualabs), drôle, utile et très pédagogue.

![Benjamin Brown](/images/posts/2017/ndhxv/bbrown.jpg)

Après un rapide passage en revue de l'histoire du «Hacking» (Au sens large) et du «Darknet» par [Benjamin Brown](https://twitter.com/ajnachakra) qui faisait office de mise en bouche, les conférences s'enchainent à un rythme assez soutenu, laissant peu de place aux questions, mais les speakers étant très accessibles il est aisé d'approfondir le sujet hors scène.

Beaucoup de conférences plus ou moins techniques s'enchainent ensuite. Parmi elles je retiendrai:

### Make hardware great again ! (Damien CAUQUIL aka [Virtualabs](https://twitter.com/virtualabs))

Présentation pleine d'entrain et d'humour qui fait un tour rapide de tout ce qu'il est possible de «hacker» chez soi, la télécommande, la clé média verrouillée de son FAI préféré, d'anciens objets électroniques jugés obsolètes mais qui peuvent trouver une seconde vie voire d'autre utilité au prix d'un petit effort de «reverse engineering» et de re-programmation.

![Damien CAUQUIL](/images/posts/2017/ndhxv/virtualabs.jpg)

### NTFS, Forensics, malwares and vulnerabilities (Stéfan LE BERRE aka [HEURS](https://twitter.com/heurs))

Point intéressant sur le fonctionnement du système de fichier NTFS, la façon dont il stocke les données et surtout comment exploiter sa structure de stockage pour corrompre, récupérer et/ou crypter les données présentes sur le disque. C'est à la fois surprenant et intéressant de voir que même un système de fichier dont la première version remonte à près de 25 ans est toujours exploitable et faillible (Souvent pour des raisons de rétro compatibilité). On y apprend notamment comment certains «malwares» s'y prennent pour corrompre les données.

![NTFS, Forensics, malwares and vulnerabilities](/images/posts/2017/ndhxv/ntfs.jpg)

### The new sheriff in town is a machine ([Jennifer LYNCH](https://twitter.com/lynch_jen))

Avocat de l'EFF et spécialiste de la vie privée et des problèmes de libertés civiles, Jennifer nous présente un sujet à la limite de l'anticipation... ou pas. Conférence intéressante sur l'exploitation du «big data» par les services de police et de renseignements pour notamment faire de l'analyse prédictive de crimes et d'actes terroristes. En effet certaines villes américaines ont déjà mis en place un système de reconnaissance faciale «à la volée» derrière le système de caméra de certaines villes et commencent à pousser le vice jusqu'à «estimer» à quelle point une personne est «susceptible» de commettre un crime.
Amateurs de Minority Report bienvenus ;)

![Jennifer LYNCH](/images/posts/2017/ndhxv/jlynch.jpg)

### How to fool antivirus software? (Baptiste DAVID)

Conférence d'assez haute volée technique, elle a pour but de présenter les différentes techniques permettant de passer outre les protections des antivirus (dans leur très grande majorité), le tout de manière élégante. On y apprend que les malwares ont encore de beaux jours devant eux, les techniques présentées permettant d'intervenir à quasiment tous les niveaux, kernel ou user-mode.
L'exploitation présentée permettant de désactiver complètement les filtres des antivirus.

![Baptiste DAVID](/images/posts/2017/ndhxv/howtofool.jpg)

# Les exposants/sponsors - Le HackDating

Beaucoup de jolies références parmis les sponsors / exposants de cette édition, on y retrouve des noms bien connus parmi lesquels Qwant, Deloitte, Orange - Cyberdefense, OVH, Thales et bien d'autres. Mais ce qui est frappant c'est la présence (en force et sans jeu de mot), du ministère de la défense ([plus de 3000 postes d'ouverts](http://www.01net.com/actualites/hackers-engagez-vous-l-armee-francaise-recrute-descombattants-numeriques-1195388.html)) avec des polos estampillés «Combattants numériques» et de l'ANSSI, preuve s'il en est que le gouvernement prend de plus en plus au sérieux la problématique de la cyber-sécurité.
A noter également la visite du secrétaire d'État au numérique Mounir Mahjoubi.

{{< tweet 878662496160272384 >}}

Énormément d'efforts sont consentis dans le recrutement chacun essayant de «draguer» au mieux et ce, sur beaucoup de domaines différents. Autant vous dire que l'on est pas en reste en termes de goodies ;)

{{< figure src="/images/posts/2017/ndhxv/goodies.jpg" alt="Goodies NDH">}}

# Les autres bonnes idées

### La NDH Kids

Oui à la NDH les enfants ne sont pas oubliés, il est donc possible pour eux de s'initier à tout un tas d'activités que pas mal d'entres nous auraient bien voulu avoir à porté de main gamin, parmi elles:

- Initiation au «Lock Picking», un des ateliers qui aura sans doute eu le plus de succès tant chez les Kids que chez les «grands».
- Initiation à la Cryptologie, j'avoue avoir passé un peu de temps à reparcourir l'histoire de la crypto à partir des présentoirs de la «WarGame».
- Initiation à la programmation
- Initiation à la chimie, avec notamment l'exploitation de réactions «explosives» permettant la création de volcans

Et enfin l'électrolab qui proposait également un stand en salle de conférence avec plein de matériel électronique à acheter. Les kids auront eu l'occasion de se faire les dents sur les bases de l'électronique, la conception de circuits, de badges...

<img src="/images/posts/2017/ndhxv/ndh_kids.jpg" alt="NDH Kids">

# Les workshops

Divers et variés les workshops permettent de s'initier tranquillement (de 20h à 6h) à tout un tas de choses. De la création de son propre réseau Internet avec l'Electrolab à l'explication, pour les amateurs de drones, du pourquoi des «no fly zones» en passant par le crochetage de serrures.

{{< instagram BHwgB9MjC5v >}}

# La WarGame

La WarGame est un concours ouvert à tous, où chacun peut essayer de résoudre différents «challenges», chaque challenge rapportant un certain nombre de points permettant de progresser au classement général de l'épreuve.

{{< figure src="/images/posts/2017/ndhxv/wargame.jpg" alt="wargame" title="Les participants à la WarGame" >}}

# Le CTF et Le SpyingChallenge

Ces deux évènements regroupent un aspect particulier de la Nuit du Hack, l'aspect «compétition».
Et on ne rigole pas avec la compet', certains participants n'hésiteront pas à fermer les «laptops» en cas de coup d'oeil furtif ou si l'on se rapproche un peu trop d'eux.

Le principe du CTF est d'opposer les deux équipes qualifiées pour la finale, chacune d'entre elles devant à la fois défendre son infrastructure et attaquer celle de l'équipe adverse.

Quant au «Spying Challenge» il fait rentrer en jeu la partie sociale du hacking puisque dans ce cas de figure les différents participants se voient confier un dossier et ont pour mission de récupérer un maximum d'information sur une «cible» désignée.

{{< instagram BVugEXzFNcq >}}

# Easter Egg

Au coin d'un couloir on pouvait également croiser un étrange personnage, entouré de machines plus ou moins ésotériques. [Mr Louis POUZIN](https://fr.wikipedia.org/wiki/Louis_Pouzin) en personne.
Le stand de l'[ARCSI](https://www.arcsi.fr/) proposant également un historique très complet de la cryptographie.

{{< tweet 878615975075221506 >}}

# Conclusion

On remarque d'emblée un public relativement jeune, beaucoup d'étudiants ou de jeunes actifs, mais la curiosité, la culture de la recherche et du challenge sont toujours là. Encore mieux elle est entretenue et exacerbée par l'aspect compétitif des événements comme le CTF ou la WarGame.

J'avais l'image de la NDH à ses débuts lorsqu'elle se déroulait dans un «hangard» avec 10 / 15 personnes. De cette période je garde surtout le souvenir d'un groupe et surtout de Paulo et sa quête du «mieux» dans chacun des aspects de sa vie. Il était comme ça le Paulo, même le mieux ça ne suffisait pas, mais je crois qu'il aurait été fier de voir ce qu'est devenu la NDH aujourd'hui.

Bref, j'ai passé un bon week-end, croisé des visages connus et d'autre moins connus et au final repris contact avec une communauté qui m'avait, je m'en rends compte, vraiment manqué.
