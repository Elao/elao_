---
type:               "post"
title:              "Créer un cluster 2 nodes Proxmox"
date:               "2015-01-16"
publishdate:        "2015-01-16"
draft:              false
slug:               "creer-un-cluster-2-nodes-proxmox"
description:        "Rapide présentation d'une fonctionnalité intéressante des distributions Proxmox qui permet de faire du clustering avec deux ou plusieures machines physiques."

thumbnail:          "/images/posts/thumbnails/matryoshka.jpg"
tags:               ["proxmox", "openvz", "cluster"]
categories:         ["Infra", "Proxmox", "Cluster"]

author_username:    "gfaivre"

---

Bonjour à tous,

Aujourd'hui nous allons aborder une fonctionnalité intéressante des distributions Proxmox qui permet de faire du "clustering" avec deux ou plusieures machines physiques.

Pour aujourd'hui nous verrons une notion simple de cluster sans haute disponibilité basé sur 2 machines physiques seulement. Cette configuration est intéressante lorsqu'il n'y a pas un réel besoin de haute disponibilité, mais un besoin de répartition de charge et/ou de reprise de service anticipé en cas d'incident.

Le "cluster" Proxmox permet notamment de faire de la migration de container d'une machine à une autre "à chaud", de gérer l'ensemble de ces containers à partir de l'un ou l'autre des hyperviseurs ... bref rien d'indispensable en soit mais pas mal de petites choses foutrement utiles.

Pour ceux d'entres vous qui souhaitent faire ça entre deux data-centres différents je ne saurais que trop vous recommander la solution vRack fournie par OVH qui remplacera de manière simple, fiable et sécurisée les bons vieux VPN.

## Installer Promox

<img src="https://placeimg.com/300/300/any" alt="" class="outside-left" />
Rien de surprenant ici, la première étape consistera à installer une distribution Proxmox sur vos deux machines.

L'installation de base de la distribution peut convenir pour tester la solution mais dans le cas d'une exploitation un peu plus poussée je vous conseille de jeter un oeil à [**ce post**](http://www.elao.com/fr/blog/partitionnement-d-un-serveur-proxmox) qui concerne le partitionement des machines.

## Préparer le réseau

<img src="https://placeimg.com/400/400/any" alt="" class="outside-right" />
Condition indispensable à nos machines pour qu'elle puisse fonctionner sous forme de cluster, il faut qu'elles soient capables de faire du "[multicast](http://fr.wikipedia.org/wiki/Multicast)".

Le premier piège à éviter est donc celui de s'assurer que les deux machines peuvent dialoguer sur un même réseau sécurisé. C'est là qu'entre en jeu la technologie [vRack](https://www.ovh.com/fr/solutions/vrack/) d'OVH ou un bon vieux VPN.

En imaginant que nous ayont configuré un réseau local (privé) 172.16.0.0 nous avons donc deux machines:

- Tartanpion, ayant pour IP publique 87.37.23.91 et pour IP privée 172.16.0.1
- Bismuth, ayant pour IP publique  76.32.45.12 et pour IP privée 172.16.0.2

Sous cette forme, une légère modification au niveau du fichier ```/etc/hosts``` de chacune de vos machines est nécessaire.
Avec une configuration standard nous aurons donc dans ce fichier (pour tartanpion)

```
127.0.0.1   localhost.localdomain localhost
87.37.23.91 tartanpion.elao.local tartanpion
```

que nous allons modifier pour avoir:

```
127.0.0.1   localhost.localdomain localhost
87.37.23.91 tartanpion.elao.local

172.16.0.1  tartanpion
```

Avec cette configuration notre noeud Proxmox (tartanpion) s'auto résoudra bien en utilisant son IP privée lors du dialogue avec le deuxième noeud (bismuth).
Cette modification a pour but de "forcer" les hyperviseurs à dialoguer entre eux via leur IP privée de manière sécurisée, mais également de pouvoir faire du multicast sur ce réseau.
La modification est bien entendu, à faire sur les deux noeuds qui constituerons notre futur cluster (cette modification est valable pour l'ensemble des machines membres de votre cluster).

<img src="https://placeimg.com/640/280/any" alt="" class="full" />

## Créer un cluster proxmox et y ajouter des noeuds

La création d'un cluster et l'ajout d'un ou plusieurs noeuds ne posent en soit guère de problème, il faudra toutefois faire attention à la notion de quorum dans notre cluster (nous en parlerons plus tard).

Sur l'un des deux hyperviseurs (peu importe lequel), créons le cluster ayant pour nom "elao":

```
pvecm create elao
```

On peut consulter son bon fonctionnement avec la commande ```pvecm status```, qui devrait nous renvoyer les informations suivantes:

```
Version: 6.2.0
Config Version: 1
Cluster Name: elao
Cluster Id: 29348
Cluster Member: Yes
Cluster Generation: 48
Membership state: Cluster-Member
Nodes: 1
Expected votes: 1
Total votes: 1
Node votes: 1
Quorum: 1
Active subsystems: 5
Flags:
Ports Bound: 0
Node name: tartanpion
Node ID: 1
Multicast addresses: 239.192.114.23
Node addresses: 172.16.0.1
```

A partir de notre deuxième noeud (bismuth) nous allons procéder à son ajout au cluster avec la commande ```pvecm add 172.16.0.1```

Si tout s'est bien passé la commande ```pvecm nodes``` doit à présent renvoyer quelquechose semblable à ce qui suit:

```
Node  Sts   Inc   Joined               Name
   1   M     48   2015-01-16 14:25:27  tartanpion
   2   M     44   2015-01-16 14:25:27  bismuth
```

## Eviter la problématique du Quorum

Très important pour la suite nous allons effectuer une manipulation pour éviter de se retrouver avec un hyperviseur en "lecture seule" dans le cas ou le deuxième viendrait à rencontrer un problème (réseau, matériel ...).

> Pour ceux qui sont intéressés par la notion de haute disponibilité je vous invite à jeter un oeil à ce qu'est le [**quorum**](http://en.wikipedia.org/wiki/Quorum) qui est une notion importante de la HA.

Pour faire rapide le fonctionnement d'un cluster haute disponibilité fonctionne à partir de ce principe de quorum qui représente le nombre minimal de vote lors d'une délibération pour prendre une décision.
Dans notre cas avec 2 machines nous ne pouvons pas avoir de quorum lors d'un incident, nos noeuds vont donc passer en lecture-seule pour éviter de se retrouver dans un état de ["**split-brain**"](http://en.wikipedia.org/wiki/Split-brain_(computing)) (écriture sur les différents noeuds sans concertation). Etat désastreux pour une infra, qui peut amener une perte de données plus ou moins importante.

<img src="https://placeimg.com/640/480/any" alt="" class="center" />

Pour l'instant un ```pvecm status``` doit retourner quelque chose comme:

```
Version: 6.2.0
Config Version: 2
Cluster Name: elao
Cluster Id: 29348
Cluster Member: Yes
Cluster Generation: 48
Membership state: Cluster-Member
Nodes: 2
Expected votes: 1
Total votes: 2
Node votes: 1
Quorum: 2
Active subsystems: 5
Flags:
Ports Bound: 0
Node name: tartanpion
Node ID: 1
Multicast addresses: 239.192.114.23
Node addresses: 172.16.0.1
```
Nous allons donc indiquer à notre cluster que nous acceptons de fonctionner avec un Quorum réduit, la haute disponibilité n'étant pas la question  de ce post.

Editez le fichier ```/etc/pve/cluster.conf```, commencez par incrémenter le numéro de version du fichier que vous trouverez au niveau de la clé ``` config_version```.

Une fois cela fait nous allons modifier l'entrée **cman** pour y ajouter les instructions ```two_node="1" expected_votes="1"```

Votre fichier doit donc à présent ressembler à ceci:

```xml
<?xml version="1.0"?>
<cluster name="elao" config_version="5">

  <cman keyfile="/var/lib/pve-cluster/corosync.authkey" two_node="1" expected_votes="1">
  </cman>

  <clusternodes>
  <clusternode name="tartanpion" votes="1" nodeid="1"/>
  <clusternode name="bismuth" votes="1" nodeid="2"/></clusternodes>

</cluster>
```

Redémarrer à présent votre cluster avec un  ```/etc/init.d/pve-cluster restart``` vous devriez avoir à présent comme résultat à ```pvecm status```

```
Version: 6.2.0
Config Version: 6
Cluster Name: elao
Cluster Id: 29348
Cluster Member: Yes
Cluster Generation: 48
Membership state: Cluster-Member
Nodes: 2
Expected votes: 1
Total votes: 2
Node votes: 1
Quorum: 1
Active subsystems: 5
Flags: 2node
Ports Bound: 0
Node name: tartanpion
Node ID: 1
Multicast addresses: 239.192.114.23
Node addresses: 172.16.0.1
```
On notera le flag ```2node```, nous voila avec un cluster à 2 noeuds fonctionnel !

## Failed to access mount point

Il est possible qu'au cours du redémarrage du cluster vous rencontriez le message suivant:

```
Starting pve cluster filesystem : pve-clusterfuse: failed to access mountpoint /etc/pve: Transport endpoint is not connected
[main] crit: fuse_mount error: Transport endpoint is not connected
[main] notice: exit proxmox configuration filesystem (-1)
 (warning).
```

Ce petit soucis peut être rapidement résolu grâce à la commande ``` umount -f /etc/pve```, redémarrez ensuite votre cluster ```/etc/init.d/pve-cluster start``` et tout devrait rentrer dans l'ordre.

Comme à l'accoutumé cet article est ouvert à toutes critiques, suggestions et corrections.
