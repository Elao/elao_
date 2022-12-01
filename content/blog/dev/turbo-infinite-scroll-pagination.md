---
type:               "post"
title:              "Une pagination avec scroll infini grâce à Hotwired Turbo et Symfony UX"
date:               "2022-12-01"
lastModified:       ~

description:        "Création d'une pagination avec scroll infini en utilisant Hotwired Turbo et Symfony UX."

thumbnail:          "content/images/blog/thumbnails/turbo-infinite-scroll-pagination.jpg"
banner:             "content/images/blog/headers/turbo-infinite-scroll-pagination.jpg"
tags:               ["Pagination", "Infinite Scroll", "Symfony", "Hotwired", "Turbo"]

authors:            ["mcolin"]
---

La pagination avec scroll infin est le fait de chargé automatiquement les pages suivante d'une pagination au fur et à mesure du scroll. C'est un artifice qui fait en général son effet et qui peut s'avérer une bonne pratique UX dans certains cas.

Nous allons voir dans cet article comment réaliser cet effet grâce à [Hotwired Turbo](https://turbo.hotwired.dev/) sans coder la moindre ligne de Javascript.

Je vais pour cela partir d'une base Symfony qui dispose déjà du Turbo installé et configuré grâce au bundle [Symfony UX Turbo](https://symfony.com/bundles/ux-turbo/current/index.html), mais les principes décrits ici sont utilisable dans n'importe quel autre environnement utilisant Turbo.

Je vais commencer par un certains nombres de rappel sur le fontionnement de Turbo, si vous êtes déjà famillié avec cela je vous invite à passer directement à la [partie réalisation](#Réalisation).

## Hotwired Turbo

Nous allons commencer par quelques petits rappels sur le fonctionnement de Turbo.

### Les bases

Le principe de cette librairie est de prendre la main sur les liens et les formulaires de votre page html afin de remplacer les requêtes que ferait votre navigateur par des rêquêtes asyncrhones (`fetch`) réalisé en javascript. La librairie va ensuite mettre à jour le DOM de votre page avec le HTML retourner par le serveur, l'idée étant ainsi de faire de éconnomie de requête en ne rechargeant pas toute la page comme avec une requête navigateur classique.

Turbo propose également deux fonctionnalités qui font de paire avec ce principe :

- Les `<turbo-frame>` qui permettent de décomposer votre page en portions indépendantes qui pourront être rechargée ou modifiée indépendamment de la page.
- Les `<turbo-stream>` qui permettent d'envoyer au navigateur les portions de page à modifier.

Ce sont ces deux fonctionnalités que nous allons utiliser pour réaliser notre pagination avec scroll infini.

### Turbo-Frame et Turbo-Stream

La maitrise de ces deux concepts est essenciel pour réaliser notre pagination avec scroll infini.

Dans le cas d'utilisations classique, une `<turbo-frame>` dispose d'un ID et d'un contenu et on va mettre à jour ce contenu de cette avec une `<turbo-stream>`.

Pour savoir si la requête provient de Turbo, celui ci ajoute un header qu'il convient de tester dans le controlleur. Il faut alors renvoyer également un header indiquant à Turbo que la réponse contient des `<turbo-stream>` à analyser.

Ce qui donne un controller de ce type :

```
class ExempleController extends AbstractController
{
    public function example(Request $request): Response
    {
        if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            
            return $this->render('example.stream.html.twig', [
                'name' => $request->request->get('name');
            ]);
        }
        
        return $this->render('example.html.twig');
    }
}
```

Au premier affichage, on obtient ceci :

```twig
{# example.html.twig #}
<turbo-frame id="myFrame">
    <form>
        <input type="text" name="name" />
        <button type="submit">
    </form>
</turbo-frame>
```

Lorsque l'on soumet le formulaire avec "Maxime" dans le champ, le controlleur retourne un `<turbo-stream>` permettant de remplacer la `<turbo-frame>` par "Bonjour Maxime.".

```
<turbo-stream action="replace" target="myFrame">
    <template>
        <p>Bonjour {{ name }}.</p>
    </template>
</turbo-frame>
```

## Réalisation

Le fonctionnel que nous souhaitons réaliser est le suivant :

1. Lorsqu'on arrive sur la pagination on liste les n premiers éléments
2. Lorsque l'on arrive en bas de la liste en scrollant, on ajoute les n éléments suivants à la liste
3. Lorsque l'on arrive à nouveau en base de liste, on ajoute les n éléments suivants à la liste
4. Et ainsi de suite jusqu'au dernier élément disponible

Pour la suite je vais prendre comme hypothèque que nous avons une pagination par page fonctionnelle. Mais que vous utilisiez une pagination par page ou par curseur, le principe est le même, il nous faut une url capable d'afficher les n éléments de notre page.

Je prend donc pour hypothèse que vous disposez d'une classe `ItemPaginator` proposant une méthode `paginate(int $page): Pagination` retournant un objet `Pagination` contenant les éléments de la page demandée ainsi que différentes méthodes : `getCurrentPage(): int` permetant de connaitre la page courante, `getNextPage(): ?int` retournant la page suivante et `isLastPage(): bool` indiquant si la page courante est la dernière.

### Etape 1 - Afficher les pages

Dans notre controlleur on fait appel à notre service de pagination pour aller chercher les éléments de la page demandé en foncitone d'un paramètre d'url (1 par défaut) :

```php
class ItemController extends AbstractController
{
    #[Route('/items', name: 'item_list')]
    public function list(Request $request, ItemPaginator $paginator): Response
    {
        $pagination = $paginator->paginate($request->query->getInt('page', 1));
    
        return $this->render('item/list.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
```

Puis dans Twig, comme dans une pagination classique on utilise une boucle pour afficher les élément de la page.

```twig
{# item/list.html.twig #}
{% extends 'base.html.twig' %}

{% block body %}
    <h1>Liste des items</h1>
    <div>
        {% for item in pagination.items %}
            <div>
                <h4>{{ item.title }}</h4>
                <p>{{ item.description }}</p>
            </div>
        {% endfor %}
    </div>
{% endblock %}
```

Dans une pagination clasique nous aurions en plus en bas de la page les liens vers chaque page : `?page=1`, `?page=2`, `?page=3`, etc.

### Etape 2 - Charger la page suivante

Nous souhaitons maintenant charger la page suivante lorsque l'on arrive en bas de la liste.

Nous allons pour cela utilisé une fonctionnalité spaciale des `<turbo-frame>`, le [lazy loading](https://turbo.hotwired.dev/reference/frames#lazy-loaded-frame).

Comme nous l'avons vu au dessus, une `<turbo-frame>` contient en général un contenu de base qui peut être mis à jour par un `<turbo-stream>` retourné par le serveur suite à la soumission d'un formulaire par exemple.

Mais le contenu d'une `<turbo-frame>` peut également être chargé de façon asynchrone via une url au moment celle ci apparait à l'utilisateur. C'est à dire que tant que la `<turbo-frame>` n'est pas visible par l'utilisateur, car par exemple en dehors du viewport, rien ne se passe. Et lorsque l'utilisateur scroll dans la page jusqu'à faire apparaitre `<turbo-frame>`, son contenu est chargé depuis l'url.

```twig
<turbo-frame loading="lazy" src="/path/to/content" target="_top"></turbo-frame>
```

L'attribut `target="_top"` permet d'indiquer à Turbo que le lien contenu dans la `<turbo-frame>` permette de remplacer la page entière et non pas uniquement la frame comme ce que ferait une iframe.

L'idée c'est donc d'inclure la page suivante de cette manière. Une page c'est donc la liste des élément de la page courante plus une `<turbo-frame>` lazy vers la page suivante :

```
{# item/page.html.twig #}
{% for item in pagination.items %}
    <div>
        <h4>{{ item.title }}</h4>
        <p>{{ item.description }}</p>
    </div>
{% endfor %}
{% if not pagination.isLastPage() %}
    <turbo-frame loading="lazy" src="{{ path('item_list', { page: pagination.getNextPage() }) }}" target="_top"></turbo-frame>
{% endif %}
```

On inclu la première page ici :

```twig
{# item/list.html.twig #}
{% extends 'base.html.twig' %}

{% block body %}
    <h1>Liste des items</h1>
    <div>
        {{ include('item/page.html.twig') }}
    </div>
{% endblock %}
```

On modifie également le controlleur pour retourner un `<turbo-frame>` lors que la requête provient d'une frame :

```php
class ItemController extends AbstractController
{
    #[Route('/items', name: 'item_list')]
    public function list(Request $request, ItemPaginator $paginator): Response
    {
        $pagination = $paginator->paginate($request->query->getInt('page', 1));
        
        if ($request->header->has('Turbo-Frame')) {
            return $this->render('item/page.frame.html.twig');
        }
    
        return $this->render('item/list.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
```

On renvoie une `<turbo-frame>` avec l'id de la page demandée permettant de remplacer la frame lazy vide par le contenu de la page (liste des items + frame lazy vers la page suivante).

```
{# item/page.frame.html.twig #}
<turbo-frame id="page_{{ pagination.getCurrentPage() }}">
    {{ include('item/page.html.twig') }}
</turbo-frame>
```
