---
type:               "post"
title:              "Opération sur un fichier avec la commande find"
date:               "2009-12-03"
publishdate:        "2009-12-03"
draft:              false
slug:               "operation-sur-un-fichier-avec-la-commande-find"
description:        "Opération sur un fichier avec la commande find"

thumbnail:          "/images/posts/thumbnails/linux_fix.jpg"
tags:               ["Linux", "Tips"]
categories:         ["Infra", "Linux"]

author_username:    "gfaivre"

---

La commande "find" permet non seulement de faire des recherches sur les fichiers de l'arborescence mais également d'exécuter des commandes sur ces mêmes fichiers. Option au combien pratique pour toutes les tâches de maintenance / nettoyage des systèmes !

{{< highlight bash >}}

find . -name ’*.yml’ -exec rm {} ;
{{< /highlight >}}

**Explications :**

La commande ci-dessus va rechercher dans le répertoire courant tous les fichiers correspondant au masque suivant l'option *-name* et exécuter la commande *rm* sur le résultat. Cette simple commande permet donc de supprimer **tous** les fichiers de l'arborescence et de la sous-arborescence du répertoire courant suffixés par ***.yml***.

**Syntaxe :**

**.** représente le répertoire courant il aurait pu être par exemple */etc/mon_rep/ *

***-name*** est une option de find permettant de faire une recherche à partir d'un **masque** ou **pattern** en anglais.

`*.yml` est le masque représentant tous les fichiers suffixés par .yml

***-exec*** est une option de find qui entraine l'exécution de la commande qui la suit

***rm*** est la commande UNIX permettant la suppression d'un fichier

{} représente touts les occurences de fichiers correspondantes au masque

***; ***termine la chaine de commande. *(Ne pas oublier de le protéger avec un backslah afin qu'il soit interprété correctement)*
