---

type:               "post"
title:              "Controller ses services applicatifs avec supervisor"
date:               "2014-12-22"
lastModified:       ~

description:        "Supervisor est un système de contrôle des processus/services applicatifs destiné aux systèmes de types UNIX."

thumbnail:          "content/images/blog/thumbnails/ice_bulb.jpg"
tags:               ["Services", "Infra", "Linux", "Debian", "Supervisor"]
categories:         ["Infra", "Linux"]

authors:            ["gfaivre"]

---

Bonjour à tous,

Aujourd'hui nous allons faire le tour d'une solution fort sympathique que nous utilisons @elao pour faire tourner certains services applicatifs.

Son petit nom ? [**Supervisor**](http://supervisord.org/).

## Introduction

Supervisor est un petit outil codé en Python et permettant d'assurer le suivi et le contrôle de services/processus applicatifs sur des systèmes de type UNIX.

On peut le comparer à launchd (utilisé par OSX) ou [**runit**](http://smarden.org/runit/). Attention toutefois il n'est pas destiné à remplacer le process init des systèmes UNIX. Il est par contre le parfait allié pour lancer et suivre des services applicatifs liés à un projet et qui doivent être démarrés et disponibles en permanence.

On peut notamment penser à des services NodeJS ou autres.

Supervisor est disponible en paquet Debian en version 3.0, l'installation est donc très facile :

```bash
apt-get install supervisor
```

Supervisor est divisé en deux composants différents.

## Supervisord

Supervisord est la partie qui a la responsabilité de démarrer les services configurés et de les redémarrer en cas de crash.
Il est configurable via le fichier ```/etc/supervisor/supervisord.conf```

## Supervisorctl

**Supervisorctl** est le client permettant d'administrer les services gérés par supervisor. Il est capable de se connecter à plusieurs démons **supervisord**  et permet de démarrer / arrêter des services. Il est possible de les faire fonctionner soit via une socket soit via TCP. Il est également possible de gérer une authentification lors de la connexion au démon.

## Utiliser supervisor

### Ajouter un service à Supervisor

Pour ajouter un service à supervisor il suffit de le déclarer en ajoutant un fichier dans ```/etc/supervisor/conf.d```.

Attention celui-ci doit se terminer par l'extension ```.conf``` pour être correctement pris en compte par le démon.

```bash
[program:blogd]
command=node node_modules/bin/blogd

directory=/srv/my_project

autostart=true
autorestart=true
startretries=20
numprocs=1
stdout_logfile=/var/log/blogd.log
redirect_stderr=true
```
Après redémarrage de supervisor (```/etc/init.d/supervisor restart```) nous pouvons ensuite vérifier le bon fonctionnement du/des service(s) via le client :

```bash
elao@bismuth:/etc/supervisor/conf.d|
⇒  supervisorctl
mailcatcher                      RUNNING    pid 7840, uptime 2 days, 4:51:46
phantomjs                        RUNNING    pid 7842, uptime 2 days, 4:51:46
blogd                            RUNNING    pid 11929, uptime 0:02:41
```

### Redémarrer un service

La syntaxe pour redémarrer un service est on ne peut plus simple.

```bash
supervisor> restart blogd
blogd: stopped
blogd: started
```
### Parcourir les logs depuis le client

```bash
supervisor> tail -f blogd
==> Press Ctrl-C to exit <==
var/blog/content/blog/images/ => web/blog/medias
[ASSETS] Copying folders succeed
Assets copied
Listening on 127.0.0.1:5555
Config: /srv/site/silex/package.json
Backup file var/blog/current.json not found or unreadable
Loading fresh content
Refreshing data from source
Loading posts...
Loading users...
Loading tags...
Checking data...
Data checked status success
Copying assets
[ASSETS] from var/blog/content/blog/images/ => web/blog/medias
[ASSETS] Copying folders succeed
Assets copied
Listening on 127.0.0.1:5555

```

### Activer l'interface web

Pour finir supervisor fournit également une interface web qui est activable via la section ``[inet_http_server]`` et qui permet de gérer les services de la même façon que le client en console.

![Supervisor - Interface web supervisor](content/images/blog/2014/supervisor_web.png)

Il suffit de créer un nouveau fichier dans ```/etc/supervisor/conf.d``` s'appelant par exemple ```inet_http_server.conf``` et d'y recopier le contenu suivant :

```bash
[inet_http_server]
port      = :9001
username  = elao
password  = boumbo
```

Si vous ne souhaitez pas d'authentification il suffit de supprimer les deux lignes correspondantes.

Remarques, commentaires et corrections sont les bienvenus comme toujours, n'hésitez pas à proposer vos [**PR**](https://github.com/Elao/blog).
