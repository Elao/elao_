---
type:               "post"
title:              "Installation d'un dépôt SVN"
date:               "2009-12-02"
lastModified:       ~

description:        "Installation d'un dépôt SVN pour gérer et versioner le code source d'un projet"

thumbnail:          "images/posts/thumbnails/kenny.jpg"
tags:               ["Linux", "SVN"]
categories:         ["Infra", "Linux"]

authors:            ["gfaivre"]

---

**[EDIT]** Il est bien évidemment conseillé aujourd'hui d'utiliser GIT comme SCM ! Cet article est à considérer comme une archive et/ou comme support à ceux qui ont la malchance d' être coincé avec SVN.

**Subversion** (en abrégé **svn**) est un <a title="Système de gestion de versions" target="_blank" href="http://fr.wikipedia.org/wiki/Syst%C3%A8me_de_gestion_de_versions">système de gestion de versions</a>, il agit sur une arborescence de fichiers afin de conserver toutes les versions des fichiers, ainsi que les différences entre les fichiers. De ce fait, et pour résumer, il permet non seulement de pouvoir revenir à un état T d'une application dans le temps mais également de travailler à plusieurs sur un seul et même projet, les conflits pouvant être engendrés entre deux utilisateurs manipulant un même fichier étant gérés par le serveur subversion.

### Les paquets à installer:

```
apt-get install subversion libapache2-svn
```

### Déploiement du dépôt subversion:

Nous partirons du principe que vous avez décidé de déployer le repository (dépôt) de votre projet dans le répertoire /repositories/. Si ce n’est pas fait nous créons le répertoire:

```
mkdir /repositories
```

Nous allons ensuite créer notre dépot à l’aide de la commande svn:

```
svnadmin create /repositories/mon_projet/
```

En règle générale la structure d’un dépôt svn est subdivisée en trois répertoires:

* trunk:
* branches:
* tags:

Si vous disposez déjà d’un existant vous pouvez parfaitement l’importer dans votre nouveau dépôt, dans le trunk:

```
svn import -m "Initialisation du dépot" /elao/projects/mon_projet/ file:///repositories/mon_projet/trunk
```

### Paramétrage du subversion et Apache

Afin “d’ouvrir” notre dépôt vers l’extérieur nous allons utiliser Apache, pour des raisons de sécurité, (libre à vous de l’utiliser ou non ) nous utiliserons SSL pour les transactions http. On active le module dav_svn avec la commande :

```
a2enmod dav_svn
```

### Paramétrer votre VHost (à adapter selon votre configuration Apache):

Nous allons commencer par définir quels seront les utilisateurs qui auront accès à notre (nos) dépôt(s) Créons pour celà un répertoire pwd, qui, d’une manière générale contiendra nos fichiers d’accès.

```
mkdir /etc/apache2/pwd
cd pwd
```

La création d’un fichier utilisateur se fait à l’aide la commande htpasswd

```
htpasswd -c users.pwd guewen
```

A noter que l’option -c indique que le fichier doit être créé, celle-ci n’est plus nécessaire une fois que celui-ci existe, ainsi pour ajouter ou mettre à jour un utilisateur on utilisera simplement la commande:

```
htpasswd  users.pwd guewen
```

La configuration Apache se fait comme suit:

```apacheconf
ServerName              svn.domain.tld
DocumentRoot            /mon_domaine/repositories/

SSLEngine on
SSLCertificateFile      /etc/apache2/ssl/host.crt
SSLCertificateKeyFile   /etc/apache2/ssl/host.key

DAV svn
SVNParentPath           /mon_domaine/repositories/
AuthType Basic
AuthName                "ELAO Private Area"
AuthUserFile            /etc/apache2/pwd/users.pwd
AuthzSVNAccessFile      /etc/apache2/pwd/perms.acl
Require                 valid-user
```

### La gestion des droits:

Les droits sur les différents dépôts svn seront gérés via le fichier perms.acl, l’utilisation d’ACL va nous autoriser à mettre en place des accès complexes basés sur le couple utilisateur/groupe. Il est cependant à noter que l’utilité d’une stratégie d’authentification complexe ne présente d’interet que si votre dépôt est publique ou nécéssite un contrôle strict des accès. Qui dit ACL dit utilisateurs, groupes et bien sur permissions ! Nous allons stocker cette configuration dans le fichier perms.acl. On va commencer par définir les groupes:

```
[groups]
admins = user1, user2
developpers = user1, user2, user3, user6
public = guest
all = admins, developpers, public
```

N’oubliez pas de définir les utilisateurs dans le fichier users.pwd ! Une fois vos groupes définis on passe à la configuration des droits sur les dépôts. Il faut savoir que l’on peut non seulement définir des droits sur un dépôt mais également une partie précise du dépôt. Ainsi nous pouvons fixer des droits de lecture (r) pour tout le monde au niveau de la racine du dépôt, par contre les droits d’écriture ne sont ici accordés qu’aux développeurs.

```
[depex:/]
@all = r

[depex:/trunk]
user2 = r
@developpers = rw
```

Attention à toujours préfixer le nom des groupes par un ‘@’
