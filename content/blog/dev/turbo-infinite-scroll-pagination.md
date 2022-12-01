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

## Hotwired Turbo

Nous allons commencer par quelques petits rappels sur le fonctionnement de Turbo.

Le principe de cette librairie est de prendre la main sur les liens et les formulaires de votre page html afin de remplacer les requêtes que ferait votre navigateur par des rêquêtes asyncrhones (`fetch`) réalisé en javascript. La librairie va ensuite mettre à jour le DOM de votre page avec le HTML retourner par le serveur, l'idée étant ainsi de faire de éconnomie de requête en ne rechargeant pas toute la page comme avec une requête navigateur classique.

Turbo propose également deux fonctionnalités qui font de paire avec ce principe :

- Les `<turbo-frame>` qui permettent de décomposer votre page en portions indépendantes qui pourront être rechargée ou modifiée indépendamment de la page.
- Les `<turbo-stream>` qui permettent d'envoyer au navigateur les portions de page à modifier.

Ce sont ces deux fonctionnalités que nous allons utiliser pour réaliser notre pagination avec scroll infini.

## Scroll infini

Le fonctionnel qu nous souhaitons réaliser est le suivant :

1. Lorsqu'on arrive sur la pagination on liste les n premiers éléments
2. Lorsque l'on arrive en bas de la liste en scrollant, on ajoute les n éléments suivants à la liste
3. Lorsque l'on arrive à nouveau en base de liste, on ajoute les n éléments suivants à la liste
4. Et ainsi de suite jusqu'au dernier élément disponible

##

Pour la suite je vais prendre comme hypothèque que nous avons une pagination par page fonctionnelle. Mais que vous utilisiez une pagination par page ou par curseur, le principe est le même.

Je prend donc pour hypothèse que vous disposez d'une classe `ItemPaginator` proposant une méthode `paginate(int $page): Pagination` retournant un objet `Pagination` contenant les éléments de la page demandée ainsi que différentes méthodes : `getCurrentPage(): int` permetant de connaitre la page courante, `getNextPage(): int` retournant la page suivante et `isLastPage(): bool` indiquant si la page courante est la dernière.

### Etape 1 - Afficher la première page

Ici rien de sorcier, comme dans une pagination classique on utilise une boucle pour afficher les élément de la première page.

```php
class ItemController extends AbstractController
{
    public function list(Request $request, ItemPaginator $paginator): Response
    {
        $pagination = $paginator->paginate($request->query->getInt('page', 1));
    
        return $this->render('item/list.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
```

```twig
{# item/list.html.twig #}
<div>
    {% for item in pagination.items %}
        <div>
            <h4>{{ item.title }}</h4>
            <p>{{ item.description }}</p>
        </div>
    {% endfor %}
</div>
```
