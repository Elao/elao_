---
type:           "post"
title:          "Le Design Pattern 'Factory Method'"
date:           "2017-04-10"
publishdate:    "2017-04-10"
draft:          false

description:    "Premier article d'une série consacrée aux Design Patterns. Aujourd'hui : le pattern Factory Method"

thumbnail:      "images/posts/thumbnails/scientist.jpg"
header_img:     "images/posts/headers/scientist.jpg"
tags:           ["Design Pattern", "Conception"]
categories:     ["Dev", "Design Pattern"]

author:    "xavierr"

---

Plan de l'article :

* [Repères bibliographiques](#biblio)
* [Ceci n'est pas une pipe](#not-a-pipe)
* [Le Design Pattern 'Factory Method'](#factory-method)

## <a name="biblio"></a> Quelques repères bibliographiques en guise de préambule

Avant d'entrer dans le vif du sujet en abordant notre premier Design Pattern, permettez-moi de citer deux ouvrages incontournables lorsque l'on évoque ce sujet.

<p class="text-center">
    {{< figure class="text-center" src="/images/posts/design-pattern/biblio-dp-elements-of-reusable.jpeg" alt="Design Patterns: Elements of Reusable Object-Oriented Software">}}
    <figcaption style="text-align: center;font-style: italic;">Design Patterns: Elements of Reusable Object-Oriented Software</figcaption>
</p>

En effet, difficile d'entamer une série d'articles consacrés aux Design Patterns sans faire référence à la bible en la matière : _Design Patterns: Elements of Reusable Object-Oriented Software_. Ce livre publié en 1994 par quatre développeurs, Gamma, Helm, Johnson et Vlissides, plus connus sous le nom du _Gang of Four_, recense 23 Design Patterns classés en trois catégories :

* les Design Patterns de création
* les Design Patterns comportementaux (_Behavior_)
* les Design Patterns structurels

Le Design Pattern que nous allons étudier aujourd'hui (_Factory Method_) appartient à la première catégorie.

Bien qu'écrit il y a plus de vingt ans, ce livre fait toujours autorité et c'est à lui qu'il faut se référer lorsque l'on souhaite connaître l'anatomie d'un Pattern ou éviter les quiproquos au sein d'une équipe de développeurs. Il n'est pas forcément d'une lecture agréable et s'apparente plus à un annuaire ou un dictionnaire : le lire peut s'avérer rébarbatif, mais il est indispensable de le garder à portée de main pour y trouver les informations dont on a besoin.

Je ne m'étendrai pas sur la définition exacte d'un Design Pattern, ni sur le formalisme à adopter pour présenter un pattern (le livre le fait très bien), ni sur les principes qui sous-tendent une bonne conception objet (comme l'acronyme SOLID par exemple) car nous aurons l'occasion d'y revenir au moment d'aborder certains patterns.

<div class="aside-note">
On peut déplorer que la plupart des exemples du livre s'appuient sur le langage <strong>Smalltalk</strong> qui est tombé en désuétude (mais notez tout de même que ce langage fut pionnier dans bien des domaines !) ... <strong>Java</strong> pourrait sembler aujourd'hui plus adapté et/ou fédérateur pour un ouvrage consacré à la conception objet, mais lors de la première édition du livre (1994), la première version de Java n'existait pas encore ...
</div>

<p class="text-center">
    {{< figure class="text-center" src="/images/posts/design-pattern/biblio-dp-head-first.jpeg" alt="Head First Design Patterns">}}
    <figcaption style="text-align: center;font-style: italic;">Head First Design Patterns</figcaption>
</p>

Complément idéal à la bible des Design Patterns, _Head First Design Patterns_ (O'Reilly 2004) constitue une excellente introduction aux principaux design patterns. Par rapport au premier ouvrage, il adopte une démarche résolument pratique et recourt à un éventail remarquable de techniques pédagogiques pour aborder les design patterns (traits d'humour, schémas explicatifs, dialogues fictifs en situation autour des différents patterns, démarches didactiques, etc.). Apprendre les design patterns peut s'avérer fastidieux, mais rien de tout ça ici ! Je recommande particulièrement cet ouvrage si vous souhaitez vous initier aux design patterns sans drame ... Par ailleurs, la plupart des exemples s'appuient sur le langage Java, beaucoup plus abordable et actuel que Smalltalk.

Enfin, je ne peux passer sous silence deux autres ouvrages qui me paraissent fondamentaux s'agissant de conception objet, à savoir _Domain-Driven Design: Tackling Complexity in the Heart of Software_ d'Eric Evans (livre pas forcément très digeste mais dont on ne peut pas ignorer l'influence) et _Catalog of Patterns of Enterprise Application Architecture_ de Martin Fowler : qu'est-ce qui définit exactement une entité ? un value object ? un repository ? etc. Nous avons tous une vague idée de ces concepts mais c'est dans cet ouvrage que se trouvent les réponses à ces questions.

## <a name="not-a-pipe"></a> Ceci n'est pas une pipe

Après un préambule assez verbeux j'en conviens, laissez-moi à présent vous gratifier d'une remarque liminaire : cet article est avant tout destiné à présenter le Design Pattern _Factory Method_, alors précisons d'emblée que ce qui suit n'est pas un pattern (et encore moins une pipe, mais ça, c'est un autre débat ...) :

```php
    <?php
    class MyFactory {
        public static function getInstance($type = null) {
            switch($type) {
                case "a":
                    return new A();
                    break;
                case "b":
                    return new B();
                    break;
                /* etc. */
            }
        }
    }
```

Ne me faites pas dire ce que je n'ai pas dit ... Je ne prétends pas du tout que le code ci-dessus relève d'une mauvaise conception. Ni que cette classe n'est pas une _factory_. Simplement, si l'on se réfère à la définition exacte du pattern `Factory Method`, on s'apercevra que l'on n'est pas en présence d'un pattern.

Pour autant, cela ne signifie pas que ce code mérite l'anathème, loin de là. J'évoquais tout à l'heure les principes d'une bonne conception objet, et parmi les bonnes pratiques, il est préconisé d'isoler ce qui varie (_encapsulate what varies_). Or c'est exactement ce que fait cette classe : elle isole en son sein un pan de code qui repose sur un embranchement (_switch_). De ce point de vue, ce code est parfaitement valide.

Noter que cette bonne pratique objet qui consiste à isoler ce qui varie est mentionnée parmi d'autres dans le livre _Head First Design Pattern_. Citons-en quelques-unes :

* favoriser la composition plutôt que l'héritage (citée également par le _Gang of Four_)
* programmer pour des interfaces plutôt que pour des implémentations (_idem_)
* s'efforcer de limiter le couplage entre les objets qui collaborent (_strive for loosely couple designs between objects that interact_)
* les classes devraient être ouvertes à l'extension mais fermées à la modification
* etc.

Mais nous aurons l'occasion d'y revenir ...

## <a name="factory-method"></a> Notre premier DP : la `Factory Method`

### Introduction

Les principes d'une bonne conception objet recommandent de programmer pour des interfaces plutôt que des implémentations (c'est au demeurant un principe qui s'accommode très bien avec celui de l'injection de dépendance). Concrètement, cela consiste par exemple à privilégier des méthodes de classes qui acceptent en arguments des interfaces (ou classes abstraites) plutôt que des classes concrètes. Cela étant, même si l'on programme pour des interfaces, nous devons nécessairement nous résoudre à instancier des objets concrets pour que notre application s'exécute. Et c'est là qu'entrent en scène les design patterns de création, et notamment les deux Design patterns de _Factory_ identifiés par le _Gang of Four_, à savoir la `Factory Method` et l'`Abstract Factory`.

Pour l'heure, intéressons-nous au premier de ces deux patterns de _Factory_.

### Classification

La `Factory Method` est classée dans la catégorie des __Design Patterns de création__.

### Définition

> Define an interface for creating an object, but let subclasses decide which class to instantiate. Factory Method lets a class defer instantiation to subclasses

Cette définition peut paraître assez obscure à première vue, mais croyez-moi, le pattern est d'une affligeante simplicité.

### Schéma

<p class="text-center">
    {{< figure class="text-center" src="/images/posts/design-pattern/creation-factory-method.jpg" alt="Le Design Pattern 'Factory Method'">}}
    <figcaption style="text-align: center;font-style: italic;">Schéma du Design Pattern 'Factory Method'</figcaption>
</p>

### Participants

On distingue sur le schéma ci-dessus les participants suivants :

* Le créateur : classe concrète ou abstraite notamment chargée de _fabriquer_ l'objet attendu
* Le créateur concret : il étend _Creator_ et instancie l'objet attendu
* Le produit : c'est l'interface de l'objet à instancier
* Le produit concret : c'est l'objet à instancier

### Exposé de la problématique

```php
    <?php
    class OrderHandler {
        public function orderProduct(Order $order, int $qty = 1): Product {
            $orderedProduct = new Product();
            $orderedProduct->setOrder($order);
            $orderedProduct->setQty($qty);
            // etc.

            return $orderedProduct;
        }
    }
```

La classe `OrderHandler` contient notamment une méthode `orderProduct` qui retourne un produit commandé après l'avoir instancié. Par rapport à notre schéma ci-dessus, `OrderHandler` correspondrait au `Creator` tandis que `Product` correspondrait à `ConcreteProduct`.

Nous pouvons craindre que cette classe `OrderHandler` et la classe concrète `Product` soient trop étroitement liées. En effet, que se passe-t-il si nous devons gérer d'autres types de produits (des produits matériels, mais aussi des prestations de services par exemple) ? Dans son état actuel, la classe `OrderHandler` nous lie trop fortement à la classe `Product`. Nous avons notamment enfreint plusieurs recommandations :

* notre classe n'est pas ouverte à l'extension,
* nous n'avons pas développé pour des interfaces,
* nous avons créé un couplage très fort (trop fort ?) entre nos deux classes.

Il est possible qu'en l'état actuel de l'application, ce code fasse parfaitement l'affaire. Mais il est préférable de rendre son code ouvert aux évolutions, et c'est particulièrement vrai lorsque l'on développe des services destinés à être consommés par des tiers (librairie _open source_ par exemple) ou bien des classes métiers dont les spécifications sont susceptibles d'évoluer fréquemment.

### Le design pattern `Factory Method` à la rescousse

Nous allons à présent modifier notre classe `OrderHandler` pour nous rapprocher du pattern :

```php
    <?php
    class OrderHandler {
        public function orderProduct(
            Order $order,
            int $qty = 1
        ): OrderableInterface {
            $orderedProduct = $this->createProduct();
            $orderedProduct->setOrder($order);
            $orderedProduct->setQty($qty);
            // etc.

            return $orderedProduct;
        }

        public function createProduct(): Product {
            return new Product();
        }
    }
```

Les modifications apportées consistent à :

* introduire une interface `OrderableInterface` qu'implémente désormais `Product`
* déporter dans une méthode dédiée (`createProduct`) l'instanciation d'un produit concret (`Product`)

### Commentaires

On note tout d'abord, comme le laisse deviner la classe `OrderHandler`, que le pattern `Factory Method` est souvent implémenté par des classes dont la création de produit n'est pas la principale responsabilité ; ici `OrderHandler` est une classe métier qui ne se contente pas d'instancier un produit ; elle est par exemple plus riche fonctionnellement que la classe `MyFactory` que j'avais présentée tout à l'heure.

En résumé, et comme son nom l'indique, le pattern `Factory Method` consiste tout simplement à déporter dans une méthode dédiée l'instanciation de l'objet attendu. Cette méthode se résume finalement à un simple `return new Product();`. Concept d'une extrême simplicité mais qui nous permet d'obtenir une classe ouverte à l'extension, en permettant le cas échéant à ses enfants d'instancier un autre type.

### Design patterns voisins

Il existe un autre Design pattern _Factory_, plus riche que le design pattern `Factory Method` car il implique plus de participants (mais il n'est pas nécessairement plus compliqué pour autant) : l'`Abstact Factory`. Il y a fort à parier qu'il fasse l'objet du prochain article de cette série consacrée aux Design Patterns.

<style>
    .aside-note {
        border-left: 5px solid #ffa600;
        padding: 20px;
        margin: 20px 0;
    }
</style>
