---
type:               "post"
title:              "Django (2nde partie) : le Modèle et l’Admin"
date:               "2010-10-06"
publishdate:        "2010-10-06"
draft:              false

description:        "Django (2nde partie) : le Modèle et l’Admin"

thumbnail:          "images/posts/thumbnails/django.jpg"
tags:               ["Django", "Framework", "MVC", "Python"]
categories:         ["Django", "Python"]

author:    "xroldo"
---

Bonjour à toutes et à tous,

Aujourd'hui, dans notre série consacrée à Django, nous abordons un second volet autrement plus intéressant que le premier (pour ceux qui ont raté l'épisode précédent, c'est <a href="/dev/premiers-pas-avec-le-framework-python-django" target="_blank">ici</a> que ça se passe). En effet, ce billet sera consacré d'une part au Modèle, que nous avions négligé précédemment, d'autre part à l'interface d'administration fournie par Django (*Django Admin Site*). J'aurais pu consacrer un article entier à la couche "Modèle" et aborder les nombreuses possibilités offertes par le Framework dans ce domaine. Mais mon précédent billet avait essentiellement pour but de nous familiariser avec Django, aussi d'un point de vue purement fonctionnel, le résultat finalement obtenu pouvait nous laisser sur notre faim. J'ai donc choisi ici de dépeindre le modèle dans ses grandes lignes, plutôt que d'entrer dans le détail, afin que nous puissions rapidement mettre en pratique nos connaissances, par le biais de l'interface d'Administration de Django. Mais sachez que les points importants sur lesquels nous ferons l'impasse aujourd'hui feront l'objet de futurs articles.

Avant de débuter ce tutoriel, assurez-vous que Python et Django sont installés sur votre ordinateur. Si tel n'est pas le cas, référez-vous à notre <a href="/dev/premiers-pas-avec-le-framework-python-django" target="_blank">introduction au Framework Django</a> pour connaître la marche à suivre.

### Présentation du projet et du modèle

Le projet qui va nous servir de fil rouge tout au long de cet article (et ceux qui suivront) consiste en un site de vente d'articles de sport. Nous allons donc dans un premier temps créer notre projet (je nommerai ce projet "elao", c'est un nom court, concis, idéal donc pour un nom de projet) et une application que je nommerai "shop". Allez, c'est parti !

Nous ouvrons donc une ligne de commande, nous nous plaçons dans le répertoire qui contient nos projets Django ("workspace-django" en ce qui me concerne), et nous lançons les commandes nécessaires pour initialiser le projet et créer l'application "shop" :

```bash
cd ~/workspace-django
django-admin.py startproject elao
cd elao
python manage.py startapp shop
```

Nous allons à présent configurer les accès à notre base de données et activer l'application que nous venons de créer. Pour cela, nous allons éditer le fichier *settings.py* qui se trouve à la racine de notre projet. Comme nous souhaitons nous initier à Django, nous opterons pour le moteur de base de données SQLite qui présente l'avantage d'être inclus dans les dernières distributions de Python et ne nécessite donc pas d'installation supplémentaire. Noter pour la petite histoire que les créateurs du Framework ne semblent pas être de fervents défenseurs de MySQL, auquel ils préfèrent Postgres ...

> Si vous souhaitez utiliser MySQL à la place de SQLite, il vous faudra installer un driver Python permettant d'accéder aux bases de données MySQL, <a href="http://sourceforge.net/projects/mysql-python/" target="_blank">MySQL for Python</a>, par exemple. Sachez toutefois que ce driver nécessite la présence des headers de MySQL et que son installation requiert donc, si vous aviez installé une version binaire de MySQL, quelques compétences en administration que je ne possède hélas pas.

> **Nota bene** : Si vous utilisez une distribution **Linux**, il est probable que votre gestionnaire de paquets vous permette d'installer le driver MySQL sans douleur. Par exemple, sous Ubuntu, recherchez un paquet nommé "python-mysqldb". En revanche, si vous utilisez **MacOS**, je vous invite à faire une recherche sur <a href="http://www.google.fr/search?hl=fr&#038;q=Python+MySQL+MacOS+trouble">Google</a> ; a priori, l'installation du driver sous cet OS ne relève pas de la sinécure, de nombreuses personnes s'y sont déjà cassé les dents. Pour les utilisateurs de **Windows**, il existe des exécutables permettant d'installer un driver MySQL, mais je n'ai pas pu les tester. Je vous renvoie donc à Google, mais a priori, veillez à télécharger un driver compatible avec votre version de Python.

Dans le fichier *settings.py*, nous allons mentionner les chemins absolus de notre base de données, du répertoire hébergeant les templates, celui contenant les fichiers statiques ... Aussi, pour rendre notre projet portable, nous allons dans un premier temps créer une constante ROOT_DIR correspondant à la racine de notre projet, et par la suite, tous les chemins absolus se baseront sur cette constante. Pour cela, ajoutez les deux lignes suivantes au début du fichier de configuration du projet :

```python

# settings.py
import os
ROOT_DIR = os.path.dirname(__file__) + '/'
```


Pour configurer les accès à notre base de données, nous allons modifier la constante *DATABASES* (qui correspond à la structure de données de type "<a href="http://docs.python.org/tutorial/datastructures.html#dictionaries" target="_blank">dictionnaire</a>" dans le langage Python) :

```python

# settings.py
# ...
DATABASES = {
    'default': {
        'ENGINE': 'django.db.backends.sqlite3',
        'NAME': os.path.join(ROOT_DIR, 'elao.db'),
    }
}
```


Enfin, activons l'application "shop" que nous venons de créer, et profitons-en au passage pour activer l'Admin de Django en décommentant la dernière ligne du fichier *settings.py* (*INSTALLED_APPS* correspond à une structure Python de type <a href="http://docs.python.org/tutorial/datastructures.html#tuples-and-sequences" target="_blank">tuple</a>) :

```python
# settings.py
# ...
INSTALLED_APPS = (
    'django.contrib.auth',
    'django.contrib.contenttypes',
    'django.contrib.sessions',
    'django.contrib.sites',
    'django.contrib.messages',
    # Uncomment the next line to enable the admin:
    'django.contrib.admin',
    'shop',
)
```


Nous allons à présent entamer la définition de notre modèle. Pour l'heure, nous nous contenterons de rédiger deux classes du Modèle, mais sachez que ce modèle s'enrichira à mesure que nous monterons en compétences. Nous créons donc dans le fichier *shop/models.py* une classe "Item" correspondant aux articles que nous mettrons en vente sur notre site et une classe "Sport" qui nous permettra de regrouper les articles par sport (un item appartient à un sport, un sport comporte plusieurs articles, nous sommes donc dans le cadre d'une relation one-to-many).

```python
# shop/models.py

from django.db import models
import datetime

class Sport(models.Model):
  name = models.CharField(max_length=100, null=False, blank=False)
  slug = models.SlugField(null=False, blank=False, unique=True)

  def __unicode__(self):
    return self.name

  class Meta:
    db_table = 'elao_sport'
    ordering = [ 'name' ]


class Item(models.Model):
  name = models.CharField(max_length=255, null=False, blank=False, unique=True)
  slug = models.SlugField(null=False, blank=False, unique=True)
  description = models.TextField(max_length=500, null=True, blank=True)
  picture = models.ImageField(upload_to = 'pictures/items/', null=True)
  created_at = models.DateField(null=False, default= datetime.datetime.now)
  stock = models.IntegerField(blank=False, null=False)
  price = models.FloatField(blank=False, null=False)
  public = models.BooleanField(null=False, blank=False, default=False)
  sport = models.ForeignKey(Sport)

  def __unicode__(self):
    return self.name

  class Meta:
    db_table = 'elao_item'
    ordering = [ 'name' ]
```


Avant de commenter ce code, vérifions que notre modèle est valide :

```
python manage.py validate
```

Corrigez si nécessaire le code de votre modèle, jusqu'à obtenir le message suivant après avoir relancé la commande *validate* :

```
0 errors found
```

> Comme nous utilisons un champ de type *ImageField*, il est nécessaire que la librairie PIL (Python Image Library) soit installée sur votre ordinateur. Pour les utilisateurs de Linux, il est probable que votre distribution vous permette de l'installer à partir de votre gestionnaire de paquetages. Si vous utilisez MacOS ou Windows, téléchargez et installez PIL en suivant les instructions disponibles sur cette page consacrée à la <a href="http://www.pythonware.com/products/pil/" target="_blank">Bibliothèque PIL</a>.
> Si vous ne souhaitez pas installer PIL, il vous suffit de changer le type du champ *picture* en **CharField**.

La définition des classes de notre modèle appelle quelques remarques. Tout d'abord, notez que les objets du modèle destinés à être manipulées par l'ORM (<a href="http://en.wikipedia.org/wiki/Object-relational_mapping" target="_blank">*Object-Relational Mapping*</a>) de Django héritent de la classe *django.db.models.Model*. En Python, la syntaxe de l'héritage se présente donc sous la forme * class MaClasse(ClasseHéritée)*

> Pour en savoir plus sur la programmation objet avec Python, n'hésitez pas à consulter la <a href="http://docs.python.org/tutorial/classes.html" target="_blank">documentation officielle</a>. On notera en particulier que Python supporte l'héritage multiple.

Ensuite, chaque propriété de la classe se voit attribuer un type de champ (FloatField, CharField, etc.). Pour connaître tous les types proposés par Django, visitez cette page : <a href="http://docs.djangoproject.com/en/1.2/ref/models/fields/" target="_blank">Model Field Reference</a>. Nous remarquerons au passage que les types proposés par Django sont généralement plus riches que les types élémentaires fournis par votre serveur de base de données. Il est ainsi possible de définir une propriété de type slug, XML, email, url, fichier, fichier image ... Choisir un de ces types permet de garantir non seulement une vérification des données selon les contraintes définies au niveau de la base de données (taille du champ, unicité, *null* non autorisé ...) mais également une validation des objets par l'ORM de Django, qui est fonction du type choisi (par exemple, pour un champ de type *EmailField*, la valeur est-elle conforme au pattern attendu par une adresse email ?). Noter enfin les types *ForeignKey* (utilisé dans notre exemple dans la classe *Item* pour référencer un objet de la classe *Sport*), ou bien *models.ManyToManyField*, que nous utiliserons sans doute à l'occasion d'un prochain tutoriel.

Pour chaque champ, on définit des options (*null* autorisé, valeur par défaut ...). Encore une fois, celles proposées par Django sont plus riches que celles des serveurs de bases de données ; je citerais par exemple l'option *unique_for_date* ou *unique_for_month*. Pour découvrir ces options et toutes celles proposées par Django, n'hésitez pas à consulter la documentation officielle de Django : <a href="http://docs.djangoproject.com/en/1.2/ref/models/fields/#field-options" target="_blank">Field Options</a>.

La méthode *__unicode__(self)* (attention, elle commence et se termine par un caractère "_" double !) nous permet de définir sous quelle forme littérale nous souhaitons afficher l'objet, par exemple lorsque nous passons cet objet à la méthode *print* ou lorsque nous afficherons cet objet dans les vues (templates).

Enfin, pour chaque classe, nous pouvons définir une classe interne *Meta* qui nous permet de définir des propriétés au niveau de la table et non pas de chaque objet. Nous pouvons ainsi préciser le nom de la table en base de données, le champ sur lequel nous souhaitons trier les enregistrements par défaut, les contraintes d'unicité portant sur plusieurs champs, etc. Pour connaître toutes les options de cette classe, voir la documentation de Django : <a href="http://docs.djangoproject.com/en/1.2/ref/models/options/" target="_blank">Model Meta options</a>.

A présent, nous allons générer les tables en base de données (si vous utilisez SQLite, la base de données sera créée, sinon, il vous faudra préalablement créer une base de données portant le nom que vous avez défini dans le fichier *settings.py*) :

```
python manage.py syncdb
```

Par défaut, Django active les applications nécessaires pour gérer les utilisateurs et les permissions, et lorsque vous lancez la commande ci-dessus, il vous est demandé si vous souhaitez créer un *superuser*. Répondez "yes" et saisissez lorsque l'invitation vous le demande le nom du *superuser* ainsi que son email et son mot de passe (nous aurons par la suite besoin de ces identifiants pour accéder à l'interface d'administration de Django).

> Si vous avez répondu "no" par erreur, sachez qu'il est possible par la suite de créer un super-utilisateur au moyen de la commande suivante
> ```python manage.py createsuperuser```

Si vous voulez connaître les instruction SQL exécutées par Django pour créer ces tables, vous pouvez lancer une de ces deux commandes :

```
python manage.py sql shop
python manage.py sqlall shop # où 'shop' correspond à notre application
```

> **Rappel :** Pour connaître toutes les commandes Django disponibles, tapez simplement :<br /><br /> [php] python manage.py [/php]<br /> Page de la documentation officielle de Django : <a href="http://docs.djangoproject.com/en/1.2/ref/django-admin/" target="_blank">django-admin.py & manage.py</a>

Nous allons à présent manipuler la classe *Sport* au moyen d'un shell interactif très pratique, en particulier lorsque l'on débute sous Django et Python. N'hésitez pas à en abuser lorsque vous souhaitez expérimenter Python et/ou Django pour vous familiariser avec ces deux technologies !

```python
python manage.py shell
```

Saisissez dans cette console les instructions suivantes :

```python

>>> from elao.shop.models import Sport
>>> sport = Sport(name="Football", slug="football")
>>> sport.save()
>>> sport.id
1
>>> sport.name
'Football'
>>> sport = Sport(name="Arts martiaux", slug="arts-martiaux")
>>> sport.save()
>>> sport.name
'Arts martiaux'
>>> Sport.objects.all()
[<Sport: Arts martiaux>, <Sport: Football>]
>>> Sport.objects.get(id=1)
<Sport: Football>
>>> Sport.objects.filter(name="Football")
[<Sport: Football>]
```


Rien de très compliqué. Nous venons de créer deux objets de la classe "Sport", que nous avons sauvegardés en base de données. Ensuite, nous avons utilisé un gestionnaire de Modèle, *ModelManager*, en l'occurrence *sport.objects*, pour exécuter des requêtes sur la table *elao_sport*. Nous aurons l'occasion de revenir sur cette notion de *ModelManager* à l'occasion de futurs articles. Pour quitter le shell interactif, il suffit de saisir **CTL + D** au clavier.

Voilà, nous n'avons fait qu'effleurer le sujet du Modèle de Django, nous aurons l'occasion d'y revenir, mais pour l'heure, il est temps de passer à l'Admin de Django, histoire de constater de visu les ajouts que nous avons faits en base de données au moyen de l'ORM !

### Le site d'Administration de Django

Pour accéder à l'interface d'administration de Django, il faut dans un premier temps activer l'admin en mettant à jour le tuple INSTALLED_APPS ; cela consiste à décommenter la ligne *django.contrib.admin* du fichier *settings.py* (si vous avez suivi les étapes depuis le début, nous l'avons déjà fait). Il faut également décommenter la route permettant d'accéder à l'Admin. Pour cela, il faut éditer le fichier *urls.py* et décommenter la ligne correspondante :

```python

# urls.py
# ...
# Uncomment the next line to enable the admin:
(r'^admin/', include(admin.site.urls)),
```

Et également décommenter les lignes suivantes (toujours dans le fichier urls.py).

```python

from django.contrib import admin
admin.autodiscover()
```


A présent, nous allons démarrer le serveur ...

```
python manage.py runserver
```

... et admirer le résultat en nous rendant à l'URL suivante : <a href="http://localhost:8000/admin/" target="_blank">http://localhost:8000/admin/</a>. Nous sommes alors invités à nous loguer :

<div style="text-align:center;">
![django admin Django (2nde partie) : le Modèle et lAdmin](images/posts/2010/django-admin.gif)
</div>

Saisissez les identifiants que vous aviez précisés lorsque nous avions lancé la commande *python manage.py syncdb*. Voici le résultat après login :

<div style="text-align:center;">
![Capture Site administration Django site admin Mozilla Firefox Django (2nde partie) : le Modèle et lAdmin" width="75%](images/posts/2010/Capture-Site-administration-Django-site-admin-Mozilla-Firefox.png)
</div>

A présent, nous allons éditer le fichier contenant la définition de nos deux classes du modèle, *Item* et *Sport*, afin qu'elles soient prises en compte par l'Admin de Django. Pour cela, nous allons éditer le fichier *shop/models.py* et y ajouter les lignes suivantes :

```python

# shop/models.py
from django.contrib import admin
# ...

class SportAdmin(admin.ModelAdmin):
  list_display = [ 'name', 'slug' ]
  search_fields = [ 'name' ]

class ItemAdmin(admin.ModelAdmin):
  list_display = [ 'name', 'slug', 'description', 'picture', 'stock', 'price', 'public', 'sport' ]
  search_fields = [ 'name' ]

admin.site.register(Sport, SportAdmin)
admin.site.register(Item, ItemAdmin)
```


Rendez-vous à présent sur la page <a href="http://localhost:8000/admin/" target="_blank">http://localhost:8000/admin/</a> pour constater que l'Admin de Django a bien enregistré nos deux classes du modèle. Si tout s'est correctement déroulé, vous devriez voir la liste des entités gérées sur la page d'accueil :

<div style="text-align:center;">
![Django Admin Model Classes Django (2nde partie) : le Modèle et lAdmin](images/posts/2010/Django-Admin-Model-Classes.png)
</div>

N'hésitez pas à naviguer dans l'interface d'Administration de Django pour découvrir les nombreuses fonctionnalités offertes, mais avant de créer des articles, nous devons préalablement déterminer le chemin du répertoire "*media*" où seront notamment placées les images liées aux articles. Pour cela, nous allons préciser la valeur de la constante *MEDIA_ROOT* dans le fichier *settings.py*, puis créer une nouvelle route destinée à servir les fichiers statiques contenus dans ce répertoire "*media*".

> Pour savoir comment faire appel à des fichiers statiques (fichiers javascript, feuilles de style, images, ...) sous Django, n'hésitez pas à consulter cette page : <a href="http://docs.djangoproject.com/en/dev/howto/static-files/" target="_blank">How to Serve Static Files</a>. Il y est notamment rappelé que Django n'a pas vocation à gérer les fichiers statiques car il préfère déléguer cette tâche au serveur Web, dont c'est une des responsabilités. Cependant, en mode développement, c'est exceptionnellement Django qui s'acquitte de cette tâche, et la page mentionnée ci-dessus décrit la manière de procéder (et c'est cette méthode que nous allons mettre en oeuvre dans notre exemple).

Nous allons donc modifier le fichier *settings.py* pour préciser le nom du répertoire contenant les fichiers statiques ...

```python

# settings.py
# Absolute path to the directory that holds media.
# Example: "/home/media/media.lawrence.com/"
MEDIA_ROOT = os.path.join(ROOT_DIR, 'media')
```


... puis créer ce répertoire ...

```
mkdir media
chmod -R 777 media
```

... et enfin, créer une route responsable des contenus statiques, en mentionnant dans le fichier *urls.py* la constante *MEDIA_ROOT* précédemment définie :

```python

# urls.py
# ...

if settings.DEBUG:
    urlpatterns += patterns('',
        (r'^site-media/(?P<path>.*)$', 'django.views.static.serve', {'document_root': settings.MEDIA_ROOT }),
    )
```


> Noter que nous avons choisi *site-media* pour URL : évitez d'utiliser l'URL *media*, car elle est déjà réservée par l'Admin de Django.<br />

L'Admin de Django facilite notamment la saisie des champs de type "slug". Pour cela, nous allons ajouter une ligne dans chacune des classes *XXXAdmin* concernées par un champ de type "*slug*" :

```python

# shop/models.py
# ...
class SportAdmin(admin.ModelAdmin):
  # ...
  prepopulated_fields = {"slug": ("name",)}

class ItemAdmin(admin.ModelAdmin):
  # ...
  prepopulated_fields = {"slug": ("name",)}
```


A présent, lorsque vous créez ou éditez un sport ou un article, à mesure que vous remplissez le champ "*name*", vous remarquerez que le champ "*slug*" se met à jour automatiquement. Sympa !

Nous allons à présent mettre en oeuvre une fonctionnalité très intéressante de l'Admin Django : les formulaires *inline*. Cette fonctionnalité permet notamment de faciliter la saisie de plusieurs enregistrements dans le cadre d'une relation *one-to-many*. Ainsi, dans notre exemple, lorsque nous créons ou éditons un sport, nous allons également autoriser l'édition et l'ajout d'articles associés au sport courant. Pour cela, nous modifions comme suit le fichier *shop/models.py* :

```python

# shop/models.py
# ...

class Item(models.Model):
  # ...

class ItemInline(admin.TabularInline):
  model = Item
  extra = 0

class SportAdmin(admin.ModelAdmin):
  # ...
  inlines = [ItemInline]
```


Nous venons donc de créer une nouvelle classe *ItemInline* héritant de *admin.TabularInline*, puis nous avons ajouté un attribut *inlines* dans la classe *SportAdmin*, faisant référence à la classe *ItemInline*. A présent, si vous créez ou éditez un nouveau sport, vous remarquerez que le formulaire principal embarque désormais plusieurs formulaires associés à la classe "*Item*". Bon, là je dois admettre que cela donne un résultat assez chargé compte tenu du nombre d'attributs définis dans la classe *Item*. Cet exemple avait essentiellement pour but de vous faire connaître l'existence des formulaires *inlines* dans l'Admin de Django. Ce type de formulaires imbriqués est sans doute plus adapté dans le cas d'une relation one-to-many dans laquelle le modèle "enfant" est moins riche ... Cela étant, Django propose un système de formulaires orientés objets qui nous permettraient de définir une version "allégée" des formulaires "*ItemForm*" et donc davantage candidate à une imbrication dans un formulaire parent. Nous pourrions alors les utiliser dans notre exemple, mais comme nous n'avons pas encore étudié les formulaires orientés Objet de Django, nous remettons ces améliorations à plus tard ... Pour l'heure, soyez conscients de l'existence de ces formulaires *inlines*, qui peuvent s'avérer très pratiques.

> Si vous souhaitez dès à présent en savoir plus sur l'imbrication de formulaires dans l'Admin de Django, et notamment connaître les différentes options possibles (model, extra ...), je vous invite à consulter le chapitre consacré aux <a href="http://docs.djangoproject.com/en/1.2/ref/contrib/admin/#inlinemodeladmin-objects" target="_blank">objets de la classe InlineModelAdmin</a>.<br /><br /> Sachez que ces objets sont également utilisables dans le cadre d'une relation de type *many-to-many*. Quant aux formulaires orientés objets proposés par Django, plusieurs chapitres de la <a href="http://docs.djangoproject.com/en/1.2/" target="_blank">documentation de Django</a> leur sont consacrés, mais nous aurons l'occasion d'y revenir.

Et pour finir notre étude de l'Admin, nous allons rédiger une méthode qui va nous permettre de modifier le statut d'un article (attribut dénommé "*public*" de la classe *Item*) à partir de la liste des articles. Cela nous donne l'occasion de voir comment afficher des colonnes personnalisées dans la vue "*liste*" et d'ajouter des fonctionnalités supplémentaires dans l'Admin. Nous allons donc créer une nouvelle méthode permettant d'inverser le statut d'un article, puis ajouter une route pointant sur cette méthode, et enfin ajouter une colonne dans la liste des articles, affichant
Nous ajoutons donc la méthode *toggle_public* dans le fichier *shop/views.py* ...

```python
# shop/views.py
from shop.models import Item
from django.http import HttpResponseRedirect
from django.core.urlresolvers import reverse

def toggle_public(request, id):
  item = Item.objects.get(pk=id)
  item.public = not item.public
  item.save()
  return HttpResponseRedirect(reverse("admin:shop_item_changelist"))
```


... puis créons une route pointant sur cette nouvelle méthode ...

```python

# urls.py
# ...
(r'^admin/item_toggle/(?P<id>\\d+)/$', 'shop.views.toggle_public'),
(r'^admin/', include(admin.site.urls)),
# ...
```


... puis une méthode de la classe *Item* permettant d'afficher un lien pointant sur cette méthode ...

```python

# shop/models.py
from django.core.urlresolvers import reverse
# ...
class Item(models.Model):
# ...
 def toggle_public(self):
   return '<a href="%s">Toggle</a>' % reverse('shop.views.toggle_public', args=[self.pk])
 toggle_public.allow_tag = True
# ...
```


... et enfin, on affiche cette nouvelle colonne dans l'Admin :

```python

# models.py
# ...
class ItemAdmin(admin.ModelAdmin):
  list_display = [ 'name', 'slug', 'description', 'picture', 'stock', 'price', 'public', 'toggle_public', 'sport' ]
# ...

```

Voilà qui clôt le chapitre consacré à la découverte de l'Admin de Django. Sachez qu'il existe d'autres fonctionnalités que nous n'avons pas explorées (notamment la pagination) et sachez également que si vos besoins le justifient, vous avez la possibilité de rédéfinir les méthodes et les templates de l'Admin. Pour ceux qui souhaitent en savoir plus sur cette possibilité, il existe plusieurs ressources disponibles sur Internet. Je citerais notamment la documentation officielle de Django qui consacre un paragraphe à ce sujet : <a href="http://docs.djangoproject.com/en/dev/ref/contrib/admin/#overriding-admin-templates" target="_blank">Overriding Admin Templates</a>. Pour ce qui concerne plus généralement l'Admin, si vous souhaitez connaître l'ensemble des fonctionnalités disponibles, voici les deux chapitres de la documentation qui traitent de ce sujet : <a href="http://docs.djangoproject.com/en/1.2/ref/contrib/admin/" target="_blank">Admin Site</a> et <a href="http://docs.djangoproject.com/en/1.2/ref/contrib/admin/actions/" target="_blank">Admin Actions</a>.

### Conclusion (partielle)

Aujourd'hui, nous avons étendu nos connaissances du framework Django, en abordant le modèle dans ses grandes lignes, et en exploitant certaines fonctionnalités proposées par l'Admin de Django. Au cours d'un prochain article, nous développerons une page qui nous permettra de lister les différents articles disponibles. Cette page comportera un moteur de recherche (assez basique), les résultats seront paginés et pourront être triés à partir des en-têtes de colonnes. Ce sera donc l'occasion de consolider certaines notions déja étudiées, et d'aborder de nouveaux concepts par la pratique, parmi lesquels l'organisation et la réutilisation des templates, la pagination, l'interrogation des données en base de données à l'aide des *ModelManagers*, la génération de vignettes, la création de *fixtures* ... *Stay tuned !*
