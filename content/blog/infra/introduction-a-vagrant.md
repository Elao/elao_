---
type:               "post"
title:              "Introduction à Vagrant"
date:               "2017-04-24"
publishdate:        "2017-04-23"
draft:              false

description:        "Le fonctionnement et les principes utilisés par Vagrant. Comment installer une VM contenant une version minimale d'Ubuntu et la provisionner avec nginx."

thumbnail:          "images/posts/thumbnails/vagrant.png"
header_img:         "images/posts/headers/vagrant.png"
tags:               ["Vagrant", "Virtualisation"]
categories:         ["Infra", "Virtualisation", "Vagrant"]

author:    "mbernard"

---

Vagrant est un outil qui simplifie la création et la gestion d’environnements virtualisés.
Si par le passé on avait recours à des plateformes comme LAMP/MAMP/XAMP pour gérer son environnement de développement, <!-- more --> cela posait souvent problème car les versions des dépendances logicielles pouvaient varier une fois l’application déployée sur un environnement externe, notamment en production. On se retrouvait alors à perdre un temps considérable pour corriger des bugs en production qui n’étaient pas reproductibles en développement, et inversement.

Vagrant permet de travailler dans un environnement reproductible avec les mêmes versions pour toutes vos dépendances. Parce qu’il utilise le principe de virtualisation, il est donc compatible avec la majorité des OS actuels. Ainsi, un développeur travaillant sous Mac aura accès au même environnement qu'un développeur sous Windows ou Linux tout en conservant son IDE favori.

# Comment ça fonctionne ?

Vagrant est en réalité une surcouche autour de solutions de virtualisation comme Virtualbox ou VMware. Il exécute une série d'instructions spécifiées dans un fichier de configuration (le Vagrantfile) pour créer et configurer une ou plusieurs machines virtuelles.

> A noter que Vagrant n’est pas un outil de provisioning, il ne chargera pas directement vos dépendances logicielles dans la VM. La solution de base consiste à lui fournir un script shell contenant les instructions nécessaires au provisionnement. Par la suite, il sera intéressant de l'associer à une solution comme Ansible, Puppet ou Chef qui se chargera d'automatiser ce processus. Voir l'article [introduction au provisioning](/fr/infra/introduction-au-provisioning/)

# Les concepts sur lesquels repose Vagrant

**Le Vagrantfile**

Le fichier de configuration principal de Vagrant. Ecrit en ruby, il sert à définir le dossier racine du projet qui sera utilisé par les options de configuration de Vagrant. C'est ausssi dans ce fichier que l'on définira le type de machine (la box) souhaité, la configuration réseau, le provider, la méthode de provisionnement ainsi que d'éventuels plugins.

**Les boxes**

Vagrant utilise des boites prêtes à l’emploi pour accélérer la construction de son environnement virtuel. Le principe est similaire aux images sous [Docker](https://www.docker.com/). Une vagrant box peut contenir une simple installation d'Ubuntu, mais elle peut aussi embarquer d'autres applications pré-installées. Si vous utilisez régulièrement les mêmes briques applicatives pour vos projets, il peut être intéressant de les compiler dans une box que vous pourrez réutiliser par la suite.

Vous trouverez une liste de boxs sur [Atlas](https://atlas.hashicorp.com/boxes/search) le catalogue de boxs officiel de Vagrant. A titre d'exemple, voici [la box standard](https://atlas.hashicorp.com/manala/boxes/app-dev-debian) que nous utilisons chez Elao pour initier nos projets.

**Le Provider**

Comme vu plus haut, Vagrant ne virtualise pas directement les environnements mais fait appel à un provider pour accomplir cette tache.
Vagrant utilise [Virtualbox](https://www.virtualbox.org/) par défaut, mais rien n’empêche d’en utiliser un autre comme VMWare, AWS ou Hyper V.

# En pratique

Nous allons maintenant installer une VM contenant une version minimale d'Ubuntu et la provisionner avec nginx à l'aide d'un script bash. Après avoir installé Vagrant sur votre machine, créez un nouveau dossier que nous appellerons `demo/`. Créez ensuite un nouveau fichier Vagrantfile avec le contenu suivant.

```
Vagrant.configure("2") do |config|

  # Vm
  config.vm.box = "hashicorp/precise32"
  config.vm.synced_folder ".", "/vagrant"

  # Provision
  config.vm.provision :shell, path: "bootstrap.sh"

  # Network
  config.vm.network "forwarded_port", guest: 80, host: 8080, id: "nginx"

end
```

On peut voir que la box utilisée est **hashicorp/precise32** et que le dossier partagé (sur la VM) avec la racine du projet (sur votre machine locale) se nomme **vagrant**. La ligne suivante spécifie la méthode de provisionnement (un script shell). La dernière ligne de configuration sert à forwarder le port 80 de la VM vers le port 8080 de votre machine locale, ce qui permettra d'avoir accès au serveur nginx depuis votre navigateur.

Notre script **bootstrap.sh** contiendra les lignes suivantes:

```
sudo apt-get -y update
sudo apt-get -y install nginx
sudo service nginx start
```

Il est temps de lancer cette commande dans votre terminal et laisser la magie opérer. Lors de cette phase,
Vagrant va installer votre VM, la provisionner à l'aide du script et configurer le port forwarding.

```
vagrant up
```

Si tout s'est bien passé, nous pouvons maintenant nous connecter en ssh sur notre VM

```
vagrant ssh
```

et ainsi voir le prompt suivant.

```
Welcome to Ubuntu 12.04 LTS (GNU/Linux 3.2.0-23-generic-pae i686)

 * Documentation:  https://help.ubuntu.com/
New release '14.04.5 LTS' available.
Run 'do-release-upgrade' to upgrade to it.

Welcome to your Vagrant-built virtual machine.
Last login: Wed Apr 19 08:52:49 2017 from 10.0.2.2
vagrant@precise32:~$
```

Pour terminer, entrez l'addresse *http://localhost:8080/* dans votre navigateur, vous devriez voir la page d'accueil de nginx, indiquant que vous avez bien accès au serveur web de votre VM.

# Conclusion

En résumé, Vagrant est une solution fiable pour instancier rapidement un environnement de développement et le partager entre développeurs. L'exemple que nous avons vu reste simple, mais il est possible de faire bien des choses avec Vagrant, comme du provisioning à l'aide d'**Ansible** (ce que nous utilisons chez Elao) ou de la configuration réseau plus poussée en passant par un réseau privé et un serveur DNS comme **landrush**.

Si les sujets de l'automatisastion et de la virtualisation vous intéressent, je vous invite à aller faire un tour sur le projet [Manala](http://www.manala.io/), la boite à outils pour Ansible maintenue par Elao.

N'hésitez pas à poster vos questions ou remarques dans la section commentaires.


sources:
https://www.vagrantup.com/docs/
http://blog.scottlowe.org/2014/09/12/a-quick-introduction-to-vagrant/
