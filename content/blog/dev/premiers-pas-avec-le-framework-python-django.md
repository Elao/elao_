---
type:               "post"
title:              "Premiers pas avec le framework Python \"Django\""
date:               "2010-09-15"
publishdate:        "2010-09-15"
draft:              false
slug:               "premiers-pas-avec-le-framework-python-django"
description:        "Premiers pas avec le framework Python \"Django\""

thumbnail:          "/images/posts/thumbnails/first_step.jpg"
tags:               ["Django", "Framework", "MVC", "Python"]
categories:         ["Django", "Python"]

author_username:    "xroldo"
---

Bonjour à toutes et à tous,

Aujourd'hui, nous allons faire la connaissance de <a href="http://www.djangoproject.com/">Django</a>, le framework Web à l'usage des perfectionnistes soumis à des délais (ce n'est pas moi qui le dis, ce sont les pères fondateurs du Framework eux-mêmes !).

Il s'agira d'une simple initiation au framework, destinée essentiellement aux développeurs qui souhaitent, comme moi, découvrir l'outil à travers un cas concret d'une affligeante banalité, puisque nous allons nous limiter pour l'heure à un très modeste "Hello, World". Mais rassurez-vous, au-delà de cet exemple simple, pour ne pas dire simplet, ce sera surtout l'occasion d'aborder par la pratique quelques concepts-clés du Framework. A mesure que nous avancerons dans ce projet (d'une ambition démesurée), nous nous attarderons sur certains aspects du framework, parfois pour les comparer aux autres frameworks Web. Enfin, je n'exclus pas de rédiger d'autres articles sur Django s'il s'avère que ce billet reçoit quelques échos favorables.

**A qui s'adresse ce tutoriel ? Quels sont les pré-requis pour suivre ce tutoriel ? Je n'y connais rien en Python, est-ce que ça vaut la peine que je poursuive la lecture de cet article ? Et puis qu'ai-je à gagner à découvrir un nouveau framework, le mien me convient parfaitement, merci ! Je ne connais pas non plus les autres frameworks, ai-je intérêt à lire ce billet ?**

Houlala, nous ne sommes pas encore entrés dans le vif du sujet que déjà les questions fusent de toute part ! Votre enthousiasme fait plaisir à voir.

Bon, vu la manière dont j'ai introduit le sujet, vous vous doutez bien que ce tutoriel s'adresse avant tout à des développeurs qui ne connaissent absolument pas Django ou qui, comme moi, en ont vaguement entendu parler mais souhaitent creuser la question. Si vous vous targuez de maîtriser l'outil, vous risquez fort de vous ennuyer à la lecture de cet article. Pour tirer pleinement profit de ce tutoriel, il est préférable de posséder quelques notions de développement, telles que le modèle MVC (Modèle-Vue-Contrôleur) ou la programmation objet. En revanche, si vous ne connaissez pas d'autre framework, ni le langage Python, cela ne devrait pas être un frein à la compréhension du tutoriel. Je mentionnerai sans doute d'autres frameworks tout au long de cet article mais uniquement dans le but d'apporter un éclairage supplémentaire. Quant au langage Python, je l'ai moi-même découvert en même temps que Django, la méconnaissance de ce langage ne semble donc pas constituer un obstacle insurmontable pour découvrir Django.

Enfin, pour suivre ce tutoriel, il vous faudra, et c'est sans doute le principal pré-requis, disposer d'un ordinateur sur lequel sont installés Python et Django ! Si ce n'est pas votre cas, pas de panique, l'installation des outils nécessaires fait justement l'objet du prochain chapitre.

### Installation de Python et Django

L'installation de Python et de Django ne pose pas de problème particulier, aussi vais-je aborder cet aspect de manière très succincte.

Tout d'abord, il faut savoir que la version actuelle de Django (1.2) requiert Python dans ses versions 2.4 à 2.7. Bonne nouvelle pour les utilisateurs d'une distribution Linux ou de MacOS, il est fort probable que Python soit déjà installé sur votre machine. Pour vous en assurer, ouvrez une console et saisissez simplement la commande suivante :

```
python
```

Vous devriez alors voir apparaître une invitation de commande Python assez semblable à celle-ci :

```bash

Python 2.5.2 (r252:60911, Jan 20 2010, 23:16:55)
[GCC 4.3.2] on linux2
Type 'help', 'copyright', 'credits' or 'license' for more information.
>>>
```


Si ce n'est pas le cas, ou si vous utilisez l'OS Windows, il vous suffira de télécharger Python et l'installer :

> Télécharger Python : <a href="http://www.python.org/download/">http://www.python.org/download/</a>
> **Rappel** : prenez soin d'installer une version 2.x de Python. En effet, à l'heure où j'écris ces lignes, la dernière version de Python est la 3.1.2 mais **Django est pour l'heure incompatible avec les versions 3.x de Python**.
>
> Nota : A partir de la version 2.5, Python inclut Sqlite dans sa distribution. Privilégiez donc une version supérieure ou égale à 2.5.

A présent, il est temps de télécharger et installer Django :

> Télécharger Django : <a href="http://www.djangoproject.com/download/">http://www.djangoproject.com/download/</a>
> Documentation d'installation : <a href="http://docs.djangoproject.com/en/1.2/topics/install/#installing-official-release">http://docs.djangoproject.com/en/1.2/topics/install/#installing-official-release</a>
>
> Nota : Pour les utilisateurs de distributions Linux, il est probable que votre gestionnaire de paquetages vous permette d'installer Django. Assurez-vous néanmoins de bien installer la version 1.2, sur laquelle se base ce tutoriel.

Si vous avez opté pour le téléchargement de l'archive de la distribution Django, l'installation consiste principalement à décompresser le contenu de l'archive dans un répertoire temporaire et à lancer le script d'installation. Voici un exemple en partant du principe que votre archive se trouve dans le répertoire ~/Temp :

```python

cd ~/Temp
tar -zxvf Django-1.2.1.tar.gz
cd Django-1.2.1
sudo python setup.py install
```


L'installation devrait se dérouler normalement.

Le script Django permettant d'initialiser un projet s'appelle **django-admin.py**. Il se trouve dans votre distribution Django fraîchement installée. Normalement, lors de l'installation, Python a dû mettre à jour le PATH pour rendre ce script disponible depuis n'importe quel emplacement. Pour nous en assurer, lançons les commandes suivantes :

```
cd ~
django-admin.py
```

Si tout s'est déroulé correctement, vous devriez obtenir le résultat suivant (et notamment la liste des sous-commandes Django disponibles) :

```bash

Usage: django-admin.py subcommand [options] [args]
      .....
Type 'django-admin.py help <subcommand>' for help on a specific subcommand.

Available subcommands:
  cleanup
  compilemessages
  createcachetable
  dbshell
  diffsettings
  ...
```


Si vous n'obtenez pas le résultat escompté, il va falloir rendre le script disponible. Pour les utilisateurs de Linux, cela consistera par exemple à créer un lien symbolique pointant sur ce fichier pour y accéder depuis n'importe quel emplacement. Quant aux utilisateurs de Windows, il leur suffira de copier ce fichier dans un répertoire inclus dans la variable d'environnement PATH, ou bien encore modifier la variable PATH en y ajoutant le chemin du répertoire parent du fichier **django-admin.py**

**Il est bien gentil, lui, mais elle se trouve où cette fichue distribution ? Comment je fais pour le retrouver ce fameux fichier?**

> Pour connaître l'emplacement de vos paquetages Python sur votre disque dur, ouvrez une console (Linux, MacOS) ou une fenêtre DOS et saisissez l'instruction suivante : [php]python -c &#8216;from distutils.sysconfig import get_python_lib; print get_python_lib()' [/php]
> C'est dans le répertoire qui s'affiche que se trouvent tous les paquets Python, parmi lesquels votre distribution Django. Exemple d'emplacement du fichier django-admin.py dans une distribution Linux :
>
> ```/usr/lib/python2.7/site-packages/django/bin/django-admin.py```

Création d'un lien symbolique sous Linux ou MacOS :

```
ln -s /usr/lib/python2.7/site-packages/django/bin/django-admin.py /usr/local/bin
```

Maintenant que tout le monde est là, nous pouvons aborder la phase la plus intéressante : la création de notre premier projet Django !

### Création d'un projet Django

A présent, nous disposons d'un environnement de développement qui nous permet de créer notre premier projet Django. Pour cela, nous allons lancer le script Django de création d'un projet, le fameux **django-admin.py**.

> La question que l'on se pose constamment lorsque l'on utilise un framework permettant notamment de générer l'arborescence d'un nouveau projet (Ruby on Rails, Symfony, Grails, Zend_Tool ...) est : "**Dois-je créer le répertoire qui va contenir mon projet avant de lancer la commande ou est-ce le script du framework qui s'en charge ?**" <p>
> Dans le cas de Django, lorsque l'on exécute la sous-commande de création d'un nouveau projet, **un nouveau répertoire portant le nom du projet est créé** et les fichiers générés sont placés à la racine de ce nouveau répertoire.

Nous allons donc ouvrir (si ce n'est pas déjà fait) un terminal, nous placer dans le répertoire contenant nos projets Django (dans mon cas, ce répertoire s'appelle "**workspace-django**", il est situé dans ma "home") et exécuter le script de création du projet :

```python

cd ~/workspace-django
django-admin.py startproject monprojet
# Ca y est, le projet est initialisé, allons voir ce qu'il contient :
cd monprojet
ls -l
```


> Voici le contenu du projet immédiatement après sa création  <p>
> (1) __init.py__
> (2) manage.py
> (3) settings.py
> (4) urls.py
>
> **(1)** Ce fichier sert à indiquer à Python que le répertoire courant est un **package** Python. Nous reviendrons brièvement sur la notion de package et de module en Python. Sachez juste pour l'heure que nous ne modifierons pas ce fichier.
> **(2)** Pour schématiser, c'est un **wrapper**, un raccourci en quelque sorte, vers le script original **django-admin.py**, placé ici pour des raisons pratiques. Désormais, c'est ce script que nous invoquerons pour exécuter des commandes Django. Nous ne modifierons pas ce fichier.
> **(3)** C'est dans ce fichier que nous définirons la configuration de notre projet. Nous aurons l'occasion d'y revenir.
> **(4)** C'est dans ce fichier que l'on définit le **routing** de nos applications. Il contient essentiellement les URLs utilisables dans notre projet. Nous aurons également l'occasion d'y revenir.

Maintenant que notre projet est initialisé, nous allons l'exécuter. Pour cela, nous allons démarrer le serveur de développement intégré nativement à Django : ouvrez un nouveau terminal (ou un nouvel onglet) et saisissez les commandes suivantes :

```
cd ~/workspace-django/monprojet
python manage.py runserver
```

Le serveur démarre et nous pouvons aller voir notre projet en consultant cette url : <a href="http://localhost:8000" target="_blank">http://localhost:8000</a>. Par défaut, le serveur de Django utilise le port 8000. Si par hasard, ce port était déjà utilisé, vous pouvez démarrer le serveur en passant un port différent :

```
python manage.py runserver 8090
```

> **Nota bene :** Le serveur HTTP utilisé par défaut est un **serveur de développement**, comparable au serveur Webrick de Rails. **Vous ne devez en aucun cas utiliser ce serveur en production !!!**.
> Pour les plus pressés d'entre vous qui souhaitent en savoir plus sur le déploiement d'un projet Django en production, je vous invite à visiter la page de la documentation officielle qui traite de ce sujet : <a href="http://djangobook.com/en/2.0/chapter12/" target="_blank">Deploying Django</a>.

Vous avez pu consulter la page par défaut de votre projet ; Django vous y souhaite la bienvenue et vous confirme par la même occasion que votre projet a été correctement initialisé. Bon le design de la page n'a vraiment pas de quoi faire rêver, mais l'on s'en contentera ... Par ailleurs, nous y apprenons que nous sommes en mode DEBUG, que l'on peut définir les paramètres de connexion à une base de données dans le fichier **settings.py** ou bien encore que, si nous le souhaitons, nous avons la possibilité de créer une application à l'aide de la commande **python manage.py startapp [myapp]**. Comme nous ne sommes pas contrariants, nous allons nous exécuter et créer notamment une application destinée à contenir notre très attendu "Hello World" ! Revenons donc à notre terminal et créons cette application que nous appellerons "hello", avec toute l'originalité qui sied aux développeurs dans de tels cas ...

```
python manage.py startapp hello
```

Cette commande a ajouté à la racine de notre projet un répertoire nommé **hello**, qui correspond à une "application" dans le jargon de Django.

C'est quoi une application au juste ? Quelle est la différence entre "projet" et "application" ?

Bon, je vous avoue que je redoutais un peu cette question, car je manque moi-même du recul nécessaire pour décrire avec exactitude les contours d'une application selon la terminologie employée par Django. Mais je vais m'efforcer tout de même d'y répondre de manière relativement claire en me basant sur un exemple concret et je vous prie de m'excuser par avance si ma réponse reste approximative et/ou trop schématique.

Admettons que vous souhaitiez développer un site Internet consacré à votre sport préféré, le curling ou le crachat de pépins acrobatique. Votre site se déclinera sans doute en plusieurs grandes fonctionnalités : vous souhaiterez sans doute développer un module principal dans lequel vous afficherez les actualités liées à votre sport favori, un module de vente d'articles et vêtements nécessaires à la pratique de votre sport préféré, une gallerie de photographies ou de vidéos, un forum dans lequel pourront s'exprimer tous ceux qui souhaitent partager leur passion immodérée du curling, et j'en passe. Dans cet exemple, je serais tenté de dire que le site internet constituerait le projet au sens de Django, tandis que le forum, la gallerie photos, le module de news, etc. en constitueraient des applications.

Mais une des notions qui m'apparaît fondamentale pour définir une application, c'est la possibilité de réutiliser une application dans un autre projet (exemple avec le forum). Noter que chaque application peut contenir ses propres classes du Modèle (au sens MVC du terme). Quant au projet, je serais tenté de le définir comme un ensemble d'applications partageant des paramètrages communs (base de données, définitions de constantes, layout commun, nom de domaine ...). Dans notre exemple, compte tenu de la modestie et de la pauvreté fonctionnelle du projet que nous développons, celui-ci ne comportera qu'une seule application.

Quoi qu'il en soit, observons la structure de notre projet après que nous avons créé notre première application :

```python
+ hello
  __init.py__
  models.py
  test.py
  views.py

__init.py__
manage.py
........
```


Maintenant que nous disposons d'une structure enrichie, nous avons l'occasion d'aborder les concepts de **packages** et **modules** du langage Python. Il n'est pas question ici d'entrer dans le détail, mais simplement de bien s'entendre sur la terminologie du langage Python, car cela nous sera utile lorsque Django nous signalera en mode DEBUG des exceptions survenues dans tel ou tel module. Pour ceux qui souhaitent approfondir la question de l'organisation du code des applications Python, je les invite à consulter la documentation officielle de Python : <a href="http://docs.python.org/tutorial/modules.html">Modules in Python</a>.

> **Packages et modules Python :**
> Les packages et modules Python permettent un découpage et une structuration du code constituant un projet Python.
>
> En règle générale, les packages Python correspondent à une organisation des fichiers selon une arborescence sur le disque dur, et l'on reconnaît les packages Python à la présence du fichier __init.py__ à la racine des répertoires. Ainsi, dans notre exemple, notre projet est lui-même un package nommé **monprojet**, il contient un sous-paquetage nommé **monprojet.hello**. Les packages permettent notamment de regrouper des modules.
>
> Les modules Python correspondent eux à des fichiers d'extension *.py qui peuvent contenir plusieurs définitions de classes. Par exemple, dans un projet Django, nous enregistrerons les classes de notre modèle dans le fichier models.py des différentes applications. Ainsi, **models** est un module qui peut contenir par exemple le code des classe Client, Product, Order, ou bien encore celui des classes Blog, Post ...
>
> **Packages et modules vs projets et applications :**
>
> Pour résumer, lorsque l'on parle de **packages** et de **modules**, on envisage le code du point de vue de Python. Tandis que les termes **applications** et **projet** décrivent une organisation du code du point de vue de Django. Bien entendu, si l'on superpose ces différents concepts de Python et Django en alignant leurs contours, il est fort probable que rien ne dépasse ... Ce sont simplement deux manières complémentaires d'envisager l'organisation du code.

Nous avons donc créé le squelette de notre application, mais dans un projet Django, toute application doit être déclarée dans le fichier **settings.py** pour être rendue disponible. Nous allons donc modifier ce fichier et ajouter une simple ligne (en toute fin de code) :

```python

# monprojet/settings.py
...
INSTALLED_APPS = (
    'django.contrib.auth',
    'django.contrib.contenttypes',
    'django.contrib.sessions',
    'django.contrib.sites',
    'django.contrib.messages',
    # Uncomment the next line to enable the admin:
    # 'django.contrib.admin',
    # Ici, nous ajoutons notre application :
    'hello',
)
```


Profitez de cette modification pour consulter le contenu de ce fichier. C'est notamment dans ce fichier que l'on définit le mode debug (**DEBUG = True**), les paramètres de connexion à la base de données, l'emplacement où nous enregistrerons les templates, le répertoire "**media**" destiné à accueillir les fichiers statiques (feuilles de style, images, fichiers javascript ...) et bien d'autre choses. Pour connaître les principaux paramètres enregistrés dans ce fichier, je vous encourage à consulter la page de la documentation de Django traitant de la configuration d'un projet : <a href="http://docs.djangoproject.com/en/1.2/topics/settings/" target="_blank">Django Settings</a>.

A présent, nous pouvons nous atteler à la rédaction du code qui va nous permettre d'afficher le tant attendu "Hello World" dans notre navigateur préféré. Cela va consister à :

1.  Ajouter une URL dans le fichier *monprojet/urls.py*
2.  Ajouter une fonction dans le fichier *monprojet/hello/views.py*
3.  Ecrire la template

>   Noter le nom du fichier dans lequel nous rédigerons le code de notre fonction : **views.py**. En fait, Django est un Framework Web mettant en oeuvre le principe **MVC**, tel que vous avez l'habitude de le rencontrer dans d'autres frameworks. Pour être plus précis, dans le cas de Django, les créateurs du framework préfèrent parler de MTV : **Model-Template-View**. Il s'agit d'une nuance dans l'interprétation du MVC. En fait, sous Django, les fonctions qui s'apparenteraient dans d'autres frameworks à des actions ou des contrôleurs sont enregistrées dans le fichier views.py. Cela peut être déroutant pour un développeur familier avec les principes MVC. Sachez simplement que c'est avant tout affaire de terminologie. Pour l'heure, acceptez le fait que le code de ce que vous considérez comme vos actions ou contrôleurs se trouve dans le fichier views.py et que c'est donc dans ce fichier que l'on trouve la logique correspondant au "C" de MVC ... Quant aux vues (dans le sens MVC du terme), elles sont représentées par les templates. Mais fondamentalement, cela ne change en rien les habitudes acquises sur d'autres frameworks MVC, à partir du moment où vous avez digéré cette subtilité sémantique, quelque peu déroutante, il faut l'admettre, lorsque l'on débute sous Django.

1. Créer une URL (fichier urls.py)

Pour ajouter l'URL pointant sur notre méthode, nous allons ajouter une ligne à la fin du fichier **urls.py** :

```python

urlpatterns = patterns('',
    # Example:
    # (r'^monprojet/', include('monprojet.foo.urls')),

    # Uncomment the next line to enable the admin:
    # (r'^admin/', include(admin.site.urls)),
    (r'^hello/$', 'hello.views.hello'),
)
```


Nous venons simplement de préciser que c'est la fonction "hello" du module "views" de l'application "hello" qui doit être appelée lorsque l'URL invoquée correspond exactement au pattern "hello/". Pour créer des URLS sous Django, vous noterez qu'il faut connaître un peu les expressions régulières. Ca, je me suis bien gardé de le préciser en introduction de peur de faire fuir le lecteur. J'avoue que le procédé est assez malhonnête, j'ai quelques scrupules, mais sincèrement, combien d'entre vous seraient parvenus jusqu'ici si j'avais mentionné les expressions régulières dès le début de cet article ? Et puis, sachez que moi-même je ne suis pas très rompu à la syntaxe des REGEXP, alors voici quelques exemples qui vont vous permettre de gérer 90 % des cas que vous serez amenés à rencontrer dans le cadre de l'ajout d'URLs sous Django :

> __Quelques expressions régulières utiles__ :

> - **^videos/** : toute URL commençant par "videos/"
> - **extract/$** : toute URL se terminant par "extract/"
> - **^gallery/$** : toute URL correspondant exactement à l'expression "gallery/"
> - **^photo/show/(?P<id>\\d+)/$** : toute URL commençant par "photo/show/" suivi d'un nombre (correspondant au paramètre "id")
> - **^photo/show/(?P<slug>[a-z0-9\\-]+)/$** : la même chose mais avec un paramètre "slug" (ne contenant donc que des chiffres, des tirets et des lettres minuscules).

A présent, si nous nous rendons sur la page <a href="http://localhost:8000/hello/">http://localhost:8000/hello/</a>, nous recevons une exception de type "ViewDoesNotExist" qui indique que nous sommes sur la bonne voie. En effet, cette erreur implique que notre route a bien été prise en compte et qu'il nous faut donc rédiger la fonction "hello.views.hello".

2. Rédiger la fonction **hello** (fichier hello/views.py)

Voici le code complet du fichier views.py contenant notre fonction "hello" :

```python

# monprojet/hello/views.py
from django.shortcuts import render_to_response

def hello(request):
  return render_to_response('hello.html')
```

Si vous découvrez le langage Python, il n'y a rien de très compliqué. Noter qu'en Python, les délimitations des blocs de code (comme la définition du corps d'une méthode par exemple) sont marquées au moyen de l'indentation (là où les langages avec une syntaxe héritée du C utilisent les accolades). Nous utilisons également une méthode raccourcie de Django très utile, **render_to_response**, qui permet de retourner une réponse HTTP en lui passant en paramètre le nom de la template à afficher. Cette méthode appartenant au package django.shortcuts, il est nécessaire d'importer ce paquetage au début du code source.

Si nous rechargeons la page <a href="http://localhost:8000/hello/">http://localhost:8000/hello/</a>, nous recevons cette fois une exception de type "TemplateDoesNotExist". Notre fonction a bien été appelée mais il nous reste à créer la template.

3. Rédiger la template

Avec Django, il est nécessaire de mettre à jour le fichier settings.py pour indiquer au framework dans quel répertoire du disque dur il doit rechercher les fichiers de templating. Nous allons donc créer à la racine de notre projet un répertoire "templates" destiné à recevoir nos templates et modifier le fichier de configuration pour y mentionner ce répertoire (rechercher dans le fichier settings.py la ligne contenant **TEMPLATE_DIRS**) :

```
mkdir templates
```

```python

# monprojet/settings.py

....
TEMPLATE_DIRS = (
    # Put strings here, like &amp;quot;/home/html/django_templates&amp;quot; or &amp;quot;C:/www/django/templates&amp;quot;.
    # Always use forward slashes, even on Windows.
    # Don't forget to use absolute paths, not relative paths.
    '/home/roldo/workspace-django/monprojet/templates/',
)
...
```


Et enfin, créer la template et y ajouter le contenu suivant :

```
<!-- monprojet/templates/hello.html -->
<h1>Hello World ! </h1>
```

Voilà, si vous rechargez la page dans votre navigateur, vous devriez voir apparaître la célèbre phrase connue de tous les développeurs de la planète. Pour finir, je vous propose quelques aménagements afin de permettre de passer un paramètre prénom à notre fonction, et afficher ensuite la valeur de cette variable dans la template.

Commençons par modifier le pattern de la route ...

```python

# monprojet/urls.py
...
(r'^hello/(?P<firstname>[a-zA-Z]+)$', 'hello.views.hello'),
...
```


... puis la fonction "hello" ...

```python

# monprojet/hello/views.py
...
def hello(request, firstname):
  return render_to_response('hello.html', { 'firstname' : firstname })
```


... et enfin, la template :

```html

<!-- monprojet/templates/hello.html -->
<h1>Hello {{ firstname }} ! </h1>
```


Pour visualiser le résultat, rendez-vous à cette URL : <a href="http://localhost:8000/hello/Georges" target="_blank">http://localhost:8000/hello/Georges</a>

### Conclusion (plus que précaire ...)

Que venons-nous de faire ? Eh bien, nous venons tout simplement de développer un projet informatique, basé sur le Framework Django et reposant sur le paradigme MVC à trois couches. Nous sommes donc à présent en mesure d'honorer tous les besoins d'un client qui souhaiterait se doter d'une application basée sur le Framework Django, bâtie sur le paradigme MVC à trois couches, et destinée à ... afficher "Hello World".

Bon, je vous le concède, les projets informatiques avec un cahier des charges aussi faméliques ne sont pas légion ... Il nous reste donc de nombreux aspects à aborder pour prétendre développer des applications un tant soit peu évoluées avec Django. Qu'à cela ne tienne, nous aurons sans doute l'occasion d'y revenir à l'occasion de prochains tutoriels ...
