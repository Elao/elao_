---

type:               "post"
title:              "SSL - Générer une demande de signature de certificat"
date:               "2015-03-13"
publishdate:        "2015-03-13"
draft:              false
slug:               "ssl-generer-une-demande-de-signature-de-certificat-csr"
description:        "Comment générer une demande de signature d'un certificat SSL (CSR) à destination d'une autorité de certification."

thumbnail:          "/images/posts/thumbnails/crypto.jpg"
header_img:         "/images/posts/headers/elao_babyfoot.jpg"
tags:               ["Infra", "Linux", "SSL", "Certicats", "Sécurité"]
categories:         ["Infra", "Sécurité", "Linux"]

author_username:    "gfaivre"

---

# Introduction

La génération d'une requête de signature de certificat, appelée comme ceci car elle est normalement transmise à une autorité de certification (Certificate Authority ou CA) pour ... signature, est faite lorsque l'on souhaite exploiter un certificat SSL délivré par une autorité de certification (reconnue ou non).

Un certificat n’est ni plus, ni moins qu’une clé qui a été signée par une autorité autorisée garantissant ainsi que celle-ci est valide est correspond bien à la bonne entité.

La "Certificate Signin Request", souvent abrégée en CSR peut être assimilée à une clé publique contenant quelques informations supplémentaires. Celles-ci et la fameuse clé sont insérées dans le certificat une fois celui-ci signé.

A la génération de cette requête, un certain nombre d'informations vous seront donc demandées, celles-ci sont appelées "Distinguished Name" (DN). La plupart des informations à renseigner sont triviales hormis le "Common Name" (CN) qui doit exactement correspondre au FQDN (Fully Qualified Domain Name) de la machine à laquelle est destiné le certificat.

Bien que l'importance de celles-ci soit toute relative, elles devraient néanmoins, refléter au maximum la réalité lorsque vous achetez un certificat SSL auprès d'une autorité de certification.

Il est bien entendu possible de passer l'ensemble de ces informations via la ligne de commande lors de la génération du certificat.

# Générer des CSRs

A présent que nous savons à quoi servent ces fameuses requêtes nous listerons ci-dessous les différents cas de figure qui pourraient nous conduire à en avoir besoin et comment les générer.

## Générer une clé privée et une CSR

Cette méthode est particulièrement indiquée si vous souhaitez utiliser HTTPS (HTTP + TLS) et donc générer un certificat pour un serveur web (Apache ou Nginx par exemple), pour ensuite le faire certifier par une CA ou demander la délivrance d'un certificat SSL à une CA reconnue.

Il est possible, si la CA le permet, d'utiliser SHA-2 pour signer le certificat avec l'option `-sha256` ([Gandi](https://www.gandi.net/ssl) le gère parfaitement)

Pour créer une clé privée de 2048 bits et une CSR utilisez la commande suivante:

```
openssl req -newkey  rsa:2048 -sha256 -nodes -keyout bismuth-elao.key -out bismuth-elao.csr
```

> - `-newkey  rsa:2048`:  Indique la génération d'une clé à partir de l'algorithme RSA d'une longueur de 2048 bits.
> - `-sha256`: Que nous souhaitons utilisé SHA-2 pour signer notre CSR (Attention à bien vérifier que votre CA le supporte)
> - `-nodes`: Comprendre "No DES" indique que la clé privée ne doit pas être cryptée à l'aide d'une "passphrase" ce qui est le cas par défaut, à vous de juger de la pertinence de cette option.
> - `-keyout`: Indique le fichier qui contiendra la clé générée.
> - `-out`: Indique le fichier qui contiendra la requête pour signature.

Vous devriez avoir en réponse la sortie suivante, attention à être particulièrement attentif au champ CN (Common Name) qui doit correspondre au domaine pour lequel vous souhaitez utiliser votre certificat.

```
Generating a 2048 bit RSA private key
............+++
.................................+++
writing new private key to elao.key
-----
You are about to be asked to enter information that will be incorporated
into your certificate request.
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
Common Name (e.g. server FQDN or YOUR name) []:*.elao.com
Email Address []:XXXX@my_domain.tld

Please enter the following 'extra' attributes
to be sent with your certificate request
A challenge password []:
An optional company name []:
```

## Générer une CSR à partir d'une clé privée

Dans le cas où vous disposez déjà d'une clé privée et que vous souhaitez l'utiliser pour la délivrance d'un certificat à partir d'une CA, il nous faudra utiliser la commande suivante:

```
openssl req -key bismuth-elao.key -new -out bismuth-elao.csr
```

Il suffit ensuite de renseigner les différentes informations demandées pour la génération de la CSR, attention à bien spécifier le CN (Common Name).

> - L'option `-key` permet de spécifier la clé à utiliser pour générer la nouvelle requête.
> - L'option `-new` indique la génération d'une CSR.

## Générer une CSR à partir d'une clé privée et d'un certificat

Ce cas de figure peut se présenter dans le cas où vous avez perdu la CSR originale, rien de bien critique pour le coup, cette manipulation épargne juste de perdre du temps à ressaisir les informations de la CSR, celles-ci sont dans ce cas là, extraites du certificat.

```
openssl x509
    -in bismuth-elao.crt
       -signkey bismuth-elao.key
       -x509toreq -out bismuth-elao.csr
 ```

L'option ```-x509toreq``` indique que l'on utilise un certificat X509 pour générer la CSR.
