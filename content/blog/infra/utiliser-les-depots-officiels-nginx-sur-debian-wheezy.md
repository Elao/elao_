---

type:               "post"
title:              "Utiliser les dépôts officiels Nginx sur Debian Wheezy"
date:               "2014-12-19"
lastModified:       ~

description:        "Comment configurer et utiliser les dépôts Nginx sur Debian Wheezy."

thumbnail:          "content/images/blog/thumbnails/server.jpg"
tags:               ["Infra", "Linux", "Debian", "Nginx"]

authors:            ["gfaivre"]

---

Bonjour à tous !

Petit mémo aujourd'hui pour pouvoir utiliser une version à jour de [**Nginx**](http://nginx.org/) sur une Debian Wheezy, celle-ci ne fournissant des paquets qu'en version 1.2.1 au moment de l'écriture de ce billet.

Attention toutes les étapes à suivre nécessitent un accès &laquo;root&raquo;

## Ajouter les sources officielles

La première étape et d'ajouter une entrée pour les dépôts officiels dans les sources d'APT.
Nous allons donc créer un fichier appelé **&laquo;nginx.list&raquo;** dans ```/etc/apt/sources.list.d```

Celui-ci doit contenir les lignes suivantes:

```
deb http://nginx.org/packages/debian/ wheezy nginx
deb-src http://nginx.org/packages/debian/ wheezy nginx
```

## Récupérer la clé PGP

Nous allons à présent ajouter la signature numérique des dépôts officiels (étape nécessaire pour que le système sache qu'il peut avoir &laquo;confiance&raquo; en ces nouveaux dépôts.)

```
curl http://nginx.org/packages/keys/nginx_signing.key | apt-key add -
```

## Prioriser l'installation des paquets (facultatif)

Il nous reste à présent à donner les instructions de priorité au gestionnaire de paquets, à savoir doit-il privilégié les paquets dits stables fournis par Debian ou doit-il utiliser les paquets officiels fournis par les dépôts Nginx.

Cette étape est facultative mais permet d'être rigoureux et d'éviter un éventuel conflit.

Pour cette étape créez un fichier supplémentaire appelé ... nginx dans ```/etc/apt/preferences.d/nginx```. Il nous servira à exprimer de manière explicite notre souhait de privilégier les paquets officiels et devra contenir les lignes suivantes:

```
Package: nginx
Pin: origin nginx.org
Pin-Priority: 900
```
## Installer Nginx

Voila nous sommes parés il ne reste plus qu'a mettre à jour notre index de paquets et installer nginx à l'aide des commandes suivantes:

```
apt-get update
apt-get install nginx
```
