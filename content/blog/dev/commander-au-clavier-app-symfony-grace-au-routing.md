---
type:           "post"
title:          "Commander au clavier une application Symfony grâce au Routing"
date:           "2018-10-25"
publishdate:    "2018-10-25"
draft:          false

description:    "Comment ajouter à une application Symfony une UI différente, une interface de commande par texte avec autocompletion."
tableOfContent:        true

thumbnail:      "images/posts/2018/commander-au-clavier-app-symfony-grace-au-routing/thumbnail.jpg"
header_img:     "images/posts/2018/commander-au-clavier-app-symfony-grace-au-routing/header.jpg"
tags:           ["Symfony", "Routing", "UX"]
categories:     ["dev", "Symfony"]

author: "rhanna"

---

Lorsqu'une application comporte des centaines de fonctionnalités et des millions de lignes en base de données,
il est souvent fastidieux d'accéder à une information.
Il faut choisir le bon élément dans un menu, chercher dans une liste, cliquer sur modifier, accéder à un formulaire
pour enfin pouvoir modifier une donnée.

Nous allons voir comment on peut ajouter à une application Symfony une UI différente,
une interface de commande par texte avec autocompletion.

## Le contexte

<p class="text-center">
    <img src="/images/posts/2018/commander-au-clavier-app-symfony-grace-au-routing/backoffice.png" alt="Backoffice" />
</p>

Ceci est une capture d'écran d'interface d'administration d'une application classique.
Il y a des listes, des boutons, des menus...
Pour accéder à une ressource ou à une fonctionnalité, plusieurs clics sont nécessaires.

Et si on avait une **UI différente** ?

Dans notre temps dédié aux *side projects*, nous avons eu l'idée de voir ce qu'on pouvait faire pour accéder
plus rapidement et plus efficacement aux ressources d'une application.

S'inspirer des **suggestions de résultats** comme sur Google, Spotlight ou Alfred sous Mac ?

Exemple lorsqu'on tape "Modifier document" sur Google :

<p class="text-center">
    <img src="/images/posts/2018/commander-au-clavier-app-symfony-grace-au-routing/search.png" alt="Recherche avec suggestion de résultats" />
</p>

Cela serait pas mal d'avoir la même chose dans notre application, n'est-ce pas ?

## Exploiter le *routing* de Symfony ?

Symfony dispose des commandes *Console* mais cette interface est dédiée aux développeurs.
L'idée est d'avoir un moteur de recherche dans le navigateur qui suggère des résultats qui irait piocher dans notre
application sans forcément écrire complètement une API côté Backend. Et pourquoi pas exploiter le *Routing* de Symfony ?
Nous allons voir pas à pas comment nous avons exploité le *routing* pour répondre à notre besoin.

### Récupérer toutes les *routes* de l'application

```php
<?php

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouterInterface;

class AllRoutesResolver
{
    /** @var RouterInterface */
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @return Route[]
     */
    public function getAllRoutes(): array
    {
        return $this->router->getRouteCollection()->all();
    }
}
```

Cela donne comme résultat :

<p class="text-center">
    <img src="/images/posts/2018/commander-au-clavier-app-symfony-grace-au-routing/all-routes-dump.png" alt="Dump de *routes* les *routes* de l'application" style="width: 50%" />
</p>

Filtrons maintenant les *routes* en ne gardant que les *routes* avec méthode GET :

```php
<?php
    // ...
    return array_filter(
        $this->router->getRouteCollection()->all(),
        function (Route $route) {
            return \in_array('GET', $route->getMethods(), true);
        }
    );
```

### Humaniser les noms de route

Maintenant qu'on a toutes les *routes* de l'application, il faut pouvoir les proposer de manière lisible à un
utilisateur non-développeur.
L'idée est de transformer le nom de chaque *route* en libellé "humanisé" :

- admin_user_list ➡ User list
- admin_user_create ➡ User create
- admin_generate_invoice_for_order ➡ Generate invoice for Order

Pour obtenir ce résultat, on a simplement supprimé le préfixe `admin_` et les `_`, puis mis la première lettre en
majuscule.

### Générer l'url

Pour générer l'url à partir d'une route, rien de plus trivial :

```php
<?php
    /** @var RouterInterface $router */
    return $router->generate($routeName, $parameters);
```

On créé des vues contenant le libellé et l'url et cela donne quelque chose comme ça :

```
array:140 [▼
  0 => ResultView {#1388 ▼
    +label: "User list"
    +routeName: "admin_user_list"
    +url: "/user/list"
```

### Démo

Pour le widget côté navigateur permettant à l'utilisateur de faire sa recherche et d'avoir des suggestions de résultats,
nous avons choisi une librairie assez légère et facilement configurable, notamment au niveau de la source de données :
[Pixabay/JavaScript-autoComplete](https://github.com/Pixabay/JavaScript-autoComplete).

Et cela donne comme résultat :

<p class="text-center">
    <img src="/images/posts/2018/commander-au-clavier-app-symfony-grace-au-routing/demo1.gif" alt="Démo" />
</p>

## Deviner des paramètres de route

Que faire maintenant si nos *routes* attendent des paramètres ? En terme d'expérience utilisateur, on souhaite que
l'application nous suggère des résultats. Par exemple, si on tape "User update", l'application nous propose l'ensemble
des utilisateurs pouvant être modifiés :

```
➡ User update Korben
➡ User update Leeloo
➡ User update Cornelius
```

Cela signifie que côté frontend, lorsqu'on aura tapé "User update" + un espace (très important l'espace),
une requête XMLHttpRequest (Ajax pour les intimes) sera déclenchée afin de récupérer les *routes* contenant
les noms des utilisateurs.

### Récupérer les *requirements* d'une *route*

Considérons que l'on a cette *route* :

```yaml
# Routing
admin_user_update:
    path: /user/update/{user}
    methods: [GET, POST]
    requirements:
        user: \d+
    defaults: { _controller: AdminBundle:User:update }
```

Notre *requirement* apparaît être un paramètre `user` qui est de type numérique.

Dans notre *controller*, le paramètre `user` va être transformé grâce au `ParamConverter` de Symfony en objet
de la classe `User` :

```php
<?php

class UserController extends Controller
{
    public function updateAction(Request $request, User $user): Response
    {
```

Ou ici avec un *invokable controller* (*Action Domain Response pattern*) :

```php
<?php

class UpdateUserAction
{
    public function __invoke(Request $request, User $user): Response
    {
```

Par le code, récupérer les *requirements* d'une *route* :

```php
<?php

public function getRequirements(Route $route): array
{
    return array_keys($route->getRequirements());
}
```

On sait ainsi par le code que la *route* `admin_user_update` a pour *requirement*, un paramètre `user`.

### Récupérer les metadata du controller d'une route

Le principe est de récupérer les arguments d'un *controller* d'une *route* et de voir de quelle classe
il s'agit.

On a besoin de deux choses :

* 1. Récupérer le *controller* d'une *route* :

```php
<?php
/** @var Symfony\Component\HttpKernel\Controller\ControllerResolverInterface $controllerResolver */
$controllerResolver->getController($request);
```

* 2. Récupérer les metadata des arguments d'un *controller* :

```php
<?php
/** @var Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadataFactoryInterface $argumentMetadataFactory */
$argumentMetadataFactory->createArgumentMetadata($controller);
```

Et le code complet donne cela :

```php
<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadataFactoryInterface;
use Symfony\Component\Routing\Route;

class RouteArgumentsMetadata
{
    /** @var ControllerResolverInterface */
    private $controllerResolver;

    /** @var ArgumentMetadataFactoryInterface */
    private $argumentMetadataFactory;

    public function __construct(
        ControllerResolverInterface $controllerResolver,
        ArgumentMetadataFactoryInterface $argumentMetadataFactory
    ) {
        $this->controllerResolver = $controllerResolver;
        $this->argumentMetadataFactory = $argumentMetadataFactory;
    }

    /** @return ArgumentMetadata[] */
    public function getArgumentsMetadata(Route $route): array
    {
        $request = new Request([], [], ['_controller' => $route->getDefault('_controller')]);
        $controller = $this->controllerResolver->getController($request);

        return $this->argumentMetadataFactory->createArgumentMetadata($controller);
    }
}
```

Résultat :

```
array:2 [▼
  0 => ArgumentMetadata {#2146 ▼
    -name: "request"
    -type: "Symfony\Component\HttpFoundation\Request"
    -isVariadic: false
    -hasDefaultValue: false
    -defaultValue: null
    -isNullable: false
  }
  1 => ArgumentMetadata {#2151 ▼
    -name: "user"
    -type: "App\Domain\Model\User"
    -isVariadic: false
    -hasDefaultValue: false
    -defaultValue: null
    -isNullable: false
  }
]
```

On a donc une variable `user` dont le type est `App\Domain\Model\User`.

Cela devient intéressant !

Voyons voir ce que l'on peut en faire...

### *Resolver* dédié

Nous allons coder un *Resolver* dédié pour un paramètre dès lors qu'il est de type `App\Domain\Model\User`.

Ici on sait que notre classe `User` est une classe d'entité *Doctrine*. Nous allons faire appel à un *repository
Doctrine* pour récupèrer une liste des utilisateurs depuis la base de données. On prend soin de retourner le résultat
en précisant que la valeur du paramètre `user` prend pour valeur l'id de l'utilisateur :

```php
<?php

class UserResolver implements ResolverInterface
{
    /** ... */

    /** ResultView[] */
    public function resolve(ResultView $parentResultView): array
    {
        $enabledUsers = $this->userRepository->getEnabledUser();
        $resultViews = [];

        foreach ($enabledUsers as $user) {
            $resultViews[] = new ResultView(
                $parentResultView->label . ' ' . $user->getFullName(),
                $parentResultView->routeName,
                $this->router->generate($parentResultView->routeName, ['user' => $user->getId()]),
                ['user' => $user->getId()]
            );
        }

        return $resultViews;
    }
}
```

On obtient ce résultat :

```
array:2 [▼
  0 => ResultView { ▼
    +label: "User update Korben"
    +routeName: "admin_user_update"
    +url: "/user/update/42"
    +parameters: array:1 [▼
      "user" => 42
    ]
  }
  1 => ResultView { ▼
    +label: "User update Leeloo"
    +routeName: "admin_user_update"
    +url: "/user/update/1"
    +parameters: array:1 [▼
      "user" => 1
    ]
  }
  2 => ResultView { ▼
    +label: "User update Cornelius"
    +routeName: "admin_user_update"
    +url: "/user/update/1337"
    +parameters: array:1 [▼
      "user" => 1337
    ]
  }
```

On peut donc maintenant proposer à l'utilisateur d'accéder à des *routes* ayant un paramètre.

## Améliorations

### *Resolver Doctrine* ?

Pourrait-on avoir un *resolver* générique dédié à nos classes d'entité *Doctrine* ?

L'idée est de tester si une classe donnée est une entité *Doctrine*, c'est à dire via le `ManagerRegistry` chercher
un éventuel `EntityManager`. Puis avec cet `EntityManager`, utiliser le bon `Repository`
et la méthode générique de tout `Repository` *Doctrine*, `findAll()` :

```php
<?php

use Doctrine\Common\Persistence\ManagerRegistry;

class DoctrineResolver
{
    public function __construct(ManagerRegistry $managerRegistry, RouterInterface $router)
    {
        $this->managerRegistry = $managerRegistry;
        $this->router = $router;
    }

    public function resolve(ResultView $resultView, string $paramName, string $className): array
    {
        $entityManager = $this->managerRegistry->getManagerForClass($className);

        if (null === $entityManager) {
            return [];
        }

        $objects = $entityManager->getRepository($className)->findAll();
        $resultViews = [];

        foreach ($objects as $object) {
            $parameters = $resultView->parameters;
            $parameters[$paramName] = $object->getId();

            $resultViews[] = new ResultView(
                sprintf('%s %s', $resultView->label, $object),
                $resultView->routeName,
                $this->router->generate($parentResultView->routeName, $parameters),
                $parameters
            );
        }

        return $resultViews;
    }
}
```

Il faut aussi déclarer une méthode `__toString` dans nos classes d'entité *Doctrine* :

```php
<?php

class User
{
    public function __toString(): string
    {
        return $this->getDisplayName();
    }
```

Ceci afin que l'objet soit *transformé* en *string* lorsque le libellé de la *route* est créé ici :

```
sprintf('%s %s', $resultView->label, $object),
```

### Traduction des noms de route

On a vu plus haut, comment à partir du nom de la route, la transformer un peu pour l'humaniser.

Cependant les noms de *routes* dans une application sont généralement en anglais.
Comment faire lorsque l'application est disponible en plusieurs langues ? Par exemple pour la *route*
"admin_user_create", en anglais on aurait donc "User Create" et en français "Utilisateur Créer".

Oui, l'action est préfixée par le sujet et du coup la phrase est inversée (Yoda style !).
Si on avait dans notre application "Créer document", "Créer facture", "Créer produit"... lorsqu'on tapperait "Cré.."
on aurait droit à tous les "Créer..." de l'application. Nous avons donc choisi ici de préfixer les libellés par le sujet
de l'action. Quand on tappe "Produ..." on a par exemple "Produit Créer", "Produit Modifier", "Produit Déstocker"...

Prenons les noms des *routes* :

```yaml
# Routing
admin_user_list:
    path: /user/list/{user}

admin_user_create:
    path: /user/create/{user}

admin_user_update:
    path: /user/update/{user}
```

et déposons les dans des fichiers de traductions de Symfony (*translations*) :

```yaml
# humanized_routes.en.yml
admin_user_list: User List
admin_user_create: User Create
admin_user_update: User Update
```

```yaml
# humanized_routes.fr.yml
admin_user_list: Utilisateur Lister
admin_user_create: Utilisateur Créer
admin_user_update: Utilisateur Modifier
```

Comment utiliser ces fichiers de traduction ?

Simplement, on regarde si on a la traduction pour un nom de *route* donné dans la *locale* de l'utilisateur de
l'application :

```php
<?php

use Symfony\Component\Translation\TranslatorBagInterface;
use Symfony\Component\Translation\TranslatorInterface;

class TranslateRouteName
{
    public function __construct(
        TranslatorInterface $translator,
        TranslatorBagInterface $translatorBag
    ) {
        $this->translator = $translator;
        $this->translatorBag = $translatorBag;
    }

    public function handle(string $routeName, string $locale): string
    {
        $catalogue = $this->translatorBag->getCatalogue($locale);

        return $catalogue->has($routeName, 'humanized_routes')
            ? $this->translator->trans($routeName, [], 'humanized_routes', $locale)
            : $this->humanizeRouteName($routeName);
    }

    protected function humanizeRouteName(string $routeName): string
    {
        return ucfirst(str_replace(['admin_', '_'], ['', ' '], $routeName));
    }
}
```

`TranslatorInterface` et `TranslatorBagInterface` sont implémentés par le même service, donc dans notre déclaration de
service, on a :

```yaml
App\ActionsBot\Resolver\TranslateRouteName:
    arguments:
        - '@translator'
        - '@translator'
```

### Démo

<p class="text-center">
    <img src="/images/posts/2018/commander-au-clavier-app-symfony-grace-au-routing/demo2.gif" alt="Démo avec paramètre" />
</p>

## Bilan

### Résultat

- Nouvelle UX / UI basée sur les *routes* de l'application.
- Rapidité++ efficacité++.
- On pourrait imaginer avoir des commandes personnalisées : afficher le chiffre d'affaire du mois, afficher le nombre
d'utilisateurs connectés, etc.

### Inconvénients

- Il faut savoir quoi chercher.
- Savoir comment chercher. On pourrait résoudre ce problème en indiquant sur chaque page, comment y accéder par une
recherche texte.
- Inversion du langage : "Utilisateur Modifier" au lieu de "Modifier utilisateur". Pour améliorer, on pourrait proposer
une recherche en langage naturel.

## Commander par la voix ?

Depuis quelques mois, nos ordinateurs et enceintes se sont dotés d'assistants vocaux plus ou moins
performants : Microsoft Cortana, Siri chez Apple, Amazon Alexa (Echo) ou Google Home.

Pourquoi ne pas piloter notre application web avec la voix ? Par exemple, en réunion on pourrait demander
"Quel est le Chiffre d'affaire du mois sur le produit T-SHIRT KORBEN DALLAS ?" et avoir une réponse en synthèse vocale.

Une API web est disponible : [Web Speech API](https://developer.mozilla.org/en-US/docs/Web/API/Web_Speech_API)

Celle-ci comporte notamment les composants suivants :

- [Speech Synthesis](https://developer.mozilla.org/en-US/docs/Web/API/SpeechSynthesis) :
la synthèse vocale à partir d'un texte écrit
- [Speech Recognition](https://developer.mozilla.org/en-US/docs/Web/API/SpeechRecognition) :
la reconnaissance automatique de la parole

```js
var recognition = new SpeechRecognition();
recognition.continuous = true;
recognition.lang = 'fr-FR';

recognition.onresult = function (event) {
  for (i = event.resultIndex; i < event.results.length; i++) {
    var result = event.results[i][0];
    console.log(result.transcript + ': ' + result.confidence);
  }
};

recognition.start();
```

Le support de l'API SpeechRecognition est très limité pour l'instant :

<p class="text-center">
    <img src="/images/posts/2018/commander-au-clavier-app-symfony-grace-au-routing/caniuse-speech-recognition.png" alt="Can I Use SpeechRecognition" />
</p>

[Démo ici](https://www.google.com/intl/en/chrome/demos/speech.html) (à tester avec Chrome seulement au jour où cet article a été écrit).

C'est une technologie assez promoteuse. A suivre donc !

---

Photo by Anthony Martino on Unsplash
