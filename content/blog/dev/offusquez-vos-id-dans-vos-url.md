---
type:               "post"
title:              "Offusquez vos id dans vos url"
date:               "2019-11-06"
publishdate:        "2019-11-06"
draft:              false
slug:               "offusquez-vos-id-dans-vos-url"
description:        "Découverte d'alternatives aux ID auto-incrémentés dans les urls et leur mise en place dans le framework Symfony."

thumbnail:          "/images/posts/thumbnails/obfuscation.jpg"
header_img:         "/images/posts/headers/obfuscation.jpg"
tags:               ["Securite","PHP","Symfony","Framework"]
categories:         ["Dev", "Symfony"]

author_username:    "mcolin"
---

L'une des pratiques les plus courantes du web pour accéder à un contenu de base de données est d'inclure l'identifiant (`ID`) de celui-ci dans l'url. Cet identifiant est dans la grande majorité des cas un entier positif auto-incrémenté par la base de données. Cet identifiant se retrouve ainsi exposé dans les urls. Bien qu'elle pose un certain nombre de problèmes, cette pratique est très simple et très répandue.

## Problèmes de sécurité et de confidentialité

Exposer ces identifiants dans les urls pose principalement des problèmes de **sécurité** et de **confidentialité**.

Ces problèmes sont dus à la prédictabilité de l'identifiant. En effet, celui-ci étant auto-incrémenté, si vous avez une url avec un identifiant, il est très facile de déduire les urls d'autres contenus en incrémentant ou décrémentant l'identifiant dans l'url.

Il est alors simple pour un attaquant ou simplement un utilisateur curieux de tenter d'accéder à des contenus qui ne lui sont pas destinés. Couplé à d'autres failles ou défauts de sécurité, cela lui facilitera grandement la tâche. Il est également simple de crawler votre contenu sans connaitre à l'avance toutes les urls, mais simplement en bouclant sur un identifiant incrémenté.

Et enfin, il est également simple de connaitre vos volumes de données. Par exemple, si je m'inscris sur un site et que je vois dans une url que mon compte utilisateur à l'ID 100, je sais qu'il n'y a que 100 utilisateurs inscrits sur ce site, si je passe une commande et qu'elle a l'identifiant 30, je sais qu'il n'y a eu que 30 commandes et je peux ensuite déduire le nombre de commandes moyen par utilisateur. Autant d'informations sensibles, surtout si vous êtes en concurrence.

## Les alternatives aux IDs auto-incrémentés

Plusieurs alternatives aux identifiants auto-incrémentés existent.

L'une des plus connues est l'**[UUID](https://fr.wikipedia.org/wiki/Universal_Unique_Identifier)**. Cet identifiant de 32 caractères hexadécimaux est généré de façon unique par un algorithme. A partir de l'UUID d'un contenu il n'est pas possible de prédire celui des autres. Il est très utilisé lors d'échanges API; néanmoins il souffre d'un inconvéniant majeur pour des urls, sa longueur.

L'url suivante est quand même beaucoup moins lisible et pratique que la même utilisant des IDs entiers :

<figure>
  <pre>https://www.example.com/shop/category/b441a884-5d46-423b-8317-ddb6f7e3f2fb/product/f0283088-5bd3-4acc-bc42-e6d173d33dd8?filter=165779fc-171d-4f3c-8c60-a2351d6468d3</pre>
  <figcaption>Une url avec des UUIDs</figcaption>
</figure>

<figure>
  <pre>https://www.example.com/shop/category/3/product/56?filter=33</pre>
  <figcaption>Une url avec des IDs</figcaption>
</figure>

Une autre solution pourrait être d'utiliser des **identifiants entiers non auto-incrémentés**. Cela nécessite néanmoins la mise en place d'un algorithme permettant de générer de manière unique ces identifiants. C'est une solution qui peut être viable, mais pas forcement simple à mettre en place et la perte d'auto-increment côté base de données peut être handicapant.

## L'offuscation

Afin de conserver mes identifiants auto-incrementés en interne mais de ne pas les exposer j'ai opté pour l'[offuscation](https://fr.wikipedia.org/wiki/Offuscation). Le principe est simple, mes IDs sont encodés avant d'être inséré dans les urls puis décodés à chaque requête de façon à ce que l'utilisateur ne voie jamais les vrais IDs.

En PHP il existe plusieurs bibliothèques permettant d'offusquer un ID numérique, encodant un entier en une chaîne héxadécidémale, une chaîne base64 ou un autre entier par exemple.

* [hashids](https://hashids.org/)
* [zackkitzmiller/tiny](https://github.com/zackkitzmiller/tiny-php)
* [marekweb/opaque-id](https://github.com/marekweb/opaque-id)
* [jenssegers/optimus](https://github.com/jenssegers/optimus)

J'ai utilisé [**optimus**](https://github.com/jenssegers/optimus) qui transforme votre ID en un autre entier. Néanmoins n'importe quelle autre bibliothèque fonctionnera pour la suite de cette article.

{{< highlight php >}}
<?php
$optimus = new Optimus(1580030173, 59260789, 1163945558);
$encoded = $optimus->encode(20); // 1535832388
$original = $optimus->decode(1535832388); // 20
{{< /highlight >}}


<style type="text/css">
figure figcaption {
  text-align: center;
  font-style: italic;
  font-size: 80%;
}

figure pre {
  margin: 0;
}

figure {
  margin: 20px 0;
}
</style>

## Intégration dans Symfony

Tout d'abord créons un service pour offusquer nos IDs.

{{< highlight php >}}
<?php
interface ObfuscatorInterface
{
    public function obfuscate(int $id): int;
    public function deobfuscate(int $id): int;
}

class OptimusObfuscator implements ObfuscatorInterface
{
    private $optimus;

    public function __construct(Optimus $optimus)
    {
        $this->optimus = $optimus;
    }

    public function obfuscate(int $id): int
    {
        return $this->optimus->encode($id);
    }

    public function deobfuscate(int $id): int
    {
        return $this->optimus->decode($id);
    }
}
{{< /highlight >}}

Configurons le service avec les générateurs.

{{< highlight yaml >}}
parameters:
    env(APP_OPTIMUS_PRIME): "1580030173"
    env(APP_OPTIMUS_INVERSE): "59260789"
    env(APP_OPTIMUS_XOR): "1163945558"
    env(APP_OPTIMUS_SIZE): "31"

services:
    _defaults:
        autowire: true
        autoconfigure: true

    Jenssegers\Optimus\Optimus:
        $prime: '%env(APP_OPTIMUS_PRIME)%'
        $inverse: '%env(APP_OPTIMUS_INVERSE)%'
        $xor: '%env(APP_OPTIMUS_XOR)%'
        $size: '%env(APP_OPTIMUS_SIZE)%'
{{< /highlight >}}

> La bibiothèque met à disposition une commande pour générer les paramètres à injecter à *Optimus* : `php vendor/bin/optimus spark`.
> Plus d'informations sur ces paramètres dans la [documentation](https://github.com/jenssegers/optimus/blob/master/README.md).

Ensuite, afin de ne pas avoir à encoder nous-mêmes les IDs, créons un decorator pour le router Symfony.

Voici un exemple simple qui **offusque** tous les paramètres `id` des routes.

{{< highlight php >}}
<?php
class ObfuscatorUrlGenerator implements RouterInterface
{
    private $inner;
    private $obfuscator;

    public function __construct(RouterInterface $inner, ObfuscatorInterface $obfuscator)
    {
        $this->inner = $inner;
        $this->obfuscator = $obfuscator;
    }

    public function generate($name, $parameters = [], $referenceType = self::ABSOLUTE_PATH)
    {
        foreach ($parameters as $key => $value) {
            if ($key === 'id') {
                $parameters[$key] = $this->obfuscator->obfuscate($value);
            }
        }

        return $this->inner->generate($name, $parameters, $referenceType);
    }

    public function match($pathinfo)
    {
        $parameters = $this->inner->match($pathinfo);

        foreach ($parameters as $key => $value) {
            if ($key === 'id') {
                $parameters[$key] = $this->obfuscator->deobfuscate($value);
            }
        }

        return $parameters;
    }

    public function setContext(RequestContext $context)
    {
        return $this->inner->setContext($context);
    }

    public function getContext()
    {
        return $this->inner->getContext();
    }

    public function getRouteCollection()
    {
        return $this->inner->getRouteCollection();
    }
}
{{< /highlight >}}

{{< highlight yaml >}}
services:
    App\Routing\ObfuscatorUrlGenerator:
        decorates: router
        decoration_priority: 1
        arguments:
            $inner: "@App\Routing\ObfuscatorUrlGenerator.inner"
{{< /highlight >}}

Ainsi, notre router offusque automatiquement nos `id` lors de la génération d'url :

{{< highlight twig >}}
{{ path('ma_route', { id: 20 }) }} {# /ma/route/1535832388 #}
{{< /highlight >}}

Et désoffusque nos `id` lors de la correspondance d'url nous permettant d'accéder à notre `id` en clair dans nos controller :

{{< highlight php >}}
<?php
class MaRouteController
{
    public function __invoke(int $id)
    {
        // $id = 20

        return new Response();
    }
}
{{< /highlight >}}


## Rendre notre système configurable

Notre offuscation fonctionne mais uniquement sur les paramètres `id`. Nous aurons peut être besoin d'offusquer d'autres paramètres ou d'exclure certaines routes.

Pour cela ajoutons une méthode `mustBeObfuscated` à notre `Obfuscator` et déportons y la logique utilisée dans le router et dans le resolver :

{{< highlight diff >}}
<?php
class Obfuscator
{
    // ...

+    public function mustBeObfuscated(string $routeName, string $argumentName): bool
+    {
+        return $argumentName === 'id';
+    }
}

class ObfuscatorUrlGenerator implements RouterInterface
{
    // ...

    public function generate($name, $parameters = [], $referenceType = self::ABSOLUTE_PATH)
    {
        foreach ($parameters as $key => $value) {
-            if ($key === 'id') {
+            if ($this->obfuscator->mustBeObfuscated($name, $key))
                $parameters[$key] = $this->obfuscator->obfuscate($value);
            }
        }

        return $this->inner->setContext($name, $parameters, $referenceType);
    }

    public function match($pathinfo)
    {
        $parameters = $this->inner->match($pathinfo);

        foreach ($parameters as $key => $value) {
-            if ($key === 'id') {
+            if ($this->obfuscator->mustBeObfuscated($parameters['_route'], $key)) {
                $parameters[$key] = $this->obfuscator->deobfuscate($value);
            }
        }

        return $parameters;
    }
}
{{< /highlight >}}

Cette méthode peut alors être personnalisée pour savoir si un paramètre doit être offusqué selon son nom et sa route.

Par exemple, en se basant sur un tableau contenant pour chaque route les paramètres à offusquer :

{{< highlight php >}}
<?php
class Obfuscator
{
    // ...

    private $routes = [
       'une_route' => ['id'],
       'une_autre_route' => ['categoryId', 'productId'],
    ];

    public function mustBeObfuscated(string $routeName, string $argumentName): bool
    {
        return isset($this->routes[$routeName])
            && in_array($argumentName, $this->routes[$routeName]);
    }
}
{{< /highlight >}}

Pour aller plus loin, nous pouvons baser ce tableau de routes sur un fichier de configuration, sur des attributs ou des options dans le fichier de routing, sur une convention de nommage ou implémenter toute autre logique dans la méthode `mustBeObfuscated`.
