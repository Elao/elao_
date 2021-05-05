---

type:               "post"
title:              "Migrer un site web sans interruption de service grâce au reverse proxy d'Apache."
date:               "2012-05-04"
lastModified:       ~

description:        "Migrer un site web sans interruption de service grâce au reverse proxy d'Apache."

thumbnail:          "images/posts/thumbnails/pulp_starwars.png"
banner:             "images/posts/headers/php_elao_code.jpg"
tags:               ["Apache","Linux","Trucs et astuces"]
categories:         ["Infra", "Linux"]

author:    "gfaivre"

---

Bonjour à tous,

petit mémo aujourd'hui concernant la migration d'une application web d'un (ancien) serveur vers un nouveau serveur.
Tout admin système à été, un jour ou l'autre, confronté à la problématique du "downtime" ou en bon français de l'interruption de service lors du "déménagement" d'une application web vers une nouvelle machine.

En effet dans le cas de la modification d'un pointage DNS, celui-ci peut mettre plusieurs heures, voir même une journée à se propager sur les différents serveurs de noms.
Pour un site statique cela ne pose guère de problème puisque nous sommes dans une stratégie de lecture, par contre pour un site dynamique le délai de propagation peut-être hautement problématique. Il est évident que l'on ne peut se permettre d'avoir des écritures sur l'un et l'autre des serveurs.

Une bonne habitude pour minimiser ce problème est d'utiliser les systèmes d'IP failover, qui permettent d'éviter de faire une modification au niveau du nom de domaine concerné lors d'une migration applicative.

>   Les IP failover sont, pour résumer, des interfaces "virtuelles" qui peuvent être facilement redirigées vers une machine physique, OVH propose par exemple ce système sur tous ses serveurs dédiés. Grâce à celles-ci, la problématique du downtime se pose moins, en effet une fois que notre site a été "déménagé", il suffit de modifier le pointage de cette IP failover sans impact sur le pointage des DNS.

**Cette stratégie trouve par contre ses limites dans différents cas :**

- Si l'on est dans le cas d'un serveur mutualisé (qui n'héberge donc pas qu'une seule application).
- S'il est impossible de faire un transfert complet des différents sites en même temps.
- Si l'on ne souhaite tout simplement "déménager" qu'une seule application

Dans le cas d'applications à l'architecture complexe pour lesquelles il est quasiment impossible d'assurer le transfert applicatif en une seule fois. On se retrouve donc souvent avec une partie des applications sur la nouvelle machine et le reste sur l'ancienne.

Il est heureusement possible de se servir des serveurs web comme de "reverse proxy".
Dans le cadre de ce mémo nous utiliseront [Apache][1] mais il est également possible d'utiliser [Nginx][2], voir même [Squid][3] qui doit être capable de faire du reverse proxy.

**Nous partirons du principe que :**

- Votre (vos) application(s) et base(s) de données ont été transférées sur leur nouveau serveur, que tout fonctionne et que tout a été vérifié par qui de droit.
- Un nouveau "Virtual Host" (désolé ça ne veux pas avec "Hôte virtuel" ...), a été configuré et est fonctionnel.
- Les mods `proxy` et `[proxy_http][4]` sont "chargés" par Apache sur notre serveur d'origine.
- Le site à déplacer sera `www.my-domain.com`.
- L'IP du "nouveau" serveur sera XXX.XXX.XXX.XXX

**Tout le reste se passe du coté de "l'ancien" serveur, nous commencerons par modifier la résolution de nom local concernant le domaine concerné en éditant le fichier `/etc/hosts` pour y rajouter l'entrée suivante :**

> XXX.XXX.XXX.XXX www.my-domain.com

De cette façon nous forçons le pointage de notre domaine vers la nouvelle machine uniquement pour le serveur d'origine.

**Nous allons ensuite modifier le "virtual host" original en y ajoutant les lignes suivantes :**

```apacheconf
ProxyRequests Off
<Proxy *>
  Order deny,allow
  Allow from all
</Proxy>

ProxyPass / http://www.my-domain.com/
ProxyPassReverse / http://www.my-domain.com/
```


Il suffit, pour finir, de redémarrer Apache, le traffic est à présent redirigé vers le nouveau serveur de manière transparente pour l'internaute.
Nous pouvons dès lors demander la redirection du nom de domaine vers l'IP du "nouveau" serveur sans se soucier des délais de propagation des serveurs de noms.

Une fois tout cela terminé jetez de temps en temps un oeil à vos logs sur le serveur d'origine, une fois que vous constatez que plus aucun traffic n'arrive sur votre ancien serveur c'est que le nom de domaine a été propagé.

 [1]: http://httpd.apache.org/ "Apache"
 [2]: http://nginx.com/ "Nginx"
 [3]: http://www.squid-cache.org/ "Squid"
 [4]: http://www.elao.com/blog/linux/reverse-proxy-apache-no-protocol-handler-was-valid-for-the-url.html "Reverse proxy Apache: No protocol handler was valid for the URL"
