---
type:               "post"
title:              "Accéder à une API cross-domain depuis Javascript avec CORS et un reverse proxy nginx"
date:               "2016-11-03"
lastModified:       ~

description:        "Configurer un reverse proxy avec nginx et CORS pour permettre à une application Javascript d'accéder à une API sur un autre domaine en contournant la Same Origin Policy."

thumbnail:          "content/images/blog/thumbnails/bridge.jpg"
banner:             "content/images/blog/headers/bridge.jpg"
tags:               ["Nginx", "Reverse", "Proxy", "Cors"]

authors:            ["mcolin"]
---

## Introduction

Dans la continuité de l'émergence des applications full frontend, nous sommes de plus en plus amenés a appeler des API directement en Javascript depuis le client. J'ai récemment été confronté à un cas où l'API à interroger n'était pas sur le même domaine que l'application. Sur un développement backend ce genre de cas ne pose aucun problème mais avec Javascript, pour des raisons de sécurité, les communications **cross-domain** sont bloquées par la [Same Origin Policy](https://developer.mozilla.org/fr/docs/Web/JavaScript/Same_origin_policy_for_JavaScript).

## CORS

[Cross-Origin Resource Sharing](http://www.w3.org/TR/cors/) (CORS) est une spécification du W3C permettant les requêtes **cross-domain** depuis les navigateurs compatibles. Si l'API que vous interrogez est compatible avec **CORS**, vous pourrez accéder à l'API même si elle n'est pas sur le même domaine que votre application.

CORS est compatible avec :

- Chrome 3+
- Firefox 3.5+
- Opera 12+
- Safari 4+
- Internet Explorer 8+

Pour utiliser **CORS** il faut envoyer au serveur des *headers* de contrôle d'accès qu'il inspectera pour approuver ou non la requête. Ces *headers* de contrôle d'accès décriront le contexte de la requête, sa méthode HTTP, son origine, ses headers custom, ...

Selon le type de requête, ces headers sont envoyés automatiquement par le navigateur avec la requête ou dans une requête préliminaire (*preflight request*). La requête aboutira si le serveur répond avec des headers de contrôle d'accès compatibles.

```
=> OPTIONS https://api.com/foobar
- HEADERS -
Origin: http://application.com
Access-Control-Request-Method: POST
Access-Control-Request-Headers: Api-Key

<= HTTP/1.1 204 No Content
- RESPONSE HEADERS -
Access-Control-Allow-Origin: http://application.com
Access-Control-Allow-Methods: GET, POST, OPTIONS
Access-Control-Max-Age: 86400
Access-Control-Allow-Headers: Api-Key
```


Pour plus d'informations sur le fonctionnement de **CORS**, je vous laisse lire les articles [Making Cross-Domain Requests with CORS](https://www.eriwen.com/javascript/how-to-cors/) et [Démystifier CORS (Cross-Origin Resource Sharing)](http://blog.inovia-conseil.fr/?p=202) qui sont très complets.

## Reverse Proxy

Malheureusement l'API que je souhaitais utiliser n'était pas compatible CORS. Si c'est votre cas également, il faudra alors passer par un proxy.

**Nginx** permet simplement de mettre en place ce genre de *reverse proxy* grace à une configuration de ce type :

```
server {
    listen          80;
    server_name     application.com;

    location /api {
        # Rewrite /api/resource/action?key=value to /resource/action?key=value
        RewriteRule                     ^/api/(.*)$ /$i [L,PT,QSA]

        # Activer le proxy
        proxy_set_header                X-Real-IP $remote_addr;
        proxy_set_header                X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_pass                      http://api.com;
        proxy_redirect                  off;
        proxy_buffers                   32 16k;
        proxy_busy_buffers_size         64k;
    }
}
```

Ainsi, votre application pourra appeler votre API sur ```http://application.com/api``` sans problème de *cross-origin*.

Si vous avez besoin d'exposer l'API sur un autre domaine ou sur un autre port que votre application, vous aurez  besoin de permettre le *cross-domain* grâce à **CORS**. Mon application tournant sur ```localhost:8080```, j'ai décidé de mettre mon proxy sur ```localhost:8181```.

```
server {
    listen          8181;
    server_name     localhost;

    location / {

        # Activer le proxy
        proxy_set_header                X-Real-IP $remote_addr;
        proxy_set_header                X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_pass                      http://api.com;
        proxy_redirect                  off;
        proxy_buffers                   32 16k;
        proxy_busy_buffers_size         64k;

        # Ajouter les headers de contrôle d'accès CORS
        add_header    'Access-Control-Allow-Origin' '*' always;
        add_header    'Access-Control-Allow-Methods' 'GET, POST, OPTIONS' always;
        add_header    'Access-Control-Allow-Headers' 'Origin, X-Requested-With, Content-Type, Accept' always;
        add_header    'Access-Control-Allow-Credentials' 'true' always;
}
```

Je peux ainsi appeler l'API sur ```localhost:8181``` de façon transparente.

<div style="border-left: 5px solid #ffa600;padding: 20px;margin: 20px 0;">
    Attention, <code>Access-Control-Allow-Origin: '*'</code> autorise les requêtes <em>cross-origin</em> depuis n'importe quel domaine, en dev cela permet de facilement mettre CORS en place mais dans un soucis de sécurité il faudrait être plus restrictif.
</div>

## Bonus

Vous pouvez encore améliorer votre proxy par exemple en ajoutant automatiquement des headers d'authentification si l'API en nécessite ou encore des headers de cache afin de reduire le temps de réponse ou d'économiser un éventuel quota imposé par l'API.

## Conclusion

Grâce a **nginx** vous pouvez donc créer un *reverse proxy* qui vous permettra d'accéder à une API directement depuis votre application front sur un domaine différent en contournant de façon sécurisée la **Same Origin Policy**.
