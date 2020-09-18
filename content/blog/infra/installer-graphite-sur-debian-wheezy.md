---

type:               "post"
title:              "Installer graphite sur Debian Wheezy"
date:               "2014-12-20"
publishdate:        "2014-12-20"
draft:              false

description:        "Installation de graphite avec Gunicorn et Nginx sur debian Wheezy."

thumbnail:          "images/posts/thumbnails/dashboard.png"
tags:               ["Monitoring", "Infra", "Linux", "Debian", "Nginx"]
categories:         ["Infra", "Monitoring", "Linux"]

author:    "gfaivre"

---

Parmi de nombreuses solutions de monitoring l'une d'entre elle fait pas mal parler d'elle en ce moment. [**Graphite**](http://graphite.wikidot.com/) se veut capable de rendre des graphiques en temps réel de l'état de vos plateformes (systèmes ou applicatives) à partir d'informations collectées sur l'ensemble de votre infra.

Que l'on soit bien d'accord nous ne sommes pas dans le même type de monitoring que peut fournir, par exemple des solutions comme [**Zabbix**](http://www.zabbix.com/), qui proposeront, certes, des graphs à partir des différentes ressources "monitorées" mais également des solutions permettant d'alerter les équipes Infra en cas de problèmes (par Email ou par SMS).

<u>**Graphite est aujourd'hui séparés en plusieurs composants:**</u>

- [**Carbon**](https://github.com/graphite-project/carbon), qui est en charge de recevoir les métriques qui lui sont envoyés via le réseau et de les stocker en utilisant l'un des "storage backend" qui lui est fourni. Il sera bientôt remplacé par [**Ceres**](https://github.com/graphite-project/ceres) (Nom de code MegaCarbon).
- [**Whisper**](https://github.com/graphite-project/whisper) est en charge du stockage des informations, c'est une TSDB (Time Serie Database), en bon français une base de données fichiers permettant de stocker des métriques horodatés.
- [**Graphite-web**](https://github.com/graphite-project/graphite-web) qui fourni une interface permettant de créer des graphiques à partir des données collectées par Carbon et Whisper.

Nous verrons au cours de ce tutoriel la mise en place d'une stack de monitoring basée sur ces différents composants.

## Installation des paquets

Nous allons, dans un premier temps, avoir besoin des paquets suivants (Les versions utilisées pour la réalisation de cet article sont spécifiées):

- nginx (1.6.2)
- python-dev (2.7.3-4+deb7u1)
- python-pip (1.1.3)
- memcached (1.4.13-0.2+deb7u1)
- libcairo2-dev (1.12.2-3)
- pkg-config (0.26-1)
- libyaml-dev (0.1.4-2+deb7u5)

_Attention  l'installation de la version 1.6.2 de Nginx nécessite <a href="http://www.elao.com/fr/blog/utiliser-les-depots-officiels-nginx-sur-debian-wheezy" target="_blank">**d'utiliser les dépôts officiels Nginx**</a> et non pas ceux de Debian dans lesquels Nginx est fourni en version 1.2.1._

```
apt-get install nginx python-dev python-pip memcached  libcairo2-dev pkg-config git libyaml-dev
```

## Installation de l'environnement Python

Graphite web exploitant le Framework Django et diverses autres librairies python nous allons les installer:

```
pip install django==1.5 &&
pip install twisted==11.0.0 &&
pip install python-memcached==1.47 &&
pip install txAMQP==0.4 &&
pip install django-tagging==0.3.2 &&
pip install gunicorn==19.1.1 &&
pip install git+git://github.com/graphite-project/whisper.git#egg=whisper==0.9.12 &&
pip install PyYAML==3.11
```

## Installer Cairo

[**Cairo**](http://cairographics.org/pycairo/) est utilisé pour les rendus par Graphite.

```
cd /tmp &&
wget http://cairographics.org/releases/py2cairo-1.8.10.tar.gz &&
tar xfz py2cairo-1.8.10.tar.gz &&
cd pycairo-1.8.10 &&
./configure &&
make &&
make install
```

## Installer Carbon

Pour commencer nous allons installer Carbon en suivant les étapes suivants:

```
cd /tmp && git clone https://github.com/graphite-project/carbon.git &&
cd carbon &&
python setup.py install
```
Le chemin d'installation par défaut est ```/opt/graphite``` il est bien sur possible de personnaliser cette installation.

### Personnaliser l'installation de Carbon

Si les paramètre d'installation par défaut de Carbon ne vous conviennent pas vous pouvez les modifier avec les options suivantes:

- ```--prefix``` Qui va correspondre au chemin d'installation des répertoire bin/ et conf/ (pour rappel par défaut ils sont installés  dans ```/opt/graphite```)

- ```--install-lib``` Qui correspondra à l'installation des modules et librairies python. (Par défaut ```/opt/graphite/lib```)
- ```--install-data``` Qui correspondra à l'emplacement où seront placés les répertoires ```storage``` et ```conf``` ( Il est égal par défaut à la valeur de ```prefix```)
- ```--install-scripts``` Qui correspondra à l'emplacement ou sont stockés les scripts (Par défaut dans ```bin``` relativement à ```prefix```)

Si l'on souhaite tout installer dans /srv/graphite

```python setup.py install --prefix=/srv/graphite --install-lib=/srv/graphite/webapp```

Si vous souhaitez donc coller un maximum au shema type des système Linux on peut donc imaginer quelque chose comme:

```python setup.py install --install-scripts=/usr/bin --install-lib=/usr/lib/python2.7/site-packages --install-data=/var/lib/graphite```

Attention la version de python dépendra de votre configuration !

### Automatiser le démarrage de Carbon

Les scripts init.d fournis avec Carbon fonctionnent mais sont prévus pour RedHat et vont renvoyer une erreur (sans gravité mais qui peut toujours laisser planner un doute), vous trouverez donc ci-après les scripts init.d pour Debian pour chacun des services Carbon ([**carbon-aggregator**](https://gist.github.com/gfaivre/9cbc5d38bc3ce8586fdf/download), [**carbon-cache**](https://gist.github.com/gfaivre/09a4870ef6fc429c0bb0/download), [**carbon-relay**](https://gist.github.com/gfaivre/090098bae3e8238fd7c6/download)) en attendant que notre PR soit acceptée.

Ces scripts sont, bien entendu, à placer dans ```/etc/init/d``` il suffit ensuite de jouer ce [**script**](https://gist.github.com/gfaivre/9c9f3bb6eafd6514d57b/download) afin de les configurer.

## Installer Graphite Web

Graphite web est la partie permettant de créer des graphiques à partir des métriques stockés par Carbon.

````
cd /tmp && git clone https://github.com/graphite-project/graphite-web.git &&
cd graphite-web &&
python setup.py install
````

Comme pour Carbon le chemin d'installation est par défaut ```/opt/graphite``` il est également possible de personnaliser cette installation.

### Personnaliser l'installation de Graphite Web

Il est tout à fait possible, comme pour Carbon, de personnaliser notre installation de Graphite web:

- ```--prefix``` Qui va correspondre au chemin d'installation des répertoire bin/ et conf/ (pour rappel par défaut ils sont installés  dans ```/opt/graphite```)
- ```--install-lib``` Qui correspondra à l'installation des modules et librairies python. (Par défaut ```/opt/graphite/lib```)
- ```--install-data``` Qui correspondra à l'emplacement où seront placés les répertoires ```storage``` et ```conf``` ( Il est égal par défaut à la valeur de ```prefix```)
- ```--install-scripts``` Qui correspondra à l'emplacement ou sont stockés les scripts (Par défaut dans ```bin``` relativement à ```prefix```)

Si l'on souhaite tout installer dans /srv/graphite

```python setup.py install --prefix=/srv/graphite --install-lib=/srv/graphite/webapp```

Si vous souhaitez donc coller un maximum au schema type des système Linux on peut donc imaginer quelque chose comme:

```python setup.py install --install-scripts=/usr/bin --install-lib=/usr/lib/python2.7/site-packages --install-data=/var/lib/graphite```

Attention la version de python dépendra de votre configuration !

### Configurer Graphite-web

Recopiez et renommer le fichier ```graphite.wsgi.example``` que vous trouverez dans ```/opt/graphite/conf``` en ```/opt/graphite/webapp/wsgi.py```

Renommez ensuite le fichier ```/opt/graphite/webapp/graphite/local_settings.py.example``` en ```local_settings.py```.


`````
cd /opt/graphite/conf &&
cp graphite.wsgi.example ../webapp/wsgi.py &&
cd ../webapp &&
mv /opt/graphite/webapp/graphite/local_settings.py.example  local_settings.py
`````

Enfin modifiez le fichier ```/opt/graphite/webapp/graphite/settings.py``` en remplaçant le champ ```SECRET_KEY``` par une clé aléatoire de bonne longueur.
