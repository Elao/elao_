---
type:               "post"
title:              "Dates PHP et Timezone UTC, pourquoi est-ce important ?"
date:               "2017-11-21"
publishdate:        "2017-11-21"
draft:              false
slug:               "date-php-timezone-utc-pourquoi-est-ce-important"
description:        "Mise au point sur les problématiques de timezone avec les dates PHP."

thumbnail:          "/images/posts/thumbnails/time.jpg"
header_img:         "/images/posts/headers/time.jpg"
tags:               ["Symfony", "PHP", "Date", "Datetime", "Timezone"]
categories:         ["Dev", "Symfony", "PHP"]

author_username:    "mcolin"
---

<!--more-->

# Timezone UTC, pourquoi est-ce important ?

## La timezone c'est quoi ?

La timezone est une information associée à une date permettant de connaître son fuseau horaire. Cette timezone est décrite par un nom de continent et un nom de ville.

```
Europe/Paris
Europe/London
America/New_York
Australia/Sydney
```

### DST Rules (_Daylight Saving Time_)

La timezone permet également de savoir si on doit appliquer l'heure d'été ou d'hiver. En effet, il peut exister plusieurs timezones correspondant au même fuseau horaire car elles n'appliquent pas l'heure d'été ou d'hiver aux mêmes dates, voir l'une d'elle ne la pratique pas du tout.

## PHP

Si vous avez déjà travaillé avec des dates en PHP, vous avez surement été confronté à des problèmes de timezone. La plupart du temps, vous avez résolu le problème en remplaçant la timezone par defaut par la vôtre.

```ini
date.timezone = Europe/Paris
```

Si cette solution règle votre souci immédiatement, nous allons voir que sur le long terme, ce n'est pas forcement une bonne idée.

## Les problèmes

Si vous persistez vos dates dans une base de données, sachez que la plupart des SGBD ne stockent pas la timezone sur les champs de type `datetime` ou `timestamp`. Vous perdez donc cette information.

Dans le cas où vos utilisateurs peuvent provenir de plusieurs pays, vous allez être confronté à des incohérences de date. Les heures entrées par un utilisateur dans sa timezone n'auront pas la même signification pour un autre utilisateur ou pour votre système.

Ainsi il sera impossible de faire des comparaisons de dates sans erreur.

### Franco-français

> Mon application est en français, il n'y aura pas d'autre timezone, autant rester en Europe/Paris.

Le français est parlé dans plus de 50 pays à travers plusieurs continents (dont une trentaine de pays ayant le français comme langue officielle). Le territoire français compte également plusieurs régions d'Outre-mer dans différentes **timezones**.

J'ajouterais que le web est mondial et à l'heure du microservice, du SAAS et du cloud, vous pourrez être amené à interconnecter votre application avec des services internationnaux.

## La solution

Pour éviter toute sorte de confusion, la norme est de stocker et de ne travailler qu'avec la **timezone UTC** (Temps universel coordonné). Votre serveur et PHP sont configurés en UTC et votre base de données stocke des dates UTC.

Ensuite, il faut permettre à l'utilisateur de saisir des dates dans sa timezone, puis les convertir en UTC une fois envoyées au serveur. De même, à l'affichage, il faut convertir les dates UTC dans la timezone de l'utilisateur.

Il faut donc soit détecter la timezone de votre utilisateur soit la lui demander et la stocker.

### Toujours stocker l'heure

Il est important également de toujours adjoindre une heure à vos dates, même si seulement la date vous intéresse. Car à la différence de `mysql` par exemple qui gère des champs de type `date` ou `datetime`, PHP ne dispose que d'une classe `DateTime` (ou `DateTimeImmutable`). Si vous créez un objet `DateTime` avec seulement une date, PHP utilisera l'heure courante et cela peut être problématique lors du changement de timezone.

Par exemple, imaginons une table avec un champ date  (sans heure donc)

```php
<?php

// Il est 10h31, Saisie de la date dans la timezone utilisateur
$date = new \DateTime('2017-10-17', new \DateTimeZone('Europe/Paris'));
// Conversion en UTC
$date->setTimezone(new \DateTimeZone('UTC'));
// 2017-10-17 10:31 => 2017-10-17 09:31
// enregistrement de 2017-10-17 dans la base de données

// Il est 00h17, Saisie de la date dans la timezone utilisateur
$date = new \DateTime('2017-10-17', new \DateTimeZone('Europe/Paris'));
// Conversion en UTC
$date->setTimezone(new \DateTimeZone('UTC'));
// 2017-10-17 00:17 => 2017-10-16 23:17
// enregistrement de 2017-10-16 dans la base de données
```

Dans l'exemple ci-dessus, si en base de données vous ne stockez que la date, vous perdrez l'information vous permettant d'appliquer correctement votre timezone et vous risquez d'avoir des décalages de jour en fonction de l'heure à laquelle votre code est exécuté. Sans heure, il est impossible de changer correctement de timezone.

## Symfony

### La saisie

Pour la saisie de date dans vos formulaires, si vous utilisez les formulaires de date natifs de Symfony comme le  [DateTimeType](https://symfony.com/doc/current/reference/forms/types/datetime.html), penchez-vous sur les options [`model_timezone`](https://symfony.com/doc/current/reference/forms/types/datetime.html#model-timezone) et [`view_timezone`](https://symfony.com/doc/current/reference/forms/types/datetime.html#view-timezone).

L'option `model_timezone` vous permet de préciser la timezone dans laquelle les dates seront stockées. Normalement pas besoin d'y toucher, sa valeur par defaut correspond à la timezone de PHP.

L'option `view_timezone` vous permet de préciser la timezone dans laquelle l'utilisateur saisit les dates. Il faut donc la renseigner avec la timezone souhaitée, celle de l'utilisateur que vous stockez sur son profil par exemple.

```php
public function buildForm(FormBuilderInterface $builder, array $options)
{
  $builder->add('datetime', DateTimeType::class, [
    'view_timezone' => $options['user']->getTimezone()
  ]);
}
```

### L'affichage

Pour l'affichage, vous pouvez configurer la timezone par defaut de Twig. Ainsi toutes les dates passant par le filtre `|date` seront automatiquement converties dans cette timezone.

```yaml
# config.yml
twig:
    date:
        timezone: Europe/Paris
```

Si la timezone change en fonction de l'utilisateur, vous devez passer la timezone souhaitée au filtre :

```twig
{{ my_date|date('d/m/Y', my_timezone) }}
```

Vous pouvez également écrire un mécanisme modifiant la timezone de Twig dynamiquement (un *event listener* sur la requête par exemple).

```php
$twig = new Twig_Environment($loader);
$twig->getExtension('Twig_Extension_Core')->setTimezone('Europe/Paris');
```

## API

Si vous développez une API qui accepte des dates, la meilleure solution est d'utiliser un format de date comprenant les décalages horaires comme la [`RFC3339`](https://www.ietf.org/rfc/rfc3339.txt) :

```
2017-11-17T12:51:11+00:00
```

Utilisez ce format pour serializer vos dates en sortie afin d'éviter toute ambiguïté pour les consommateurs de votre API et en entrée afin de permettre la saisie dans n'importe quelle timezone.

## Conclusion

Ne pas gérer ou mal gérer les **timezones** sur une application manipulant des dates peut entraîner des bugs assez sérieux, difficiles à identifier et couteux à corriger après coup (imaginez migrer les dates de 500k lignes en prod). Prendre en compte cette problématique dès le début des développements vous évitera donc de vous arracher les cheveux ;)
