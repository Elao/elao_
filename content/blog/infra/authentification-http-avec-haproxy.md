---
type:               "post"
title:              "Authentification HTTP avec HA Proxy"
date:               "2015-03-10"
lastModified:       ~

description:        "Comment gérer une authentification HTTP basique avec HA Proxy, définir des utilisateurs, des groupes et le type d'authentification souhaitée."

thumbnail:          "images/posts/thumbnails/lock.png"
tags:               ["Infra", "HA Proxy", "Linux", "Network"]
categories:         ["Infra", "HA Proxy", "Linux"]

author:    "gfaivre"

---

Nous utilisons aujourd'hui pas mal HA Proxy pour faire du load-balancing software sur différents types d'infra, une fonctionnalité interessante proposée par HA Proxy permet de gérer une authentification basique via HTTP.

Cela permet, entre autre, de centraliser les accès au niveau du LB mais également de ne pas solliciter les backend.

Sa mise en place est assez simple et repose sur la notion de "**userlist**", qui permet de définir au niveau HA Proxy des identifiants comme ci-dessous.

```
userlist ELAO
  group infra users guewen,flo
  group dev users xavier,vincent

  user guewen password 84375611a53741f7e94b09eb49127f41
  user flo insecure-password ela0pAssWd
  user vincent insecure-password MyAwesomePassword
```

On remarquera qu'il est possible de définir des groupes pour les différents utilisateurs et de choisir de stocker le mot de passe en clair ou de manière chiffrée.

Pour information haproxy utilise la fonction `crypt` pour évaluer les "hash" de mot de passe, les fonctions de hachage supportées dépendent donc directement du système utilisé.

Sur des systèmes basés sur Linux les fonctions de hachage suivantes sont donc supportées:

- MD5
- SHA-256
- SHA-512
- DES

Il ne reste ensuite plus qu'a rajouter une ACL basée sur l'authentification du client.

```
backend HttpElaoServers
  acl AuthOK_ELAO http_auth(ELAO)
  http-request auth realm ELAO if !AuthOK_ELAO
```

Si les "credentials" du client ne sont pas bons HA Proxy renverra le traditionnel `401 Unauthorized` et le header `WWW-Authenticate: Basic`.
