---
type:           "post"
title:          "Comprendre l'héritage des instructions de configuration de Nginx"
date:           "2015-11-30"
publishdate:    "2016-01-11"
draft:          false
slug:           "comprendre-lheritage-des-instructions-de-configuration-de-nginx"
description:    "Nginx fonctionne avec une notion de contexte autorisant certaines instructions de configuration. Nous évoquons dans cet article comment Nginx traite et organise ces différents blocs."

thumbnail:      "/images/posts/thumbnails/geek_love.jpg"
header_img:     "/images/posts/headers/stickers.jpg"
tags:           ["infra", "nginx", "linux"]
categories:     ["infra", "nginx"]

author_username:    "gfaivre"

---


Nginx fonctionne sur la base de blocs de configuration appelés « Contexte de configuration », qui vont selon leur positionnement et/ou leurs instructions traiter, modifier voir altérer les requêtes entrantes.<!--more-->

# Les contextes

L'organisation des contextes est primordiale car leur positionnement dans la configuration n'est pas sans importance et s'apparente à un arbre.

```bash

Global (ou Main)
├── Events
└── Http
    ├── Server
    ├── If
    ├── Location
    │   ├── If
    │   ├── Location (Imbriquée)
    │   └── limit_except
    ├── map
    ├── perl / (perl_set)
    ├── split_clients
    ├── geo
    ├── types
    └── charset_map
```

> **N.B.:** Le contexte **global** est particulier car non-défini explicitement. Toute directive spécifiée hors d'un contexte est considérée comme appartenant au contexte **global**.

### Exemple

```nginx
user www-data;
worker_processes 1;

error_log /var/log/nginx/error.log warn;
pid /var/run/nginx.pid;

events {
    worker_connections 1024;
}
```


# Les directives de type chaîne

Il est très important de garder à l'esprit que les directives de configuration s'appliquent **toujours** vers le bas, il n'y a aucune chance qu'une directive ait un impact sur une directive de plus haut ou de même niveau.
Même lorsqu'une directive `location` renvoie vers une directive identique le contexte de la première n'a **plus aucune valeur**.

A l'inverse les contextes pouvant être imbriqués les uns dans les autres, Nginx considère également l'héritage des directives de configuration, la règle étant que si une directive est valide dans deux contextes imbriqués, une directive déclarée dans un contexte parent s'appliquera à ses enfants comme valeur par défaut.

**Le contexte enfant pouvant, bien entendu, surcharger ces directives.**


## Exemple:

```nginx
server {
    root /srv/app/symfony/web;

    location /app {
        root /usr/share; # Nous modifions le répertoire racine vers /usr/share/app
                         # Toutes les URI de type /app/* seront servies
                         # à partir de ce répertoire.
    }

    location /app2 {
        # La directive root du contexte "Server" s'applique
        # Une URI de type /app2/user/profil sera servie
        # à partir de /srv/app/symfony/web
    }
}
```

# Les directives de type tableau

Les directives `array-type` n'échappent pas à la règle de surcharge, cependant leur comportement est légèrement différent, deux cas de figure peuvent se présenter.

> Pour rappel un `array-type` est un type de données utilisé pour décrire une collection d'éléments identifiés unitairement par une clé, **ils sont compilés au lancement du programme**.

## A l'intérieur d'un même contexte

La redéfinition d'une directive array-type à l'intérieur d'un même bloc, par exemple un bloc `location`, entraine l'ajout de la nouvelle clé à la structure.

### Exemple

```nginx
fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
fastcgi_param HTTPS off;
```


Dans ce cas la directive `fastcgi_param` contient les données suivantes:

```yaml
fastcgi_param:
    - SCRIPT_FILENAME: $document_root$fastcgi_script_name
    - HTTPS: off
```


## A l'intérieur de contextes différents

Dans ce cas le comportement sera le même qu'une **surcharge de directive**, la nouvelle définition écrasant la précédente, il ne faut donc pas considérer que l'on ajoute une nouvelle clé à un tableau existant car n'oubliez pas, les directives n'ont d'existence que dans **le contexte auquel elles appartiennent**.
Ce comportement justifie souvent d'avoir une même configuration qui se répête dans deux blocs différents alors qu'une seule clé est modifiée.

### Exemple

```nginx
    location ~ ^/(frontend)\.php(/|$) {
        include conf.d/php_fpm_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param HTTPS on;
    }

    location ~ ^/(backend)\.php(/|$) {
        include conf.d/php_fpm_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param HTTPS off;
    }
```

> ATTENTION: certaines directives sont propres à un contexte et ne peuvent donc pas être utilisées hors de celui-ci.

# Les directives de type action

Les directives de type action ont la particularité de ne pas impacter leur comportement les unes entre les autres, elles sont en effet limités à un seul contexte et ne sont pas concernées par l'héritage descendant.
Elles peuvent toutefois, être utilisées dans plusieurs contextes et être exécutées pour chacun d'entre eux.

La directive `rewrite` est un bon exemple car elle peut être utilisée à la fois dans le contexte `server` et dans le contexte `location` et être exécutée dans les deux.

### Exemple

```nginx

server {
    rewrite ^/elao-lyon(.*) /lyon$1 permanent; # Cette règle est toujours évaluée.

    location /lyon {
        rewrite ^ /index.php; # Ne surcharge pas la précédente et
                              # PEUT être évaluée en complément de la précédente.
    }
}
```
