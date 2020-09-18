---
type:               "post"
title:              "Introduction au provisioning"
date:               "2015-12-09"
publishdate:        "2016-02-03"
draft:              false

description:        "Introduction aux principes de base de l'approvisionnement (ou provisoning) d'environnements de développement, d'exécution, de production ou encore de test"

thumbnail:          "images/posts/thumbnails/puppet.jpg"
header_img:         "images/posts/headers/php_elao_code.jpg"
tags:               ["Ansible", "Linux", "Virtualisation"]
categories:         ["Infra", "Virtualisation", "Provisioning", "Ansible"]

author:    "gfaivre"

---

De plus en plus répandu au sein des services IT, l'approvisionnement (ou provisioning en anglais) devient une composante incontournable des environnements techniques. <!--more--> Depuis longtemps utilisé par les sysadmin (soit sous la forme de "simples" scripts shell ou via des technologies spécialisées), l'avènement des DevOps permet aujourd'hui de démocratiser son utilisation. On le retrouve ainsi de plus en plus au sein des environnements de développement comme de production.

La "culture" DevOps et plus largement AGILE repose sur des principes simples que sont l'automatisation, la réutilisabilité ou encore l'amélioration continue, principes auxquels le provisionning répond largement.

# C'est quoi le provisioning ?

Le provisioning c'est (globalement) l'automatisation de tâches variées et diverses allant de l'installation d'un environnement de développement à la mise en place d'infrastructures extrêmement complexes. Il a été historiquement et très largement utilisé dans l'industrie des télécoms.

Vous l'aurez compris il s'agit d'automatiser l'installation de certains logiciels, librairies... et d'assurer leur configuration de manière à garantir que notre environnement soit pleinement opérationel.

> ATTENTION il ne faut pas confondre **provisioning** et **déploiement applicatif**. En effet bien qu'il soit possible de déployer des applications avec des outils de provisioning, ceux-ci vont beaucoup plus loin puisqu'ils assurent la disponibilité de l'environnement technique d'une application.

# Pourquoi le « provisioning » ?

Le provisioning répond à un besoin à la fois de robustesse, de rapidité de déploiement et/ou de mise à disposition d'environnements plus ou moins complexes. Nous avons ainsi pour habitude chez [ELAO](https://www.elao.com) d'y avoir recours pour l'ensemble de nos environnements "du dev à la prod".

Le provisionning apporte plusieurs choses parmis lesquelles:
-----------------------------------------------------------

- L'automatisation de tâches parfois rébarbatives.
- La garantie de ne pas "oublier" un composant essentiel lors de l'installation d'un nouvel environnement.
- La facilité et la rapidité d'avoir à disposition un environnement parfaitement fonctionnel.
- La garantie d'avoir des environnements les plus identiques possibles.

# Quand automatiser ?

Les aficionados vous diront « *Tout le temps !* ».
La nécessité d'automatiser une tâche ou un traitement restant à la discretion du technicien (développeur, SysAdmin ...), chez [ELAO](https://www.elao.com) nous avons pour parti pris d'automatiser systématiquement tous les projets «Actifs» (Ceux où il y a une activité de développement, de TMA ou encore d'évolution fonctionnelle).
L'ensemble de nos infras de production et de demo ne dérogent pas à cette règle, ce fonctionnement nous permet d'avoir des environnements **homogènes**, facilement **scalables** et rapidement **reproduisibles**.

# « Provisionner » oui, mais avec quoi ?

Il existe plusieurs outils plus ou moins connus permettant d'automatiser. Quatre d'entres eux sont en «concurrence» directe, leur choix relevant autant du but poursuivi que de l'affinité de chacun avec ceux-ci.

- **Le script shell**

Historiquement il s'agit sans doute de la façon la plus ancienne d'assurer le provisioning de ses environnements. Difficilement maintenable et extrêmement spécifique, il tend à être délaissé au profit d'outils plus haut niveau.

- [**Chef**](http://www.chef.io/) - 2009 (Première révision stable)

L'un des plus connus il est écrit principalement en Ruby et en Erlang, il obéit à une organisation à base de « recipes » qui s'organisent sous la forme d'un « cookBook ».

- [**Puppet**](https://puppetlabs.com/) - 2005 (Première révision stable)

Concurrent direct de Chef il adopte la même approche, il est lui aussi écrit en Ruby et fonctionne sur la base de « manifests » ensuite compilés sous forme de « catalogs ».

- [**Salt**](http://saltstack.com/) - 2011 (Première révision stable)

Salt est écrit en Python et s'articule autour de modules spécifiques (Execution module, State module, Renderer module).

- [**Ansible**](http://www.ansible.com/) - 2012 (Première révision stable)

Ansible est le «petit dernier» si l'on peut dire, il est également écrit en Python et a l'avantage non négligeable d'être facilement accessible même pour des profils ayant peu de compétences et d'affinité avec l'administration système.

Il fonctionne à base de « roles » plus ou moins spécifiques et de « playbooks ». Il a la particularité d'être  « agentless » et ne nécessite donc pas l'installation d'un quelconque client avant de pouvoir fonctionner à la différence de Chef et Puppet.

C'est la solution que nous avons choisi d'utiliser chez [ELAO](https://www.elao.com).

# Et la virtualisation ?

Même si la virtualisation n'est pas un pré-requis, il est très commun d'utiliser des outils de provisioning sur des environnements virtualisés, c'est même là où ils trouvent tout leur intérêt.

En effet cette notion de virtualisation, couplée au provisioning, introduit dans des infras complexes la notion de « **jetabilité** » des instances, celles-ci étant très souvent redondées on peut envisager de les perdre (ou de les détruires) car elles sont  facilement recréables.
De même il devient beaucoup plus facile (attention je n'ai pas dit simple) d'assurer la scalabilité des applications, encore une fois car la mise à disposition des environnements techniques est plus rapide.

Il devient également de plus en plus commun de virtualiser les environnements de développement (nous utilisons par exemple le couple **Vagrant / Ansible**), ces outils permettant à un développeur d'être rapidement opérationel et de se concentrer sur l'essentiel de son métier, la création d'applications.

Loin d'être exhaustive sur le sujet, cette introduction reste ouverte à toute remarque et/ou complément.
