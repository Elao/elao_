---
type:               "post"
title:              "Utiliser l'API Openstack OVH"
date:               "2016-12-16"
lastModified:       ~

description:        "Préparer son environnement pour utiliser l'API openstack d'OVH, pré-requis et installation du client"

thumbnail:          "images/posts/thumbnails/openstack.png"
banner:             "images/posts/headers/facade.jpg"
tags:               ["ovh","openstack","docker","infra","api"]
categories:         ["Infra", "openstack", "ovh"]

authors:            ["gfaivre"]
---

OVH propose depuis quelque temps déjà d'accéder à certaines ressources de votre infrastructure à travers l'API Openstack.
Bien que pas mal d'opérations soient réalisables grâce au manager, certaines d'entre elles, un peu poussées, ne sont réalisables qu'à travers l'[API OVH](https://api.ovh.com/) et/ou l'API OpenStack qui est d'ailleurs partiellement exploitée par le dashboard [Horizon](https://horizon.cloud.ovh.net/).

Nous allons voir en détails l'installation d'un environnement client pour exploiter l'API Openstack en console.

## Pré-requis

* Python 2.7 ou supérieur
* Le paquet «SetupTools»
* pip

Si vous ne souhaitez pas faire l'installation sur votre machine de travail, vous trouverez [ici](https://hub.docker.com/r/manala/openstack-api-client-debian/), un conteneur Docker prêt à l'utilisation.
Je prends le devant des Trolls, oui Docker n'a pas été conçu pour ça mais pour une fois qu'on lui trouve une utilité pratique... :D

**Sinon** voici les étapes à suivre (La machine utilisée est une Debian 8, je vous laisse le soin de corriger le nom des paquets qui pouraient différer d'une distribution à une autre.):

```
apt-get --no-install-recommends -y install vim python python-setuptools python-dev python-pip libyaml-dev gcc g++
```

Une fois que l'environnement est prêt il nous reste à installer le client OpenStack:

```
pip install python-openstackclient
```

Il est à noter que depuis peu l'ensemble des clients a été regroupé dans un seul. Auparavant chaque client avait son «domaine de compétences».
Toutefois, pour certaines opérations, il peut être nécessaire de passer par les anciens clients. Leur installation est assez simple et se fait également à l'aide de **`pip`**:

```
pip install python-novaclient
```

**Pour rappel:**

* `barbican` - Key Manager Service API
* `ceilometer` - Telemetry API
* `cinder` - Block Storage API and extensions
* `cloudkitty` - Rating service API
* `designate` - DNS service API
* `fuel` - Deployment service API
* `glance` - Image service API
* `gnocchi` - Telemetry API v3
* `heat` - Orchestration API
* `magnum` - Container Infrastructure Management service API
* `manila` - Shared file systems API
* `mistral` - Workflow service API
* `monasca` - Monitoring API
* `murano` - Application catalog API
* `neutron` - Networking API
* `nova` - Compute API and extensions
* `sahara` - Data Processing API
* `senlin` - Clustering service API
* `swift` - Object Storage API
* `trove` - Database service API

## Configuration/Authentification

Avant de pouvoir exploiter pleinement l'environnement que nous venons d'installer il nous faut récupérer nos autorisations à partir du manager OVH (dans la partie Cloud plus précisément).
Vous trouverez au niveau de vos différents environnements une entrée «OpenStack» qui vous permettra:

* de générer un compte utilisateur
* de vous rendre sur l'interface Horizon
* de récupérer un fichier préconfiguré avec vos identifiants
* de (re)générer un token OpenStack

<div class="text-center">
    <img src="images/posts/2016/ovh-openstack/ovh-os.png" alt="API OpenStack OVH" />
</div>

Une fois que vous avez créé un utilisateur, récupérez votre fichier de configuration (il s'agit en fait d'un simple fichier qui exporte les variables utilisées par le client OpenStack pour établir sa connexion) et «sourcez» le:

```
. ./openrc.sh
```

Les variables suivantes devraient être initialisées:

* `OS_AUTH_URL`
* `OS_TENANT_ID`
* `OS_TENANT_NAME`
* `OS_USERNAME`
* `OS_PASSWORD`

## Connexion

Une fois les étapes précédentes terminées vous devriez être capable de vous connecter avec le client. Nous pouvons vérifier le bon fonctionnement de l'environnement en listant nos conteneurs par exemple:

```
root@97cbc23bcd97:/srv# openstack server list
+--------------------------------------+----------+--------+-------------------------+
| ID                                   | Name     | Status | Networks                |
+--------------------------------------+----------+--------+-------------------------+
| 73fv7fb4-1234-4fff-9a51-eb4e5fbf654a | GW_XXXXX | ACTIVE | Ext-Net=XXX.XXX.XXX.XXX |
| 73fv7fb4-1234-4fff-9a51-eb4e5fbf654a | GW_XXXXX | ACTIVE | Ext-Net=XXX.XXX.XXX.XXX |
+--------------------------------------+----------+--------+-------------------------+
```

## Zones géographiques

Certains l'auront remarqué, il existe une dernière variable d'importance dans le fichier de configuration. La variable `OS_REGION_NAME` permet, en effet, de spécifier à quel DC vous souhaitez vous connecter:

* Roubaix (RBX1)
* Gravelines (GRA1)
* ou Beauharnois (BHS1)

Elle est d'importance car pour chacun de ces DC vous ne verrez (et c'est logique) que les instances qui lui sont rattachées.

----
Une bonne partie de l'API openstack vous est à présent accessible et couvrira les manques fonctionnels du manager ainsi que les carences de l'API OVH pour la configuration d'environnements un tant soit peu complexes.
L'utilisation des différents clients s'avère, de prime abord, peu aisée mais on s'y fait vite.

Comme à l'accoutumé cet article est ouvert à toutes critiques, suggestions et/ou corrections.
