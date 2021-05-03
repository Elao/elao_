---
type:               "post"
title:              "Twig : Quelques pro-tips issue du Symfony Live 2013"
date:               "2013-04-19"
lastModified:       ~

description:        "Twig : Quelques pro-tips issue du Symfony Live 2013"

thumbnail:          "images/posts/thumbnails/badass_vader.jpg"
banner:             "images/posts/headers/php_forum_team_elao.jpg"
tags:               ["Tips", "Twig", "Symfony 2"]
categories:         ["Tips", "Twig", "Symfony"]

author:    "tjarrand"
---

### Isoler les "include"

Lors d'un `include`, le template appelé accède par défaut au context du template appellant. Or c'est inutile la plus part du temps (le template appelé n'a souvent besoin que de quelques variables). De plus, cela peut provoquer des conflits si des variables portent le même nom dans les différents scopes.

Pour éviter ça, vous pouvez isoler le template enfant en passant le paramètre `only` lors de son appel. Le template appelé n'aura alors accès qu'aux variables que vous lui passez. C'est une bonne pratique en terme de performance et de stabilité :

```twig

{# template.html.twig #}

{% set title = 'Mon titre' %}
{% set username = 'Jane Doe' %}

<h1>{{ title }}</h1>
<p>{{ username }}</p>

{% include 'user.html.twig' with {title: username} only %}</code></pre>

  <pre><code class="language-twig twig">{# user.html.twig #}

<h2>{{ title }}</h2>

{# La variable title contient 'Jane Doe'.  #}
{# La variable username n'existe pas.  #}
```


### Embed : un include plus malléable

Le cas se présente souvent : vous avez un template qui est inclus dans plusieurs autres, mais vous avez besoin de changer légèrement une partie du code HTML pour certains cas.

Plutôt que de passer une variable dans l'include puis modifier le comportement du template en fonction de cette valeur, vous pouvez utiliser le tag `Embed` (et c'est beaucoup mieux !).

```twig

{# template.html.twig #}

<h1>Mon grand titre </h1>

{% embed "user.html.twig" %}
    {% block titre %}
        <h2>Mon titre</h2>
    {% endblock %}

    {% block bottom %}
        {{ parent () }}
        <a href="#" title="Mon autre lien">Mon autre lien</a>
    {% endblock %}
{% endembed %}
```


```twig
{# user.html.twig #}

<div class="user">
    {% block titre %}
        <h1>Mon titre</h1>
    {% endblock %}

    {% block content %}
        <p>Mon contenu</p>
    {% endblock %}

    {% block bottom %}
        <a href="#" title="Mon lien">Mon lien</a>
    {% endblock %}
</div>
```


Dans le template parent, à l'interieur du tag embed, vous pouvez redéfinir certain block du template appelé, exactement comme vous le feriez pour un extends. Notez qu'a l'interieur de ces blocks, vous pouvez donc utiliser des fonction comme `parent()` !

Twig rendra **la totalité du template embed** en lui apportant les modification définie entre les balises embed dans le template parent.

Plus de details dans [la documentation officielle][1].

### One line block

Vous pouvez déclarer un block en une ligne ! Utile notamment pour un bloc vide amené à être redéfini par un autre template lors d'un extend :

```twig
{% block content "" %}
```


### Sources

Conférence au Symfony Live 2013 : "[COMMENT ORGANISER SES TEMPLATES TWIG ?][2]" par Grégoire Pineau – SensioLabs

 [1]: http://twig.sensiolabs.org/doc/tags/embed.html
 [2]: http://lyrixx.github.io/SFLive-Paris2013-Twig/#/comment-organiser-ses-templates-twig
