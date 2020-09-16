---

type:               "post"
title:              "Configuration de Bind sous Mac OS X"
date:               "2010-05-21"
publishdate:        "2010-05-21"
draft:              false

description:        "Configuration de Bind sous Mac OS X."

thumbnail:          "/images/posts/thumbnails/yosemite.jpg"
tags:               ["Bind","Mac OSX", "OSX"]
categories:         ["Tech", "OSX"]

author:    "vbouzeran"

---

Dans cet article, nous allons détailler pas à pas comment configurer le serveur Bind intégré à Mac Os X.
Les développeurs web sont souvent confrontés à des problématiques DNS (wildcards, etc...) pour lesquelles le fichier **/etc/hosts** s'avère insuffisant.

Cet article est traduit de <a href="http://www.macshadows.com/kb/index.php?title=How_To:_Enable_BIND_-_Mac_OS_X's_Built-in_DNS_Server" target="_blank">"How To: Enable BIND - Mac OS X's Built-in DNS Server"</a>

### Etape 1: Configuration de rndc

**rndc** est l'utilitaire qui permet la configuration du serveur DNS. Vous devez tout d'abord créer un fichier de configuration ainsi qu'une clé secrète.

Saisissez les commandes suivantes dans votre terminal pour générer le fichier de configuration et la clé secrète :

```bash
> sudo -s
rndc-confgen -b 256 > /etc/rndc.conf
head -n5 /etc/rndc.conf | tail -n4 > /etc/rndc.key
```


Attention : La commande r**ndc-confgen** est extrêmement pratique pour générer les fichiers de configurations **rndc**, mais il se peut qu'il initialise un port par défaut différent de celui de **named**.
Lancez les commandes suivantes, et assurez-vous que les ports sont identiques dans les deux fichiers de configurations /**etc/named.conf** et **/etc/rndc.conf**, si ce n'est pas le cas, éditez les fichiers et uniformisez les.

```bash

cat /etc/named.conf | grep 'inet'
cat /etc/rndc.conf | grep 'default-port'
```


### Etape 2 : Activer BIND

Nous devons maintenant configurer **Bind** pour qu'il se lance au démarrage. Pour se faire, on utilisera les commandes suivantes:

```bash
launchctl load -w /System/Library/LaunchDaemons/org.isc.named.plist
echo "launchctl start org.isc.named" >> /etc/launchd.conf
```


Puis nous n'avons plus qu'à démarrer le démon.

```
/usr/bin/named
```

ou encore

```
launchctl start org.isc.named
```

### Etape 3 : Configuration de named<br />

Cette étape peut nécessiter quelques recherches supplémentaires si vous avez des besoins très spécifiques.
Dans cet article, nous allons voir un cas simple de configuration où nous souhaitons que notre interface locale (localhost) réponde pour tous les dns de la forme `*.local`
Ce type de configuration peut être très utile pour les développeurs web qui souhaitent gagner du temps et éviter d'éditer systématiquement leur fichiers /etc/host lorsqu'ils rajoutent un virtualhost dans Apache.

La création de nouveaux dns passe par deux étapes. La première consiste à créer le fichier de zone correspondant à notre dns ".local", la seconde va permettre d'indiquer à named la nouvelle zone ainsi que l'emplacement du fichier de configuration associé.

1) Nous devons tout d'abord créer notre fichier de zone.

```
vi /var/named/local.zone
```

Passez en mode insertion (touche i) et copiez/collez la configuration suivante en remplaçant user.domain.com par votre adresse email (prenez garde à remplacer le "@" par un "." et suffixer également d'un ".").

```bash

$TTL    86400
$ORIGIN local.
@       IN      SOA     localhost.      user.domain.com. (
                                        42              ; serial
                                        3H              ; refresh
                                        15M             ; retry
                                        1W              ; expiry
                                        1D )            ; minimum

                        1D IN NS        @
                        1D IN A         127.0.0.1

* IN A 127.0.0.1

```


Assurez vous que la dernière ligne est vide, sauvegardez et quittez (ESC puis ":wq" et ENTER to sauvegarder et quitter sous **VI**).

2) Reconfiguration de named.conf pour indiquer notre nouvelle zone

```
vi /etc/named.conf
```

Puis nous ajoutons les lignes suivantes à la suite des configurations de zones présentes:

```bash

zone "local" IN {
        type master;
        file "local.zone";
        allow-update { none; };
};
```


Sauvegardez et le tour est joué !

### Etape 4 : Rechargement de la configuration

A chaque fois que vous modifier les fichiers de configuration ou de zones, vous aurez besoin de recharger la configuration par la commande:

```
rndc reload
```

Vous pouvez également réinitialiser le cache DNS par la commande suivante:

```
rndc flush
```
