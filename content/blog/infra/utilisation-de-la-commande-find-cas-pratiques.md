---
type:               "post"
title:              "Utilisation de la commande find - cas pratiques"
date:               "2010-04-23"
publishdate:        "2010-04-23"
draft:              false

description:        "Utilisation de la commande find - cas pratiques"

thumbnail:          "/images/posts/thumbnails/homer-do-not-care.png"
tags:               ["Linux", "Trucs et astuces", "Tips"]
categories:         ["Infra", "Linux"]

author:    "gfaivre"

---

La commande find, outils ô combien indispensable des administrateurs systèmes, permet d'effectuer et d'automatiser tout un tas de tâches de maintenance courantes.<!--more-->

Ce petit memo se veut être un post de centralisation des lignes de commande pratiques, pouvant dépanner l'admin en détresse !

### Rechercher un fichier

```
find . -name "databases*"
```

Cette commande renverra tous les fichier du répertoire courant **ET** de ses sous répertoires dont le nom correspond au masque suivant l'option **-name**.

### Rechercher tous les fichiers de type répertoire

```
find . -name "config" -type d
```

### Lister le contenu de répertoires correspondant à un masque

```
find . -type d -name "config" -exec ls -l {} \;
```

### Modifier les droits des fichiers de type répertoire

```
find . -type d -exec chmod g+x {} \;
```

Commande très utile pour donner les droits d'exécution aux seuls répertoires par exemple.

### Recherche à l'aide d'opérateurs logiques

```
find /tmp \( ! -user webmaster \)
```

Cette commande renverra tous les fichier de /tmp n'appartenant pas à l'utilisateur webmaster

```
find / \( -type d -a -user webmaster \)
```

Cette commande renverra tous les fichiers de type répertoire **ET** (option -a) qui appartiennent à webmaster

```
find / \( -name a.out -o -name ’*.o’ \) -atime +7 -exec rm {} \;
```

Cette commande supprimera tous les fichiers dont le nom correspond est a.out **OU** se terminant par .o **ET** dont la date de dernier accès est antérieure à 7 jours.
