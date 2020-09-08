---
type:               "post"
title:              "Virtualiser son environnement de développement avec Manalize ✨"
date:               "2019-01-29"
publishdate:        "2019-01-29"
draft:              false
summary:            true
slug:               "manalize-virtualiser-son-environnement-de-developpement"
description:        "Virtualiser son environnement de développement avec Manalize ✨"
header_img:         "/images/posts/headers/manalize-virtualiser-son-environnement-de-developpement.jpg"
thumbnail:          "/images/posts/thumbnails/manalize-virtualiser-son-environnement-de-developpement.jpg"
tags:               ["manala", "virtualisation", "ansible", "vagrant",]
categories:         ["Dev"]

author_username:    "tjarrand"
---

Il y a certains changements, dans notre manière de travailler, qui facilitent tellement la vie (coucou Git) qu'une fois adoptés, on ne se voit plus revenir en arrière.

Et bien chez nous, à élao, depuis quelques années on est passé aux environnements de développement virtuels. Et on n'envisage pas de s'en passer !

## Qu'est-ce qu'un environnement de développement virtuel ?

C'est une machine virtuelle (que nous appellerons simplement VM) qui tourne sur mon ordinateur et dans laquelle vit mon application.

L'idée c'est d'avoir tout l'environnement de mon application (version de PHP, configuration Nginx, packages Node ou extensions PHP particulières) installé, configuré et fonctionnel dans cette VM et ce de manière __automatique__ (c'est à dire au lancement d'une simple commande, sans intervention manuelle).

## Les avantages de la machine virtuelle

- (Re)monter et supprimer un projet sans effort sur ma machine à l'aide d'une seule commande.
- Partager le même environnement au sein d'une équipe projet.
- Développer dans des conditions et dans un contexte proches de la prod (même moteur de base de données, même version de PHP, etc.).
- Accéder au projet localement derrière une url intelligible. Ex : http://monprojet.vm
- Pouvoir faire tourner plusieurs projets dépendant de différentes versions de PHP sur sa machine.
- Versionner tout ce contexte projet au même titre que son code source.

## Manalizer son projet

Et pour faire ça nous utilisons __[Manala](http://www.manala.io/)__, un outil petit et puissant qui permet de décrire l'environnement de notre projet sous la forme d'une configuration texte, puis de monter et lancer une VM selon cette recette.

### Pré-requis

Tout d'abord, installons l'outil de configuration en ligne de commande dédié : __manalize__

```
curl -LSs https://raw.githubusercontent.com/manala/manalize/master/installer.php | php
```

_💡 Note :_ il est aussi possible de [l'installer via git ou composer](https://github.com/manala/manalize#installation).

Ensuite, Manala s'appuie sur _Vagrant_ et _VirtualBox_ pour créer des machines virtuelles et sur _Ansible_ pour les configurer. Il nous faudra donc [installer sur notre ordinateur tous ces pré-requis](https://github.com/manala/manalize#prerequisites) :

- [PHP](http://php.net)
- [Vagrant](https://www.vagrantup.com/)
- [Vagrant Landrush](https://github.com/vagrant-landrush/landrush)
- [VirtualBox](https://www.virtualbox.org/)

_💡 Note :_ manalize est capable de nous confirmer que tous les pré-requis sont correctement installés avec la commande :

```
manalize check:requirements
```

Et voila, nous sommes prêts à manalizer notre projet web !

### Au lancement

Pour l'exemple, je vais créer un nouveau projet [Symfony](https://symfony.com/download) vide avec :

```
symfony new --full acme
cd acme
```

![](/images/posts/2019/manalize-virtualiser-son-environnement-de-developpement/empty_symfony_project.png)
![](/images/posts/2019/manalize-virtualiser-son-environnement-de-developpement/empty_symfony_project_browser.png)

Puis créer un environnement virtuel Manala pour cette application :

```
manalize setup .
```

À travers son outil de setup interactif, Manala me demande de faire un certain nombre de choix concernant les technologies nécessaires au fonctionnement de mon projet à intégrer dans la VM.

Puisque nous travaillons beaucoup avec Symfony chez élao, Manala propose une pré-configuration adaptée aux projets Symfony, embarquant entre autres PHP et Mysql. C'est ce que j'utiliserai ici :

![](/images/posts/2019/manalize-virtualiser-son-environnement-de-developpement/setup.png)

_💡 Qu'est-ce qui est créé ?_

- `Vangrantfile` : Décrit les propriétés de la machine virtuelle pour Vagrant.
- `ansible/.manalize.yml` : Fichier de configuration Manala persistant les choix faits lors du setup interactif, à partir duquel sont générés les fichiers de configuration Ansible.
- `ansible/*.yml` : Fichiers de configuration Ansible configurant le système de fichiers de la VM, Nginx, PHP et toute technologie nécessaire au fonctionnement du projet.
- `Makefile` : Liste de commandes Make servant à piloter la VM et le projet depuis la console.

_💡 Note :_ Ces fichiers font maintenant partie du code source du projet et seront versionnés et publiés dans Git. Ils pourront également évoluer et s'étoffer au fur et à mesure de la vie du projet.

### Au quotidien

#### Créer la VM

Lorsque je viens de mettre en place Manala sur mon projet, ou bien lorsque je clone un projet existant, utilisant déjà Manala, sur ma machine hôte; je dois d'abord créer la machine virtuelle :

```
make setup
```

#### Lancer la VM

Ensuite, je n'aurai plus qu'a lancer cette VM à chaque fois que je démarre mon ordinateur et que je veux développer sur ce projet :

```
vagrant up
```

Mon app est maintenant disponible à l'adresse suivante : http://acme.vm

![](/images/posts/2019/manalize-virtualiser-son-environnement-de-developpement/symfony_in_vm.png)

Mission accomplie ! 🎉

#### Entrer dans la VM

Mon projet ne tourne pas localement sur mon poste, mais dans une machine virtuelle (c'était le but).

C'est pourquoi, pour accéder à des fonctions internes comme la console Symfony, je vais devoir lancer `bin/console` _dans_ la VM; afin que le script s'execute dans le contexte de la VM, avec sa version de PHP et son système de fichiers.

Pour cela, je me connecte en SSH à la VM (comme je le ferais à un serveur web distant hébergeant mon application), grâce à la commande suivante fournie par vagrant :

```
vagrant ssh
```

Une fois connecté, je suis dans le répertoire de mon application et peux accéder à la console Symfony :

![](/images/posts/2019/manalize-virtualiser-son-environnement-de-developpement/symfony_cli_in_vm.png)

_💡 Notez le chemin du répertoire courant :_ je suis dans le système de fichiers de la VM, plus sur ma machine !

## Le tester, c'est l'adopter !

Nous utilisons [Manala](http://manala.io) depuis plusieurs années pour nos environnements de développement, l'outil est maintenant mature et stable.

Il est également totalement open-source, disponible et utilisable par tous.

Vous avez un projet qui tourne localement sur votre machine ?
Pourquoi ne pas tester un petit `manalize setup .` dès maintenant et voir votre projet tourner dans un environnement virtuel ?

## Une dernière chose ...

En 2019, une nouvelle mouture de `manalize` devrait pointer le bout de son nez, encore plus cool !
Mais ça fera l'objet d'un prochain article. 😉

---

<span>Photo par <a href="https://unsplash.com/photos/zen35Y3B834">Mark Mühlberger</a> sur <a href="https://unsplash.com">Unsplash</a></span>
