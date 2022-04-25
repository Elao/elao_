---
type:               "post"
title:              "Provisionner simplement une stack de monitoring Telegraf + InfluxDB + Grafana avec Manala"
date:               "2017-01-25"
lastModified:       ~

description:        "Comment utiliser les roles Ansible de Manala pour provisionner simplement une stack de monitoring Telegraf + InfluxDB + Grafana"

language:           "fr"
thumbnail:          "content/images/blog/thumbnails/grafana.jpg"
banner:             "content/images/blog/headers/grafana.jpg"
tags:               ["provisoning","manala","ansible","influxdb","grafana","telegraf","monitoring"]
categories:         ["Infra", "manala", "ansible"]

authors:            ["mcolin"]
---

## Manala

[Manala](http://www.manala.io/) est la boîte à outils pour [Ansible](https://www.ansible.com/) créée par [Elao](https://www.elao.com/fr/). Elle se compose d'une multitude de rôles **Ansible** pensés autour de la même philosophie : une installation et une configuration simple d'un environnement serveur.

Si vous n'êtes pas famillier avec **Ansible**, je vous encourage à [découvrir ce magnifique outil](http://docs.ansible.com/ansible/index.html).

## Pourquoi monitorer

Le **monitoring** consiste à surveiller et logger une série de métriques dans le temps et de les représenter sous forme de graphiques. Le monitoring vous permet de détecter voir d'anticiper des anomalies ou des pannes, que ce soit de votre infrastucture ou de votre applicatif.

Le **monitoring** peut vous permettre de déclencher des alertes sur certains seuils de **métriques** afin de réagir avant qu'un problème devienne critique.

Celà peut être :

- un pic de charge important indiquant un problème de performance ou un pic de visiteurs
- un pic de trafic anormal causé par une attaque
- à l'inverse une perte soudaine de traffic pouvant indiquer une panne ou une indisponibilité
- ...

Vous pouvez également mesurer un gain ou une perte de **performance** à la suite d'une mise à jour.

En conclusion, le monitoring permet de **surveiller** la santé de votre serveur/application.

## La stack

### Telegraf

[Telegraf](https://www.influxdata.com/time-series-platform/telegraf/) est un collecteur de données créé par les créateurs d'**InfluxDB** : [InfluxData](https://www.influxdata.com/). Il permet de collecter des données systèmes (CPU, mémoire, I/O, disque, ...) et dispose de très nombreux plugins [d'entrées](https://github.com/influxdata/telegraf#input-plugins) (pour collecter) et [de sortie](https://github.com/influxdata/telegraf#output-plugins) (pour stocker).

### InfluxDB

[InfluxDB](https://www.influxdata.com/time-series-platform/influxdb/) est une base de données écrite en Go spécialisée dans le stockage de métriques et d'événements. Egalement développé par [InfluxData](https://www.influxdata.com/), l'intégration avec **Telegraf** est très facile.

### Grafana

[Grafana](http://grafana.org/) est une des références parmis les dashboards de métriques. Il permet de réaliser des graphiques à partir d'une multitudes de sources de données.

A partir de la version 4, Grafana permet également de faire de l'alerting basique grâce à un système de [règles](http://docs.grafana.org/alerting/rules/) et de [notifications](http://docs.grafana.org/alerting/notifications/).

## Provisonning

### Playbook

Vous devez installer les rôles suivants grâce à [Ansible Galaxy](https://galaxy.ansible.com/intro) :

```yaml
- src: manala.apt
- src: manala.telegraf
- src: manala.influxdb
- src: manala.grafana
```

puis les ajouter à votre playbook :

```yaml
---

- hosts: all
  roles:
    - role: manala.apt
    - role: manala.influxdb
    - role: manala.telegraf
    - role: manala.grafana
```

<div style="border-left: 5px solid #ffa600;padding: 20px;margin: 20px 0;">
    Attention, jouez bien les rôles dans cet ordre là. Le rôle <code>manala.apt</code> doit être en premier car il va configurer les dépots. Telegraf doit être installé après InfluxDB car il va y créer sa base de données.
</div>

### Configuration

Dans ```group_vars```, la configuration suivante permet d'indiquer que nous souhaitons installer InfluxDB, Telegraf et Grafana via les dépôts ```influxdata``` et ```grafana```.

```yaml
manala_apt_preferences:
  - influxdb@influxdata
  - telegraf@influxdata
  - grafana@grafana
```

Il faut ensuite configurer le rôle [manala.telegraf](https://github.com/manala/ansible-role-telegraf) :

```yaml
manala_telegraf_configs_exclusive: true
manala_telegraf_configs:
  - file:     output_influxdb.conf
    template: configs/output_influxdb.conf.j2
    config:
      - urls: ["http://localhost:8086"]
      - database: telegraf
      - username: telegraf
      - password: password

  - file:     input_system.conf
    template: configs/input_system.conf.j2

  - file:     input_cpu.conf
    template: configs/input_cpu.conf.j2

  - file:     input_mem.conf
    template: configs/input_mem.conf.j2

  - file:     input_disk.conf
    template: configs/input_disk.conf.j2

  - file:     input_diskio.conf
    template: configs/input_disk.conf.j2

  - file:     input_net.conf
    template: configs/input_net.conf.j2
```

La configuration du fichier ```output_influxdb.conf``` indique sur quel support de stockage **Telegraf** doit envoyer les données collectées. Ici on indique l'url de API d'**InfluxDB** ainsi que le nom et les identifiants de la base de données à utiliser.

Les fichiers ```input_*.conf``` suivants permettent de configurer les métriques à collecter. Le rôle est fourni avec des fichiers de configurations pour les plusieurs métriques mais vous pouvez ajouter vos propres fichiers de configuration.

* cpu
* disk
* diskio
* haproxy
* mem
* net
* swap
* system

Les rôles [manala.influxdb](https://github.com/manala/ansible-role-influxdb) et [manala.grafana](https://github.com/manala/ansible-role-grafana) ne nécessitent pas de configuration particulière pour fonctionner.

Je vous encourage néanmoins à jeter un oeil à la configuration de ces deux rôles si vous souhaitez aller plus loin, notamment concernant la sécurité des deux outils ou l'activation de fonctionnalités de Grafana.

## Prise en main

### InfluxDB

InfluxDB dispose d'une interface en ligne de commandes à la manière de ```mysql``` pour exécuter des requêtes. Si vous vous connectez en SSH à la machine que vous avez provisonnée, vous devriez pouvoir l'interroger. Vous pouvez par exemple lister les métriques de la base de données "telegraf".

```bash
$ influx -execute 'SHOW MEASUREMENTS' -database="telegraf"
name: measurements
name
----
cpu
disk
diskio
kernel
mem
net
processes
swap
system
```

Vous devriez voir la liste des métriques que vous aviez configurées plus haut dans le rôle ```manala.telegraf```

<div style="border-left: 5px solid #ffa600;padding: 20px;margin: 20px 0;">
    Je ne parle intentionnellement pas de l'interface web d'InfluxDB habituellement disponible sur le port 8083 car celle-ci est <a  href="https://docs.influxdata.com/influxdb/v1.1/administration/differences/#deprecations">actuellement dépréciée et désactivée par défaut</a> (version 1.1) et disparaîtra des versions suivantes.
</div>

### Grafana

Par defaut **Grafana** est accesible sur le port ```3000``` avec pour identifiant et mot de passe "admin" / "admin". Vous accédez alors au "Home Dashboard". Avant toute chose il faut ajouter notre base de données InfluxDB comme source de données. Pour cela dans le menu, sélectionnez *Data Sources* puis *Add data source*. Nommez votre source, sélectionnez le type "InfluxDB", renseignez l'url ```http://localhost:8086``` et le nom de base de données ```telegraf```. Par défaut il n'y a pas d'identifiant ni de mot de passe sur la base de données. Cliquez sur *Save and test* et si tout va bien vous devriez obtenir le message *Data source is working*.

À partir de là vous pouvez créer votre premier *dashboard* (Menu > Dashboard > New). Pour avoir rapidement une base, vous pouvez également importer (Menu > Dashboard > Import) <a href="https://gist.github.com/maximecolin/ae5876ff844ce6a5dca95bc179bfa72d" target="_blank">cette configuration de dashboard</a> que j'ai initiée pour vous.

<figure>
    <img src="content/images/blog/2016/monitoring-grafana.jpg" alt="Dashboard Grafana de monitoring système" />
    <figcaption style="text-align:center;font-style:italic;">Dashboard Grafana de monitoring système</figcaption>
</figure>

## Pour aller plus loin

### Provisionner les datasources et les dashboards

Une fois que vous avez configuré vos *datasources* et créé vos *dashboards* vous souhaiterez peut-être de les intégrer à votre provisonning afin d'automatiser leur configuration. Le rôle ```manala.grafana``` permet celà.

Pour configurer une *datasource*, renseignez les mêmes informations que dans le formulaire de l'administration de **Grafana** :

```yaml
manala_grafana_datasources_exclusive: true
manala_grafana_datasources:
  - name:      telegraf
    type:      influxdb
    isDefault: true
    access:    proxy
    basicAuth: false
    url:       http://localhost:8086
    database:  telegraf
    username:  ''
    password:  ''
```

Pour configurer un *dahsboard*, indiquez le chemin vers le fichier d'export JSON du dashboard et renseignez les ```intputs``` utilisés par celui-ci :

```yaml
manala_grafana_dashboards_exclusive: true
manala_grafana_dashboards:
    - template: "{{ playbook_dir }}/templates/grafana/dashboards/system.json"
      inputs:
        - name:     "DS_TELEGRAF"
          pluginId: "influxdb"
          type:     "datasource"
          value:    "telegraf"
      overwrite: true
```

Cette configuration va associer l'*input* "DS_TELEGRAF" du dashboard à la source `influxdb` de type `datasource` nommée `telegraf`, c'est à dire la source créée juste au dessus. Si vous importez d'autres dashboards, prenez bien soin de regarder les *inputs* requis et associez-les de la même manière à vos `datasources`.

### Proxy pass

Si vous destinez votre instance de **Grafana** à des utilisateurs par forcement technique, il peut être intéressant d'avoir une url une peu plus sexy qu'un numéro de port à la fin de votre domaine. Vous pouvez opter pour un sous-domaine ou un chemin dédié en [plaçant **Grafana** derrière un reverse proxy](http://docs.grafana.org/installation/behind_proxy/).

Vous pouvez configurer un reverse proxy grâce au rôle [manala.nginx](https://github.com/manala/ansible-role-nginx).

Par exemple pour exposer **Grafana** sur l'url ```http://grafana.foobar.com``` :

```yaml
manala_nginx_config_template: config/http.{{ _env }}.j2

manala_nginx_configs_exclusive: true
manala_nginx_configs:
  # Grafana
  - file:     grafana.conf
    template: configs/server.{{ _env }}.j2
    config:
      - server_name: grafana.foobar.com
      - location /:
        - proxy_pass: http://localhost:3000
```

Il faut également indiquer le domaine à **Grafana** en ajoutant la configuration suivante :

```yaml
manala_grafana_config:
  - server:
    - domain: grafana.foobar
```

### Sécuriser Grafana

Une première mesure consiste à changer l'identifiant et le mot de passe administrateur de **Grafana** et désactiver la création de compte. Dans votre fichier de configuration **Ansible** :

```yaml
manala_grafana_config:
  - security:
    - admin_user: foobar
    - admin_password: foobar
  - users:
    - allow_sign_up: false
```

<div style="border-left: 5px solid #ffa600;padding: 20px;margin: 20px 0;">
    Attention, l'utilisateur administrateur est créé au premier démarrage de <strong>Grafana</strong>, vous ne pourrez donc pas changer son identifiant ou son mot de passe via la configuration après le premier provisioning. Vous devrez alors passer par la page <em>profile</em> dans l'interface de <strong>Grafana</strong>.
</div>

Il est possible de mettre en place des systèmes d'authentification tiers comme Google, GitHub ou votre propre OAuth.

Vous pouvez également changer le port de l'interface web qui est à ```3000``` par defaut :

```yaml
manala_grafana_config:
  - server:
    - http_port: 3000
```

Pour une configuration encore plus poussée, vous pouvez lire la [documentation de Grafana](http://docs.grafana.org/installation/configuration/).

### Sécuriser InfluxDB

Si vous laissez **Telegraf** créer sa propre base de données **InfluxDB**, celle-ci n'aura pas de mot de passe. Vous avez la possibilité de sécuriser votre base de données si vous le souhaitez, la configuration du rôle `manala.influxdb` permet de gérer les utilisateurs et les permissions.

Par exemple vous pouvez ajouter un utilisateur *telegraf* qui a les droit d'écriture/lecture et un utilisateur *grafana* qui n'a que le droit de lecture

```yaml
manala_influxdb_databases:
  - telegraf

manala_influxdb_users:
  - database: telegraf
    name:     telegraf
    password: nYhvEVsku
  - database: telegraf
    user:     grafana
    password: fCCWkXemR

manala_influxdb_privileges:
  - database: telegraf
    user:     telegraf
    grant:    ALL
  - database: telegraf
    user:     grafana
    grant:    READ
```

N'oubliez pas ensuite de renseigner les *username* et *password* dans la configuration de **Telegraf** :

```yaml
manala_telegraf_configs:
  - file:     output_influxdb.conf
    template: configs/output_influxdb.conf.j2
    config:
      - urls: ["http://localhost:8086"]
      - database: telegraf
      - username: telegraf
      - password: nYhvEVsku
```

et dans la *datasource* de **Grafana** :

```yaml
manala_grafana_datasources:
  - name:      telegraf
    type:      influxdb
    isDefault: true
    access:    proxy
    basicAuth: false
    url:       http://localhost:8086
    database:  telegraf
    username:  grafana
    password:  fCCWkXemR
```

## En production

### Diviser pour mieux régner

Pour l'article et dans un soucis de simplicité, je vous ai fait installer l'ensemble des outils sur le même serveur. Généralement, en production, on sépare le collecteur (Telegraf) de la persistance (InfluxDB) et de l'exploitation des données (Grafana) et ce pour des raisons de performance et de disponibilité.

Et oui, si votre serveur éprouve quelques difficulés, vous aimeriez bien qu'il n'en soit pas de même pour votre dashboard. Un monitoring qui tombe en panne en même temps que le serveur qu'il surveille n'a plus aucun intérêt.

Concernant les performances, selon la volumétrie de données que vous enregistrez, l'impact peut être non négligeable voire important. Il vaut donc mieux traiter les données sur un serveur à part.

### Configuration

Concernant l'installation, pas de grande différence, il suffit de reprendre le provisionning ci-dessus et de répartir sur deux playbook, l'un pour le serveur à monitorer avec Telegraf, l'autre pour le serveur de données avec InfluxDB et Grafana. La difficulté sera de permettre à Telegraf d'envoyer ses métriques à un autre serveur.

On va également en profiter pour passer du protocole `HTTP` au protocole `UDP` plus léger :

```yaml
manala_influxdb_config:
  - reporting-disabled: true
  - udp:
    - enabled: true
    - bind-address: :8090
    - database: telegraf
    - batch-size: 5000
    - batch-timeout: 1s
    - batch-pending: 10
    - read-buffer: 0
```

puis renseigner l'ip (ou le domaine) du serveur **InfluxDB** dans la configuration de **Telegraf** :

```yaml
manala_telegraf_configs:
  - file:     output_influxdb.conf
    template: configs/output_influxdb.conf.j2
    config:
      - urls: ["udp://123.234.56.78:8086"]
      - database: telegraf
      - username: telegraf
      - password: nYhvEVsku
```

<div style="border-left: 5px solid #ffa600;padding: 20px;margin: 20px 0;">
  Attention, il est recommandé de placer votre endpoint InfluxDB derrière un firewall si l'interface est publique et de le configurer pour accepter le traffic entrant pour le port 8086 uniquement depuis l'IP du serveur monitoré. Pour cela vous pouvez utiliser le role <a href="https://github.com/manala/ansible-role-shorewall">manala.shorewall</a>.
</div>

## Conclusion

Grâce aux rôles de [Manala](http://www.manala.io/) j'ai pu créer simplement le provisonning d'une stack de monitoring en [moins de 100 lignes de configuration](https://gist.github.com/maximecolin/acf6dd12dff72640a2b224cbb3934c4d). Au besoin, grâce au provisonning, je peux répliquer cette stack sur n'importe quel serveur en quelques minutes.
