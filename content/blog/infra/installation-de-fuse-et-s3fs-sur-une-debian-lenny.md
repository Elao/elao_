---
type:               "post"
title:              "Installation de FUSE et s3fs sur une Debian Lenny"
date:               "2010-01-12"
lastModified:       ~

description:        "Installation de FUSE et s3fs sur une Debian Lenny"

thumbnail:          "images/posts/thumbnails/danbo_on_drops.jpg"
tags:               ["Linux", "FUSE", "s3fs"]
categories:         ["Infra", "Linux"]

authors:            ["gfaivre"]
---

Aujourd'hui nous allons installer s3fs sur nos serveurs.
Ce paquet permet de monter des systèmes de fichiers distant de type Amazon S3. Les applications peuvent êtres multiples, le but avoué étant d'avoir la possibilité d'avoir notre "bucket" Amazon S3 disponible localement.

**Pour se faire :**

*   Installez les paquets suivants, nécessaire à la compilation des sources:

```
apt-get install build-essential libcurl4-openssl-dev libxml2-dev libfuse-dev comerr-dev libfuse2 libidn11-dev libkadm55 libkrb5-dev libldap2-dev libselinux1-dev libsepol1-dev pkg-config fuse-utils
```

Récupérer la dernière version des sources s3fs sur le <a href="http://code.google.com/p/s3fs/downloads/list">Google Code project</a> (version 118 au moment où j'écris ce billet):

```
wget http://s3fs.googlecode.com/files/s3fs-r188-source.tar.gz
```

*   Compiler les sources

```
cd s3fs
make
make install
```

Une fois ces 3 étapes accomplies le système est prêt nous allons pouvoir monter notre système de fichier.

Commençons par modifier la configuration de FUSE en dé-commentant la ligne user_allow_other, et c'est parti pour le montage du notre S3 bucket:

```
sudo mkdir -p /mnt/s3
s3fs bucketname -o accessKeyId=XXX -o secretAccessKey=YYY -o use_cache=/tmp -o allow_other /mnt/s3
```

accessKeyId: Votre "Amazon Access Key"
secretAccessKey: Votre "Secret Key"

L'option **use_cache** permet d'indiquer à s3fs de cacher les fichiers du *bucket* Amazon localement (dans /tmp en l'occurrence), l'option allow_other permet quant à elle d'autoriser des utilisateurs non root à manipuler les fichiers du montage.
N'hésitez pas à jeter un oeil au wiki du projet en cas de problème: [wiki](http://code.google.com/p/s3fs/wiki/FuseOverAmazon).

A présent tous les fichiers écrits dans /mnt/s3 seront en fait écris dans votre espace Amazon S3.

Si vous êtes comme moi et que vous n'aimez pas laisser trainer des mots de passe, clés et autre dans les lignes de commande vous pouvez conserver votre couple **

```
accessKeyId /
```

```
secretAccessKey
```

dans le fichier

```
/etc/passwd-s3fs
```

sous la forme d'une seule ligne formatée comme ceci:

```
accessKeyId:secretAccessKey
```

Nous obtenons dans ce cas la ligne de commande suivante:

```
s3fs bucketname -o use_cache=/tmp -o allow_other /mnt/s3
```

*(Le fichier /etc/passwd-s3fs est lu automatiquement)*
