---
type:               "post"
title:              "Créer une autorité de certification et des certificats SSL auto-signés"
date:               "2010-10-05"
publishdate:        "2010-10-05"
draft:              false
slug:               "creer-une-autorite-de-certification-et-des-certificats-ssl-auto-signes"
description:        "Créer une autorité de certification et des certificats SSL auto-signés"

thumbnail:          "/images/posts/thumbnails/matryoshka.jpg"
tags:               ["Linux", "Sécurité"]
categories:         ["Linux", "Infra"]

author_username:    "gfaivre"
---

Bonjour à tous !

Petit memo aujourd'hui pour les admin sys

Nous allons voir comment créer sa propre autorité de certification et créer ses propres certificats SSL auto-signés, toujours très utiles lorsque l'on a des problématiques de connexion sécurisée.<!--more-->
L'ensemble de ces manipulations ont été réalisées sur des serveurs Linux / Debian Lenny.

### Créer son propre CA (Certificate Authority)

Nous allons commencer par créer une clé privée

```bash

root@my_server:~# openssl genrsa -des3 -out elao-ca.key 2048
Generating RSA private key, 2048 bit long modulus
.........................................................................................+++
..+++
e is 65537 (0x10001)
Enter pass phrase for elao-ca.key:
```


La protection par une "pass phrase" de votre clé est essentielle ici, en effet cette clé nous servira à signer **tous** les certificats de notre organisation, sa confidentialité doit donc être **primordiale**. Il va sans dire que le stockage de cette clé, dans l'idéal, doit être fait sur un support amovible  (type clé USB), et ne doit pas être conservée sur une machine accessible "publiquement" (j'entend ici directement connectée à internet).

**Note :**

Il est possible d'améliorer la qualité de la clé en fournissant comme source de données aléatoires un ou plusieurs fichiers avec l'option -rand, par exemple :

```
-rand /var/log/messages
```

Par défaut openssl utilisera /dev/urandom s'il existe, dans le cas contraire /dev/random, la source de données aléatoire est très importante pour la qualité du cryptage, si votre système ne dispose d'aucun de ces deux fichiers vous pouvez installer un générateur de nombre aléatoire comme [egd][1].

Maintenant que nous disposons d'une clé nous allons procéder à la création de notre certificat CA privé

```
openssl req -new -x509 -days 3650 -key elao-ca.key -out elao-ca.crt
Enter pass phrase for elao-ca.key:
```

Le contenu de notre certificat est vérifiable grâce à la commande suivante :

```
openssl x509 -in elao-ca.crt -text -noout
```

### Créer une clé et un certificat pour le serveur

Maintenant que nous disposons de notre autorité de certification nous allons pouvoir créer et signer les certificats à destination de notre serveur.

```
openssl genrsa -des3 -out elao-server.key 1024
```

Comme vous le constatez nous restons sur un cryptage à 1024 bits, en effet il est risqué d'aller au delà car certains navigateurs ne supportent pas les cryptages supérieurs.

La prochaine étape consiste à générer une requête de signature de certificat, appelée comme ceci car elle est normalement transmise à une autorité de certification (Certificate Authority ou CA) pour signature.
Un certificat n’est ni plus, ni moins qu’une clé qui a été signée par une autorité autorisée garantissant ainsi que celle-ci est valide est correspond bien à la bonne entité.

**Note:**

Une autorité de certification est une entité autorisée à signer les certificats SSL, elles ne sont que quelques unes à faire autorité. Lorsqu’un certificat est signé par l’une d’entre elles, les navigateurs accepterons automatiquement le certificat comme étant valide, si un certificat est signé par une autorité de certification absente de la liste des autorités fiables du navigateur celui-ci renverra un avertissement à l’utilisateur lui précisant que le certificat a été signé par une autorité non reconnue et lui demandera de confirmer qu’il accepte bien le certificat.

Ce qui se traduit sous Firefox par exemple par ce type d’écran:

<img title="firefox-ssl" src="/blog/medias/creer-une-autorite-de-certification-et-des-certificats-ssl-auto-signes/firefox-ssl.png" alt="firefox ssl Créer une autorité de certification et des certificats SSL auto signés" width="700" height="386" />

Au cours de cette étape plusieurs questions vous serons posées et se présenteront ainsi, vos réponses seront utilisées par le certificat et utilisées par les navigateurs afin de vérifier que le certificat provient d’une source sûre. Les utilisateurs seront en mesure d’inspecter ces détails à n’importe quel moment à partir de l’instant où ils se connectent à votre site.

```
openssl req -new -key elao-server.key -out elao-server.csr
```

... qui devrait aboutir sur:

```bash

Enter pass phrase for elao-server.tld.key:
You are about to be asked to enter information that will be incorporated into your certificate request.
What you are about to enter is what is called a Distinguished Name or a DN.
There are quite a few fields but you can leave some blank
For some fields there will be a default value,
If you enter '.', the field will be left blank.
-----
Country Name (2 letter code) [AU]:FR
State or Province Name (full name) [Some-State]:Rhone-Alpes
Locality Name (eg, city) []:LYON
Organization Name (eg, company) [Internet Widgits Pty Ltd]:ELAO
Organizational Unit Name (eg, section) []:IT
Common Name (eg, YOUR name) []:my-server.my-domain.tld
Email Address []:my-contact-adress@my-domain.tld
Enter pass phrase for elao-server.key:
You are about to be asked to enter information that will be incorporatedinto your certificate request.
What you are about to enter is what is called a Distinguished Name or a DN.There are quite a few fields but you can leave some blankFor some fields there will be a default value,If you enter '.', the field will be left blank.
-----
Country Name (2 letter code) [AU]:FR
State or Province Name (full name) [Some-State]:Rhone-Alpes
Locality Name (eg, city) []:LYON
Organization Name (eg, company) [Internet Widgits Pty Ltd]:ELAO
Organizational Unit Name (eg, section) []:IT
Common Name (eg, YOUR name) []:my-server.my-domain.tld
Email Address []:my-adress.my-domain.tld
```


Attention lorsque vous générez cette requête le common name doit correspondre EXACTEMENT au FQDN de votre serveur !

```
openssl x509 -req -in elao-server.csr -out elao-server.crt -sha1 -CA elao-ca.crt -CAkey elao-ca.key -CAcreateserial -days 3650
```

Un petit coup d’oeil pour vérifier que tout est ok

```
openssl x509 -in elao-server.crt -text -noout
```

Attention cependant lorsque vous allez utiliser votre certificat avec Apache typiquement, à chaque redémarrage vous devrez saisir la “pass phrase” du certificat, ce qui peut être contraignant dans le cas d’un “reboot” malheureux de la machine (Apache planté jusqu’à intervention de l’admin).
Pour palier ce problème nous allons générer une clé non sécurisée, oui je sais, c’est mal, mais il faut parfois choisir entre la sécurité et la praticité.

```
openssl rsa -in elao-server.key -out elao-server.key.insecure
```

Au niveau de notre configuration apache nous utiliserons ensuite, par exemple, notre certificat et notre clé de cette façon:

```
SSLEngine On
SSLCertificateFile      /path_to_my_certs/my-server.my-domain.tld.crt
SSLCertificateKeyFile   /path_to_my_keys/my-server.my-domain.tld.key
```
