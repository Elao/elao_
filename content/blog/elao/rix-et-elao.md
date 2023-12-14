---
type:               "post"
title:              "Rix et Elao"
date:               "2023-11-07"
lastModified:       ~
tableOfContent:     false

description:        "RIX, structure spécialisée en hébergement et infrastructure, est historiquement liée à Elao. Désormais partenaires de longue date, nous avons souhaité mettre en lumière notre manière de travailler ensemble."

thumbnail:          "content/images/blog/thumbnails/rix-et-elao.jpg"
tags:               ["Développement", "Hébergement", "Manala"]
authors:            ["equentin", "mcolin", "llapeyre"]
tweetId:            ""
---

Comment fonctionne un projet qui passe entre nos mains et celles de RIX ? Comment les développeurs de chacune des structures s'organisent pour avancer sur un même projet ? Quels sont nos outils ? Et surtout, quels avantages pouvons-nous retirer de cette relation de confiance ? 

Pour répondre à ces questions, nous avons demandé à [Maxime :max-happy:](../../member/mcolin.yaml) , développeur chez Elao, ainsi qu’à [Lod :lod-happy:](../../member/llapeyre.yaml), sysadmin chez RIX, de se prêter au jeu de l’interview croisée !

## Présentation

**Eva** : Je vous propose de présenter rapidement les deux activités, du côté d’Elao et de Rix.

**Maxime** : Côté Elao, nous faisons du **développement d’applications web et mobile sur mesure**, spécialement sur du [**Symfony**](../../term/symfony.md) côté back et du [**React**](../../term/react.md) côté front. On fait également un petit peu de [**Vue JS**](../../term/vue-js.md) mais de moins en moins, c’est en fonction des opportunités. Donc notre spécialité, ce sont les applications sur mesure pour les **entreprises** qui ont des processus gérés à l'ancienne et qui souhaitent les moderniser, mais également les **start-ups** aux idées révolutionnaires.

**Lod** : Chez RIX, nous faisons de l’**hébergement d’application web**, et c’est là qu’on commence à voir le lien avec Elao. Une grosse partie de notre activité repose sur **de l’accompagnement et du conseil**, en particulier **devops**. Ça peut être du conseil sur le choix de l’**infrastructure** et sa mise en place, les limitations des différentes technos ou la refonte de l’existant, surtout en ce moment. On a pas mal de clients qui ont déjà une infra et qui voient les limites atteintes par le développement de leur business. On fait aussi pas mal de **Kubernetes** et sinon de l’**hébergement** assez classique, de la machine virtuelle chez un hébergeur Cloud. On travaille essentiellement avec **OVH** car on essaie de travailler avec des solutions françaises, mais on n’est pas fermés, on a également des clients qui utilisent **AWS** et **Google**.

## La collaboration

<blockquote>Tous nos scripts de déploiement fonctionnent chez Rix, et ce, sans trop de configuration</blockquote>

**Eva** : Dans quels cas Elao va faire appel à RIX ?

**Maxime** : Quand nous faisons un projet côté Elao, nous essayons systématiquement de l’héberger chez Rix. C’est plus facile pour nous d’héberger chez Rix en tant que développeur, parce que tous nos **scripts de déploiement** fonctionnent chez Rix, et ce, sans trop de configuration. 

**Lod** : En fait surtout ce qui est surtout intéressant à souligner, c’est que tout ce qui est outil de développement chez Elao, c’est RIX qui s’en charge. C’est RIX qui le maintient et qui le crée.

**Maxime** : Et les scripts qui créent nos environnements de développement sont calqués sur les environnements de production qui sont développés et maintenus par RIX. Donc nous avons davantage de certitude que quelque chose que l’on a développé sur notre stack de développement va fonctionner sur notre stack de prod sans souci.

**Maxime** : Et typiquement en tant que développeurs, quand nous devons travailler avec un autre hébergeur, car nous avons des clients qui nous disent qu’ils ont déjà leur hébergeur ou qui hébergent déjà en interne, nous sommes obligés de déployer sur des serveurs qui ne sont pas ceux de RIX. Et c’est tout le temps plus compliqué car quand on va déployer une application en prod, il faut que la bonne version de PHP soit installée, avec les bonnes extensions qui vont bien, qu’on ait les bonnes permissions dans les bons répertoires, qu’on ait la base de données avec la bonne version qu’on a utilisée, etc. ll y a plein de façons différentes de déployer une application. Avec nos scripts de déploiement, il faut certains répertoires avec certaines permissions et certaines dépendances. Et si tout ça n’est pas là et bien… ça ne fonctionne pas ! Généralement ce qu’il se passe sur un hébergeur tiers, c’est qu’on peut avoir de la perte d’information : comme certaines choses manquent, il va y avoir de nombreux échanges entre l’hébergeur et nous et c’est à ce moment que les infos se perdent. Alors qu’avec RIX, une fois que le serveur est installé, c’est assez rare qu’il y ait des manques ou des oublis ou si c’est le cas, c’est résolu en quelques minutes. On va dire qu’avec RIX, dans la journée, le serveur est prêt et on peut déployer dessus. Avec un hébergeur tiers, le temps que l’on fasse des échanges, ça va nous prendre 4, 6, voire 10 jours parfois. 

**Eva** : De ce que je comprends, travailler avec RIX nous fait gagner du temps, car on connaît l’outil et on a l’habitude de travailler ensemble. 

**Maxime** : C’est ça.

**Lod** : Et le fait que nous ayons des outils qui sont uniformisés. En fait, quand Maxime me demande une nouvelle machine, il m’envoie son fichier de développement qui définit son environnement de développement et j’ai juste à le prendre tel quel dans mon code qui va déployer l'infrastructure pour que ça fonctionne. On a la même techno Ansible derrière et c'est la même syntaxe. De fait, j'ai moins d’allers-retours à faire, car il me fournit déjà ce avec quoi il travaille depuis des semaines et je n’ai plus qu’à me baser dessus. 

**Maxime** : Et il y a aussi le fait que chez RIX et Elao, nous faisons beaucoup d'applications Symfony qui se configurent quasi tout le temps de la même façon. Chez RIX, vous connaissez la manière de configurer Symfony là où, avec un autre hébergeur, il se peut qu’on ait à expliquer précisément tout ce que l’on veut, ce qui prend énormément de temps. L’avantage d’avoir un partenariat comme celui que nous avons avec RIX, au sein duquel la manière de faire ne change pas, et où l’on a des projets avec une certaine norme, un standard, une continuité, c’est qu’il est beaucoup plus rapide de mettre en prod et de faire vivre cette dernière.

**Lod** : Et pour nous, c’est aussi plus simple, car nous n'avons pas à expliquer ce que l’on fait, notre manière de fonctionner, les standards, car Elao les connaît. Et c’est même plus que ça en fait, car nous les avons construits ensemble et du coup, nous gagnons nous aussi du temps. Et ça nous va bien. Quand nous travaillons avec des clients externes, il y a plus d’allers-retours à faire pour expliquer notre fonctionnement. Nous n’avons pas à passer par cette étape avec Elao, mais elle fait partie intégrante de notre métier et nous accompagnons avec plaisir nos clients, quand cela est nécessaire.

**Eva** : La passation, la transmission se fait très rapidement.

**Maxime** : Oui, notre environnement de développement est construit automatiquement, on a des scripts pour le construire avec un fichier qui référence tout ce dont nous avons besoin. Et le formalisme de ce fichier, c’est le même qui est utilisé par RIX pour automatiser la création des serveurs. Lod a donc toutes les infos, il n’a pas besoin de chercher quoi que ce soit et ses outils vont pouvoir lire sans problème les éléments. Donc c’est à la fois **un gain de temps côté compréhension** et **un gain de temps côté configuration**.

**Eva** : Et les outils que vous utilisez, c’est essentiellement Manala ou est-ce qu’il y en a d’autres ?

**Lod** : Manala c’est la sur-couche Ansible. Après niveau outils de communication, on est avantagés par Slack…

**Maxime** : On a un Slack partagé.

**Lod** : Même si on a des Slack avec d’autres clients. C’est sur ce Slack qu’Elao nous demande quand ils ont besoin d’une machine, ils nous transmettent le fichier sur Github et c’est fait.

**Maxime** : La chose qui simplifie beaucoup la relation entre l’hébergement et le développement, c’est que nous travaillons depuis longtemps avec des sysadmin au sein d’Elao. Certains sysadmin sont des transfuges de développeurs. De fait, les sysadmin chez RIX ont des expériences de développeurs. Comme les développeurs chez Elao sont beaucoup en communication avec des sysadmin et que nous avons co-construit la stack, nous avons une certaine culture sysadmin et devops. Donc quand nous avons une demande ou un souci côté dev, nous savons comment communiquer cela au sysadmin. On parle un peu plus la même langue. 

**Lod** : C’est aussi le privilège de bien connaître tes clients, tes prestataires. Tu sais que leur socle est commun et tu peux directement aborder le sujet sans passer par une phase plus précise d'explication fonctionnelle. Là, je ne dis pas que ça n’arrive pas, et l’inverse est vrai en tant que développeur, mais comme on se connait bien, on sait un peu plus vers quoi tendre.

**Maxime** : Oui, et de notre côté, en tant que développeurs, puisqu’on s’y connaît un peu plus en hébergement, on est capables de remarquer quand il manque quelque chose ou de comprendre d’où peut venir un bug. 

**Lod** : Je sais qu’Elao ne me demandera pas pourquoi quelque chose ne fonctionne pas si l’erreur ne vient pas de chez nous.

**Eva** : J'imagine que vous avez des clients qui n’ont pas les mêmes bagages techniques.

**Lod** : Oui, bien-sûr !

**Eva** : Je vois. Là nous étions dans le schéma où Elao qui fait une demande à RIX. Est-ce qu’il peut arriver que RIX ait une demande à faire côté Elao ?

<blockquote class="blockquote-secondary">Nous monitorons les infra et si on voit qu’il y a des soucis, à ce moment-là on les remonte à Elao.</blockquote>

**Lod** : Honnêtement, c’est rare. Quand il y a des choses qui viennent de RIX et qui vont vers Elao, c’est plus du support. Nous monitorons les infra et si on voit qu’il y a des soucis, à ce moment-là nous les remontons à Elao. Par exemple si une page est très lente, si le site a trop grossi, s’il est désormais trop lent et qu’il faut mettre une autre machine, nous allons faire des changements de notre côté et indiquer à Elao ce que nous avons changé pour qu’ils puissent le modifier également. C'est ça le cœur de notre métier : on fait des vérifications côté hébergement pour pouvoir conseiller les développeurs sur les éléments problématiques et la façon de les régler. De mon côté, mes demandes seront plus sur des outils. Par exemple, sur le projet [Musique & Music](etudes-de-cas/musique-music), ils utilisent un outil qui s'appelle Audio Waveform pour générer fichiers audio. Et ça, c’est quelque chose qui n’était pas dispo sur la version Linux que l’on utilise. J’ai fini par packager quelque chose moi-même au sein de Manala. Puis j’ai vu qu’il y avait un package permettant de faire ça qui est sorti. Donc typiquement, ma demande auprès d’Elao, c’est de voir si vous pouvez tester le code avec cette nouvelle version comme ça on s’affranchit de maintenir ça de notre côté et vous, vous êtes un peu plus à jour. Je dirais donc que s’il doit y avoir des demandes qui émanent de RIX vers Elao, c’est plutôt pour des améliorations.

**Eva** : Ça marche. Je pense qu’on a plutôt bien cerné la relation entre RIX et Elao. Est-ce que vous voulez rajouter quelque chose ?

**Maxime** : Moi je résumerais juste le tout en disant que le fait de fréquenter RIX, de les avoir sur place, de travailler souvent avec eux, et d’avoir les mêmes standards, les mêmes façons de faire, ça fluidifie énormément le travail. 

**Lod** : Oui, il y a une grosse **relation de confiance entre RIX et Elao**, avec un **historique commun**.

**Maxime** : Nous gagnons beaucoup en temps et j’imagine, en qualité aussi. Comme nous fréquentons beaucoup RIX, nous avons intégré chacun de notre côté le travail et les connaissances de l’autre ce qui nous permet de gagner en efficacité. Et nos clients sont gagnants eux aussi !

**Eva** : Tout le monde est gagnant. Vous, car vous ne perdez pas de temps, et le client car il a les éléments dont il a besoin beaucoup plus rapidement. 

**Maxime** : C’est ça, nous pouvons réagir beaucoup plus vite à un bug ou à un souci. Nous déterminons plus rapidement d’où vient le souci, nous ne perdons pas du temps à faire des allers-retours entre les développeurs et l’hébergement comme ça peut être le cas lorsque c’est géré par deux prestataires totalement différents. 

**Lod** : Je pense également que l’outil Manala est vraiment fondamental dans ce processus.

## Manala

**Eva** : Peux-tu re-définir ce qu’est Manala ?

**Lod** : À la base, c’est une suite de rôles Ansible, qui est lui-même un outil qui permet de faire de l’infrastructure “_as code_” : tu décris l’état de ta machine en texte et tu donnes cela à Ansible, pour que lui vérifie que la machine correspond à l’état que tu lui as demandé. Si cela ne correspond pas, il change pour que ça corresponde. Ensuite, nous ajoutons à ça un outil de templating. On lui fournit les templates créés, et tout cela est Open Source. Tu as un seul fichier dans lequel tu vas mettre toutes tes définitions et Manala va prendre les valeurs et les intégrer aux bons endroits. De fait, tu as très peu de configuration à faire pour avoir toute une stack de développement qui fonctionne. 

**Maxime** : Ça simplifie beaucoup la relation entre développeur et hébergeur, car Manala va apporter une sorte de sur-couche où l’on va juste indiquer des éléments basiques à la portée de tous les développeurs. Et la commande de Manala va créer tous les fichiers de configuration complexes par rapport à ce fichier qui est simple. Grâce à ça, nous n’avons pas besoin de maintenir ces fichiers complexes. 

## Le mot de la fin

**Lod** : Pour résumer, les pierres angulaires d’une collaboration réussie entre hébergeurs et développeurs, c’est de bien se connaître et d’avoir les bons outils, des outils communs, une culture commune et une envie d’avoir des standards sur lesquels on ne se repose pas forcément, car le monde du numérique évolue très rapidement et on va vite mettre à jour nos outils. 

**Maxime** : Et on évolue ensemble !

Vous l’aurez donc compris, la relation entre RIX et Elao permet à chacune des parties de fluidifier son travail au quotidien, de gagner en efficacité et surtout d’évoluer ensemble vers de nouveaux outils et process. Côté client, cette collaboration représente un gain de temps considérable, et l’assurance qu’aucune information ne sera perdue dans les échanges entre développeurs et hébergeur.

Pour clore cette discussion et approfondir le sujet, on vous invite à aller en apprendre plus sur notre [service d’infrastructure et d’hébergement](/nos-services/hebergement).
