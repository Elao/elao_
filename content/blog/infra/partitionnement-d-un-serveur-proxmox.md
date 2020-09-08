---

type:               "post"
title:              "Partitionnement d'un serveur proxmox"
date:               "2014-11-06"
publishdate:        "2014-11-06"
draft:              false
slug:               "partitionnement-d-un-serveur-proxmox"
description:        "Petit billet mémo aujourd'hui concernant le partitionnement d'un serveur Proxmox, rien de bien sorcier en soi mais il est toujours bon d'avoir un référentiel auquel se fier."

thumbnail:          "/images/posts/thumbnails/hdd_partition.jpg"
tags:               ["proxmox", "openvz", "partitionnement", "linux"]
categories:         ["Infra", "Proxmox", "Linux"]

author_username:    "gfaivre"

---

Bonjour à tous,

Petit billet mémo aujourd'hui concernant le partitionnement d'un serveur Proxmox, rien de bien sorcier en soi mais il est toujours bon d'avoir un référentiel auquel se fier.<!--more-->

Utilisant depuis un bon moment des solutions de virtualisation, nous avons pour habitude chez ELAO d'utiliser des containers OpenVZ via la distribution [Proxmox](https://www.proxmox.com/).

Nous prendrons pour l'exemple une des machines OVH, machines avec lesquelles nous avons l'habitude de travailler. Pour les moins exigeants le partitionnement par défaut proposé peut parfaitement faire l'affaire.

<p class="text-center">
    {{< figure src="/images/posts/2014/proxmox_default_partition_1000.png" title="Partitionnement par défaut OVH" alt="Partitionnement-d-un-serveur-proxmox - Partitionnement par défaut OVH" >}}
</p>

Il présente cependant un gros défaut qui est celui de ne pas laisser d'espace non partitionné à disposition de Proxmox dans le groupe LV, condition <i>sine qua non</i> pour pouvoir effectuer des sauvegardes de type "snapshot" de nos containers (ou de nos machines virtuelles).

Pour rappel le backup "à chaud" sous Proxmox fonctionne de la façon suivante:

- Proxmox crée une partition virtuelle à la volée.
- Il déclenche un premier rsync du container (ou de la VM) pour récupérer le "gros" des fichiers.
- Il déclenche un deuxième rsync afin de mettre à jour les fichiers ayant été modifiés entre le début et la fin du premier.
- Il crée une archive compressée qu'il stocke dans ```/var/lib/vz/dump```
- Il détruit la partition virtuelle initialement créée

Nous préférerons donc définir un partitionnement comme ci-dessous (La machine utilisée disposait d'un espace total de 3 To):

<p class="text-center">
    ![Partitionnement-d-un-serveur-proxmox - Partitionnement personnalisé](/images/posts/2014/proxmox_partition_elao_1000.png)
</p>

- 500 Mo pour ```/boot``` (Devrait amplement suffire à couvrir les MAJ kernel)
- 10 Go pour ```/```
- 2 * 1 Go pour le swap

Le reste est partitionné avec du LVM ce qui nous laisse une grande souplesse pour éventuellement réallouer de l'espace disque.

- 10 Go pour ```/tmp``` (Que l'on passe en tmpfs et pour laquelle on ajoute les options nodev, nosuid et noexec par sécurité)
- 1 To pour ```/var/lib/vz``` qui va recevoir les VMs et Containers
- 150 Go pour ```/var/lib/vz/dump``` (Dans la pratique je ne l'utilise pas et préfère utiliser les 500 Go de backup externe fourni avec OVH pour chaque serveur dédié monté en NFS)

Attention toutefois à conserver au moins 50 Go d'espace non partitionné pour pouvoir effectuer des snapshots, veuillez à adapter cette espace à la taille de votre plus grosse VM.

A cet espace nécessaire aux snapshots on gardera le reste de l'espace disque non partitionné pour pouvoir le redistribuer si jamais on se rend compte qu'une partition est trop petite.

Comme d'habitude, remarques et critiques de ce schéma sont plus que les bienvenues ;)
