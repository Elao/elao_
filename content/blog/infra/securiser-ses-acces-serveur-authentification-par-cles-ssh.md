---

type:               "post"
title:              "Sécuriser ses accès serveur: Authentification par clés SSH"
date:               "2010-03-05"
publishdate:        "2010-03-05"
draft:              false

description:        "Sécuriser ses accès serveur: Authentification par clés SSH."

thumbnail:          "images/posts/thumbnails/hdd_partition.jpg"
tags:               ["ssh", "Sécurité"]
categories:         ["Infra", "Linux", "SSH"]

author:    "gfaivre"

---


Il n'est pas rare aujourd'hui que les administrateurs système soit amené à manipuler plusieurs dizaine de mots de passe, il existe, bien entendu, diversent façon de les "stocker", comme par exemple le logiciel Keepass, permettant de gérer vos différents mots de passe. Il est cependant encore plus facile d'utiliser une authentification basée sur une clé publique.

Nous allons donc voir comment utiliser une authentification par clé ssh.
Cela facilitera l'administration des serveurs mais permettra également de mettre en place certains scripts. Comme par exemple la sauvegarde des données à partir d'un autre serveur

### Pré-requis

Un serveur ssh fonctionnel et bien sur un client ssh, si vous travaillez sous linux ou sous Mac OS pas de problème vous devriez avoir le nécessaire d'installé.

Si vous ne disposez pas de ssh sur votre machine il suffit de l'installer :

```
apt-get install openssh-server openssh-client
```

Si vous êtes sous Windows, vous pouvez utiliser l'excellent <a title="puTTY" href="http://www.chiark.greenend.org.uk/~sgtatham/putty/download.html" target="_blank">puTTY</a> qui est un client SSH ainsi que PuttyGen pour la génération de clés.
Nous allons partir du principe que notre utilisateur **amartin** souhaite se connecter à notre serveur en utilisant une authentification basée sur un couple clé publique / clé privée.

Pour mettre en place notre authentification nous allons devoir agir à la fois coté serveur mais également coté client.

Imaginons que nous souhaitions que notre utilisateur amartin puisse, à partir de sa machine cliente, se connecter sur notre serveur en temps que root.

### Coté client

Le mécanisme consiste à justifier de l'identité auprès du serveur en se basant sur une paire clés publique / privée. Il faut par contre bien garder en tête que cette clé privée est le seul moyen pour le serveur d'être sûr que la connexion est bien initié par un tiers de confiance, de ce fait il est indispensable de conserver cette clé confidentielle car quiconque est en sa possession est capable de se faire passer pour notre client.
Il est toutefois possible d'ajouter une protection sur cette clé en la protégeant par une passphrase qu'il sera nécessaire de saisir lorsque l'on souhaitera utiliser notre clé.

La clé publique, comme son nom l'indique est celle qui est connue par le serveur, et qui permet de d'assurer que notre client est bien celui attendu pour l'utilisateur demandé.

Pour commencer il va nous falloir générer une paire de clés pour notre utilisateur, il existe deux principaux algorithmes asymétriques de cryptage <a href="http://fr.wikipedia.org/wiki/Digital_Signature_Algorithm">DSA</a> et <a href="http://fr.wikipedia.org/wiki/Rivest_Shamir_Adleman">RSA</a>, vous pouvez utiliser l'un ou l'autre.

Pour la petite histoire sachez juste que le **Digital Signature Algorithm**, plus connu sous le sigle **DSA**, est un algorithme de signature numérique standardisée par le NSIT aux Etats-Unis, du temps où le RSA était encore breveté.




**Générons notre paire de clé :**

```
ssh-keygen -t rsa -C 'Alexandre Martin' -f amartin
```

Cette commande crééra une clé publique ET une clé privée, (si l'option "-f" n'est pas spécifiée, par défaut dans le répertoire .ssh de votre compte utilisateur).

```
>Generating public/private rsa key pair.
Enter passphrase (empty for no passphrase):
Enter same passphrase again:
Your identification has been saved in amartin.
Your public key has been saved in amartin.pub.
The key fingerprint is:
5c:ae:c4:36:79:fd:f6:4e:64:75:77:92:26:19:3a:ff Alexandre Martin
The key's randomart image is:
+--[ RSA 2048]----+
|            .    |
|           . o . |
|          + o + =|
|       o + + o .=|
|        S o o   o|
|       o +   o o |
|        .     E .|
|             . o |
|               .o|
+-----------------+
```

Vous l’aurez compris le fichier “amartin” est notre clé privée, il faut donc faire très attention à elle et ne pas la perdre.
Il existe la possibilité de renseigner une passphrase, celle-ci devra être saisie chaque fois que vous voudrez utiliser votre clé, ceci afin d’éviter que n’importe qui ne puisse utiliser votre clé si vous vous la faites voler.

Modifier la passphrase d’une clé privée SSH

important Sécuriser ses accès serveur: Authentification par clés SSH  Il est tout à fait possible de modifier la passphrase d’une clé privée, pour se faire il suffit de saisir la commande

```
ssh-keygen -p -f amartin
Enter old passphrase:
Key has comment 'amartin'
Enter new passphrase (empty for no passphrase):
Enter same passphrase again:
Your identification has been saved with the new passphrase.
```

### Coté serveur

Une fois cette paire de clés générée, il faut déposer notre clé publique sur le serveur sur lequel nous souhaitons pouvoir nous authentifier.
Pour se faire nous récupérons notre clé publique amartin.pub (par défaut id_dsa.pub) et nous l’a déposons sur le serveur.

Une fois cela fait il faut ajouter son contenu au fichier contenant les clés autorisées à se connecter à notre serveur pour l’utilisateur voulu.

Nous allons maintenant autoriser les connexions au serveur pour notre machine cliente. Dans notre cas nous souhaitions pouvoir nous connecter en tant que root avec notre clé amartin, nous allons donc ajouter le contenu de notre clé publique dans le fichier authorized_keys2 (pour un Debian ou une Ubuntu, authorized_keys pour les autres).

Nous ajoutons donc la clé au fichier /root/.ssh/authorized_keys2:

```
cat /path/to/amartin.pub >> authorized_keys2
```

important Sécuriser ses accès serveur: Authentification par clés SSH  Il n’est pas rare, et c’est une bonne chose que la configuration des serveurs SSH implique d’avoir certains droits correctement appliqués sur le répertoire .ssh et sur le fichier authorized_keys (StrictModes activé).
Pour éviter tout problème nous allons donc restreindre l’accès à ces différents fichiers:

```
chmod 700 ~/.ssh
chmod 600 ~/.ssh/authorized_keys
```

### Connexion

Nous pouvons à présent directement nous connecter au serveur avec notre clé en utilisant la commande suivante:

```
ssh -i ~/.ssh/amartin root@serveur.tld
```

L’option “-i” indiquant vous l’aurez compris la clé privée à utiliser pour initier la transaction avec le serveur.
