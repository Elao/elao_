---
type:               "post"
title:              "Django (3ème partie) : les templates, et bien d'autres choses ..."
date:               "2010-10-15"
publishdate:        "2010-10-15"
draft:              false

description:        "Django (3ème partie) : les templates, et bien d'autres choses ..."

thumbnail:          "images/posts/thumbnails/django.jpg"
tags:               ["Django", "Framework", "MVC", "Python"]
categories:         ["Django", "Python"]

author:    "xroldo"
---

Bonjour à toutes et à tous,

Aujourd'hui nous abordons notre troisième volet consacré à <a href="http://www.djangoproject.com/" target="_blank">Django</a> et autant vous le dire tout de suite : nous avons du pain sur la planche !

En effet, nous allons enrichir le projet initié lors de notre <a href="/blog/django-2nde-partie-le-modele-et-ladmin.html" target="_blank">précédent article</a>. Pour mémoire, j'ai choisi de mettre en pratique nos connaissances de Django à travers un cas concret, assez simple pour être didactique mais assez riche pour couvrir un large éventail de fonctionnalités : un site de vente d'articles de sport (ou, soyons honnêtes, plutôt une ébauche de site). Dans l'épisode précédent, nous avons créé deux objets métiers, "**Sport**" et "**Item**", et mis en place l'interface permettant de les gérer, grâce à l'Admin de Django (affichage des listes, création, édition, suppression). Aujourd'hui, nous allons construire une page d'accueil dans laquelle nous afficherons une liste des produits disponibles. Histoire de rendre la partie plus intéressante, nous nous fixons les objectifs suivants : la liste pourra faire l'objet d'un tri sur un critère donné, elle devra être paginée et il sera possible de faire des recherches sur des critères assez simples. Du travail en perspective !

Voici le résultat à atteindre :

<div style="text-align:center;">
![elao shop index vfinale Django (3ème partie) : les templates, et bien dautres choses ...](/images/posts/2010/elao_shop_index_vfinale.png)
</div>


Tout au long de cet article, nous allons travailler principalement sur trois fichiers : la template globale (**base.html**), la template correspondant à la page d'accueil (**index.html**) et le fichier **shop/views.py**. A mesure que nous avancerons en fonctionnalités, nous compléterons le code de chacun de ces fichiers. Toutefois, si à un moment ou un autre, vous êtiez perdu(e)s, voici une archive contenant le projet obtenu lorsque toutes les étapes décrites dans cet article ont été réalisées (codes, feuilles de style, images, fixtures …) : <a href="http://www.elao.org/wp-content/uploads/2010/10/django_shop_v1.zip">Elao shop - code source - part 3</a>. Quoi qu'il en soit, pour tirer pleinement profit de cet article, je vous encourage plutôt à suivre chacune des étapes décrites ci-après, à ne télécharger cette archive que pour récupérer les fixtures, les styles et les images, et à ne consulter le code qu'en cas de difficulté à "reconstituer le puzzle". Bien entendu, le code fourni ne sert qu'à des fins didactiques et ne devrait en aucun cas être utilisé tel quel en production. Ce code est sans nul doute perfectible à bien des égards, et toute critique constructive pour l'améliorer est évidemment la bienvenue.

Vous êtes prêts ? C'est parti ! Nous allons donc nous atteler à la création de notre page d'accueil, assez basique dans sa forme et son contenu dans un premier temps.

### Première étape : création d'un jeu de données

Vouloir afficher des articles sur un site de vente, c'est bien, encore faut-il pour cela que nous disposions d'un jeu de données. Et c'est là qu'entrent en scène les **fixtures**. Nous allons donc voir une méthode permettant de créer un jeu de fixtures, puis alimenter la base de données avec les données ainsi obtenues (pour ceux qui le souhaitent, un exemple de fixtures est disponible dans le répertoire **shop/fixtures** de <a href="/blog/wp-content/uploads/2010/10/django_shop_v1.zip">l'archive</a> proposée au début de cet article. Copier ce fichier dans le répertoire **elao/shop/fixtures** de votre propre projet. Ces fixtures ont été obtenus en appliquant la méthode décrite ci-après).

Il faut savoir que Django gère les fixtures dans plusieurs formats : XML, YAML et JSON. Nous allons opter ici, de manière assez arbitraire, pour le format XML, qui n'est pas nécessairement mon format préféré, mais il fallait bien en choisir un … J'aurais tendance à considérer que le format YML est le plus lisible des trois (cela reste une opinion) mais …

>   Pour générer des fixtures au format YAML, <a href="http://www.pyyaml.org/" target="_blank">PyYAML</a> doit être installé !

La manière la plus simple pour générer un jeu de données est dans un premier temps de créer en base de données un enregistrement pour chaque classe, à l'aide de l'Admin de Django, puis de lancer la sous-commande **dumpdata** qui va générer un fichier exemple de fixtures à partir des données que nous aurons enregistrées. Si vous souhaitez créer vos propres fixtures, je vous laisse donc le temps d'aller créer une instance de la classe "**shop.Sport**" et une autre de la classe "sport.Item" via l'Admin … Ca y est ? C'est fait ? Nous pouvons donc générer un squelette de fixtures qui va nous servir de base pour créer des données supplémentaires :

```bash
python manage.py dumpdata --indent=2 --format=xml shop
```


Voici un exemple de data qui s'affiche à l'écran après avoir lancé cette commande :

```xml

<!--?xml version="1.0" encoding="utf-8"?-->
```


En nous basant sur cet exemple, nous allons pouvoir créer des données supplémentaires que nous enregistrerons dans le fichier **elao/shop/fixtures/initial_data.xml**. Si vous ne souhaitez pas créer vos propres fixtures, je rappelle qu'un fichier XML de fixtures est disponible dans l'archive. Attention, si vous êtes un amateur de foot éclairé, je vous déconseille d'utiliser ce fichier XML, dont le contenu pourrait heurter la sensibilité de certains footballeurs … Les images associées à nos articles sont également disponibles dans l'archive.

A présent, nous allons charger les données en base en lançant la commande **loaddata** :

```bash

python manage.py loaddata shop/fixtures/initial_data.xml
```


La console devrait vous retourner le résultat suivant :

```
Installing xml fixture 'shop/fixtures/initial_data' from absolute path.
Installed 12 object(s) from 1 fixture(s)
```

Voilà, nous disposons désormais d'un jeu de données qui va nous permettre d'afficher tous les articles sur la page d'accueil de notre site. Nous pouvons donc nous atteler à la rédaction de la page d'accueil. Et pour cela, nous allons définir une template de base (le layout global) dont nos vues hériteront.

### Création de la page d'accueil et du layout global

Dans un premier temps, nous allons écrire le squelette du code nécessaire à l'affichage d'une page d'accueil (nous nous soucierons ensuite de l'habillage). Comme souvent avec Django, cela signifie :

1.  Ecrire une route (dans notre cas, l'URL correspondra à la racine du site)
2.  Ecrire une méthode *index* dans le fichier *views.py*
3.  Ecrire la template correspondante

Tout d'abord, la route permettant d'appeler la méthode **index** du module **views** de notre application **shop** lorsque l'URL invoquée correspond à la racine de notre site ...

```python

# elao/urls.py
# …
urlpatterns = patterns('',
(r'^$', 'elao.shop.views.index'),
# ...
```


… ensuite la méthode **index** ...

```python
# elao/shop/views.py
from django.shortcuts import render_to_response

def index(request):
return render_to_response('index.html')
```


… et pour finir, la template **index.html**

```html
<!-- elao/templates/index.html -->
{% block content %}
<h1>Catalogue des articles</h1>
Ici, nous allons afficher la liste des articles disponibles.

{% endblock %}
```

A présent, si vous vous rendez sur la page <a href="http://localhost:8000/" target="_blank">http://localhost:8000/</a>, vous verrez s'afficher une magnifique page, dont la sobriété graphique a de quoi refiler le cafard à tous les designers de la planète … Même le développeur indécrottable que je suis est à peine satisfait du rendu, c'est vous dire …

Nous allons tenter d'améliorer tout cela, mais le but de cet article n'étant pas de révéler notre sensibilité artistique, mais bien d'étudier le framework Django, nous allons voir comment une template de Django peut hériter d'une template "mère", laquelle va servir de layout pour ses "filles". Je vous rassure, nous en profiterons au passage pour améliorer le rendu à l'aide d'une feuille de style … En ce qui me concerne, je ne suis absolument pas doué pour le graphisme, mais même lorsque je développe, j'aime bien améliorer un peu le rendu global avec quelques styles basiques qui rendent tout de suite le travail plus agréable. Nous allons donc créer dans un premier temps cette template "mère", que nous appellerons **base.html** (c'est une convention adoptée par la plupart des développeurs Django, donc, autant nous y conformer !) et que nous placerons à la racine du répertoire **templates** :

```html
<!-- elao/templates/base.html -->
<div class="container" style="margin: 10px auto; width: 1100px; background-color: white; padding: 15px; border: 2px outset lightgrey;">
    <div id="header">
        <div id="header-logo" style="float: left;">
            <a href="/">
                <img style="margin-right: 10px;" title="Django" src="/site-media/pictures/django_logo.gif" alt="Django" align="absmiddle" />
                <span style="font-size: 26px;">La boutique de sport d'ELAO</span>
            </a>
        </div>
        <div id="header-navigation" style="float: right;"><a href="/">Home</a> | <a href="/admin">Admin</a></div>
    </div>
    {% block content %}{% endblock %}
</div>
```


Pas grand chose à signaler ici, il s'agit d'un fichier HTML assez classique. Vous remarquerez que j'ai inclus un lien vers l'Admin afin d'y accéder rapidement, ce qui n'est pas nécessairement une pratique recommandable dans la mesure où nous développons une page destinée au grand public. Mais nous verrons à l'occasion d'un prochain article comment masquer ce lien en fonction du statut de l'utilisateur courant. Noter également comment nous incluons la feuille de style "**style.css**" : le chemin de la feuille de style fait référence à une route **site-media** que nous avions créée dans l'article précédent, et qui est destinée à servir les fichiers statiques. Il vous faudra donc enregistrer vos styles dans un fichier nommé **style.css** placé à la racine de votre répertoire **media**. Pour ceux qui le souhaitent, le fichier CSS que j'ai utilisé est disponible dans l'archive téléchargeable que j'ai mentionnée au début de cet article. Cette feuille de style recourt à quelques images de poids très léger également disponibles dans l'archive. Enfin, notez surtout la présence du bloc **{% block content %}{% endblock %}** : toutes les pages qui hériteront de la page **base.html** verront le contenu de leur bloc **content** injecté dans le bloc **content** de la template "mère".

A présent, pour que notre page **index.html** soit incluse dans le layout global (en l'occurrence, dans le fichier **base.html**), il nous suffit d'ajouter la ligne suivante au début de ce fichier :

```html

<!-- elao/templates/index.html -->
{% extends "base.html" %}
```


Si vous avez récupéré la feuille de style et les images que je vous proposais, la page d'accueil <a href="http://localhost:8000/" target="_blank">http://localhost:8000/</a> devrait désormais ressembler à ceci :


<div style="text-align:center;">
![elao shop index vfinale Django (3ème partie) : les templates, et bien dautres choses ...](/images/posts/2010/elao_shop_index_v1.png)
</div>

Bon, tout cela commence à prendre forme, à présent, nous allons afficher nos articles dans notre template fraichement créée !

### L'affichage des articles

La première chose à faire consiste à modifier la méthode **index** du module **views.py** pour récupérer tous les articles afin de les passer à la vue …

```python

# elao/shop/views.py
# ...
from shop.models import Item

def index(request):
item_list = Item.objects.all()
return render_to_response('index.html', { 'items' : item_list})
```


… puis les afficher dans la vue :

```html

<!-- elao/templates/index.html -->
{% extends "base.html" %}

{% block content %}
    <h1>Catalogue des articles</h1>
    {% for item in items %}
        <div class="article_div">
            <h2>{{ item.name }}</h2>
            <div style="padding: 5px; text-align: center; margin-top: 10px;"><img title="{{ item.description }}" src="/site-media/{{ item.picture }}" alt="{{ item.name }}" width="150px" height="150px" border="1" /></div>
            <div style="text-align: center; height: 15px; margin-top: 17px; font-size: 15px;">{{ item.price }} &amp;euro;
            {{ item.sport.name }}</div>
        </div>
    {% endfor %}
{% endblock %}
```


Dernière chose avant de passer à la pagination, aux tris, et au moteur de recherche : si vous observez les champs définis dans la classe "**Item**", vous remarquerez que nous avions défini un champ **stock** et un champ **public**. Comme nous travaillons sur une page destinée aux internautes (et non pas aux administrateurs du site), il est tout naturel de ne pas afficher les articles dont le stock est nul et ceux qui ont un statut "public" à false. Nous allons donc définir un **ModelManager** supplémentaire pour la classe **Item**, qui tienne compte de ces contraintes métiers. Nous allons donc dans un premier temps rédiger le code de la classe **PublicItemManager** qui hérite de **models.Manager**, puis modifier le code de la classe **Item** pour que celle-ci utilise ce nouveau **PublicItemManager** en plus du **manager** par défaut. Tout cela se passe bien évidemment dans le fichier **shop/models.py**.

```python

# shop/models.py
# ...
class PublicItemManager(models.Manager):
def get_query_set(self):
return super(PublicItemManager, self).get_query_set().filter(public=True).filter(stock__gt=0)

class Item(models.Model):
# …
objects = models.Manager()
public_items = PublicItemManager()
```


Noter que l'on a défini un second manager, mais si on ne souhaite pas qu'il se substitue au manager **objects** par défaut, il est nécessaire de déclarer explicitement ce dernier (l'interface d'Admin, par exemple, utiliserait le **manager public_items** si **objects** n'était pas déclaré et nous n'aurions donc plus accès dans l'Admin aux articles en rupture de stock et non publics, ce qui n'est pas le comportement souhaité !). A présent, nous allons modifier la méthode **index** dans le fichier **views.py** afin que les articles extraits de la base de données soient gérés par notre **Manager** personnalisé :

```python
# shop/views.py
# …

def index(request):
item_list = Item.public_items.all()
return render_to_response('index.html', { 'items' : item_list})
```


A présent, si vous modifiez, via l'Admin, le stock ou le statut d'un article pour le rendre impropre à la publication, vous remarquerez que celui-ci ne s'affiche plus sur notre page d'accueil : mission accomplie ! Nous pouvons donc continuer à améliorer notre page, en y ajoutant la pagination des articles par exemple.

> Pour en savoir plus sur la personnalisation des **ModelManagers**, voir la page de la documentation Django qui traite de ce sujet : <a href="http://www.djangoproject.com/documentation/models/custom_managers/" target="_blank">Giving models a custom manager</a>.Pour savoir comment exécuter des requêtes avec l'ORM de Django, voir la page dédiée : <a href="http://docs.djangoproject.com/en/1.2/topics/db/queries/" target="_blank">Making queries</a>.

### Pagination des résultats

>   La pagination fait l'objet d'un chapitre complet dans la documentation officielle : <a href="http://docs.djangoproject.com/en/1.2/topics/pagination/" target="_blank">Pagination</a>.

Nous allons limiter le nombre d'articles affichés sur la page d'accueil à trois (dans la mesure où nous disposons d'un jeu de dix articles en base de données, le nombre de trois me paraît être un bon compromis, en tenant compte du fait que nous ajouterons des fonctionnalités de filtre par la suite). Dans un premier temps, nous allons modifier la méthode **index** du module **shop.views** :

```python

# shop/views.py
# …
from django.core.paginator import Paginator, InvalidPage, EmptyPage

def index(request):
item_list = Item.public_items.all()
paginator = Paginator(item_list, 3)

# On s'assure que le parametre 'page' est bien de type integer, sinon, on fixe le numero de page courante a 1
try:
page = int(request.GET.get('page', '1'))
except ValueError:
page = 1

# Si la variable 'page' depasse le nombre de pages total, on fixe sa valeur au numero de la derniere page
try:
items = paginator.page(page)
except (EmptyPage, InvalidPage):
items = paginator.page(paginator.num_pages)

return render_to_response('index.html', { 'items' : items })
```


Ensuite, nous devons modifier la template **index.html** car désormais, nous ne bouclons plus sur **items**, mais sur **items.object_list** :

>   Ici, **items** est une instance de la classe <a id="search_div" class="search" lang="html" type="text" name="search_sport" href="?page={{ items.next_page_number }}{{ sort_query_string }}" target="_blank""></a>

```python

-- Sélectionnez un sport --

{% for sport in sports %}

{{ sport.name }}

{% endfor %}

&amp;nbsp;&amp;nbsp;

<input type="submit" value="Search" />&amp;nbsp;&amp;nbsp;
<input type="reset" value="Reset" />

<!-- … -->
```


Le code de notre formulaire appelle plusieurs remarques :

Première remarque : pour le moment nous ne faisons pas encore appel aux formulaires orientés objets proposés par Django. Nous aborderons cet aspect dans un autre article ; pour l'heure nous continuons à construire nos formulaires "**à la main**".

Seconde remarque : vous noterez que les balises de templates (**template tags** dans le jargon de Django) font référence à des variables en session. En effet, en combinant les paramètres de tri et les paramètres de pagination, vous avez pu constater à quel point il était délicat de maintenir une certaine cohérence, et notamment de conserver une trace des paramètres GET pour les réinjecter dans les liens de pagination. Pour notre exemple, j'ai donc choisi de transmettre les paramètres du formulaire de recherche via la méthode POST, et non pas la méthode GET, et utiliser les sessions pour conserver leur trace. Nous y reviendrons.

Troisième remarque : vous avez pu noter que nous bouclons sur une variable **sports** qui n'a pas encore été définie et pourtant la page s'affiche malgré tout. Quel enseignement en tirer ? Eh bien, tout simplement que les templates de Django, lorsqu'elles rencontrent une variable non définie, ne provoquent pas une Exception, mais les ignorent silencieusement.

Dernière remarque : nous protégeons notre formulaire des attaques CSRF à l'aide de la balise **{% csrf_token %}**. Cette protection implique des modifications dans notre code que nous allons voir.

>   Pour en savoir plus sur la protection CSRF avec Django 1.2, consulter cette page : <a href="http://docs.djangoproject.com/en/1.2/ref/contrib/csrf/" target="_blank">Cross Site Request Forgery protection</a>

A présent, si vous soumettez le formulaire de recherche, Django vous gratifiera d'une erreur 403 **Forbidden** ; il a même la gentillesse de vous indiquer la marche à suivre, en trois points :


<div style="text-align:center;">
![elao shop index vfinale Django (3ème partie) : les templates, et bien dautres choses ...](/images/posts/2010/elao_shop_csrf_403.png)
</div>

Concernant les deux derniers points, nous sommes "**dans les clous**". En particulier, la classe **CsrfViewMiddleware** est bien déclarée par défaut dans notre fichier **settings.py**. En revanche, concernant le premier point, nous devons modifier notre méthode **index** pour passer à la méthode **render_to_response** un troisième paramètre de type **RequestContext**. Nous allons donc nous exécuter :

```python
# shop/views.py
from django.template import RequestContext
# …
def index(request):
# …
return render_to_response('index.html', { 'items' : items, 'sort_query_string' : sort_query_string,}, context_instance=RequestContext(request))
```


>   Pour en savoir un peu plus sur les classes **Context** et **RequestContext**, référez-vous à la documentation de Django : <a href="http://docs.djangoproject.com/en/1.2/ref/templates/api/#playing-with-context-objects" target="_blank">Playing with Context objects</a>.

A présent, nous allons alimenter la liste de sélection avec les enregistrements contenus en base de données. Rien de très compliqué, cela consiste à récupérer tous les sports dans la méthode **index** et les transmettre à la vue (template) :

```python
# shop/views.py
from shop.models import Item, Sport
# …
def index(request):
item_list = Item.public_items.all()
sports = Sport.objects.all()
# …
return render_to_response('index.html', { 'items' : items, 'sort_query_string' : sort_query_string, 'sports': sports}, context_instance=RequestContext(request))
```


Notre formulaire de filtres est désormais correctement configuré, il nous reste donc à traiter la soumission de ce formulaire. Comme je l'ai déjà évoqué, pour tracer les variables POST soumises via le formulaire, nous allons utiliser les sessions : lorsque l'on soumet le formulaire, on place toutes les variables du formulaire en session. Par la suite, si l'utilisateur navigue au moyen des liens de tri ou de pagination (donc via la méthode GET), nous nous baserons sur les variables en session pour retrouver les critères de filtre courants ; pour tenir à jour les variables en session, c'est très simple, seule une soumission du formulaire peut modifier ces variables ; en l'absence de variables POST, les variables en session restent inchangées. Quant aux variables de tri et de pagination, nous n'avons plus à nous en soucier : nous considérons qu'à partir du moment où l'utilisateur soumet une nouvelle requête via le formulaire, les variables de tri et de pagination n'ont plus à être maintenues, et elles sont systématiquement repositionnées à leur valeur par défaut.

**En résumé** : on vérifie dans un premier temps si des données ont été postées ; si c'est le ce cas, on injecte les données postées en session ; dans tous les cas, on construit ensuite la requête en se basant sur les données présentes en session. Ce qui nous donne :

```python

# shop/views.py
# …
def index(request):
item_list = Item.public_items.all()
sports = Sport.objects.all()

# Parametres de recherche :
# On recupere les donnees de session dans une variable 'search'
search = request.session.get('search', {})
# Si le formulaire a ete soumis :
if request.method == 'POST' :
# On reinitialise la variable 'search'
search = {}
if request.POST.has_key('search_name') and request.POST.get('search_name'):
search['name'] = request.POST.get('search_name')
if request.POST.has_key('search_sport') and request.POST.get('search_sport'):
search['sport'] = 1
# Et on reinjecte la variable 'search' dans la session
request.session['search'] = search

if search.has_key('name') and search['name']:
item_list = item_list.filter(name__icontains=search['name'])
if search.has_key('sport'):
item_list = item_list.filter(sport=search['sport'])
# ...
```


Vous pouvez désormais naviguer dans la liste des articles à l'aide du formulaire, des liens de pagination et de tri, tout devrait se dérouler normalement. A une exception près : lorsque l'on soumet le formulaire, les valeurs courantes des critères de filtre ne sont pas conservées par le formulaire. Le problème est lié à la transmission des variables de session à la template. En effet, par défaut, Django ne rend pas les variables de session directement disponibles dans les templates. Nous devons donc encore apporter une dernière modification, qui consiste à ajouter le tuple **TEMPLATE_CONTEXT_PROCESSORS** défini ci-dessous dans notre fichier **settings.py** :

```python

# settings.py
# …
TEMPLATE_CONTEXT_PROCESSORS = (
  'django.core.context_processors.auth',
  'django.core.context_processors.debug',
  'django.core.context_processors.i18n',
  'django.core.context_processors.request',
)
# …
```


Il s'agit d'une liste de méthodes (**callables**) permettant d'injecter des variables issues par exemple de la requête, des sessions, ou de l'utilisateur courant, dans l'objet **context_instance** (de la classe **RequestContext**) que nous transmettons aux templates.


> Les **context processors** sont abordés dans le chapitre de la documentation Django consacré à la classe <a href="http://docs.djangoproject.com/en/1.2/ref/templates/api/#subclassing-context-requestcontext" target="_blank">RequestContext</a>.

### Conclusion (absolument pas définitive)

Aujourd'hui, en construisant une page d'accueil somme toute très classique, nous avons été amenés à appliquer de nombreuses fonctionnalités proposées par Django, parmi lesquelles les fixtures, l'héritage des templates, les variables de session, les gestionnaires de modèle personnalisés, la pagination, et j'en passe. Il nous reste malgré tout encore de nombreux concepts à étudier (l'internationalisation, les vues génériques, les tests unitaires et fonctionnels, l'authentification, les formulaires objets, le cache …). Je tâcherai de rédiger des articles dans lesquels j'aborderai ces différents points, mais avant d'en arriver là, je dois moi-même monter en compétences … Quoi qu'il en soit, je ne manquerai pas de vous faire profiter des connaissances que j'aurai acquises, avec toujours la ferme volonté de privilégier la pratique par rapport à la théorie.
