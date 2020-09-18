---
type:               "post"
title:              "Authentifications multiples à partir de clés SSH"
date:               "2010-04-11"
publishdate:        "2010-04-11"
draft:              false

description:        "Authentifications multiples à partir de clés SSH."

thumbnail:          "images/posts/thumbnails/keep-calm-and-connect-to-ssh.png"
tags:               ["ssh", "Linux","Sécurité","Trucs et astuces","Tips"]
categories:         ["Infra", "Linux", "SSH"]

author:    "gfaivre"

---


La petite astuce du jour

Lorsque l'on commence à avoir pas mal de serveurs à administrer, les clés SSH se multiplient. En effet même si en règle générale l'on utilise une seule et même clé pour s'authentifier sur différentes machines, il est parfois nécessaire d'utiliser des clés différentes ...
Soit pour  se connecter avec un compte utilisateur différent sur une même machine physique, soit parce que l'on utilise plusieurs type de clé, ou encore parce que l'on veut éviter qu'une seule clé permette  d'accéder à plusieurs machine.

Vous me direz que l'on peut choisir quelle clé utilisée à l'aide de l'option -i de ssh, c'est vrai, mais comme tout bon développeur, je suis un peu fainéant, et si la machine peut le faire à ma place ... pourquoi m'ennuyer ?

Il est donc possible à l'aide du fichier `~/.ssh/config` d'indiquer au client SSH quelle clé il doit utiliser pour se connecter et à quelle machine.

**Sa syntaxe est la suivante :**

```
Host mon-serveur.domain.tld
HostName mon-serveur
User guewen
IdentityFile ~/.ssh/ma-cle-ssh

Host mon-autre-serveur.domain.tld
Hostname mon-autre-serveur
User root
IdentityFile ~/.ssh/mon-autre-cle-ssh
```

De cette façon notre client SSH utilisera systématiquement la clé attachée à un hôte particulier.
La directive permet de spécifier un nom alternatif, elle accepte également une IP.

Il est tout à fait possible de spécifier plusieurs noms d'hôtes pour la directive "Host", ceux-ci, devant dans ce cas là, être séparés par des espaces. De même un wildcard peut être renseigné dans le cas ou plusieurs machines partagent le même domaine. Très pratique lorsque l'on a un parc complet à administrer.
Il existe un nombre de directives assez impressionnantes, aussi je vous conseille pour plus d'informations de faire un petit **man ssh_config.**

**Exemple d'un host configuré avec un wildcard :**

```
Host *.domain.tld
HostName mon-alias
User guewen
IdentityFile ~/.ssh/ma-cle-ssh
```
