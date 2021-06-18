---
type:               "post"
title:              "Mettez en valeur vos offres d'emploi grâce aux données structurées"
tableOfContent:     2

description:        "Et si vous rendiez vos offres d'emploi plus visibles dans les pages de résultats des moteurs de recherche grâce aux données structurées ? "
date:               "2019-08-26"

thumbnail:          "images/posts/thumbnails/arrows-square.jpg"
banner:             "images/posts/headers/arrows.jpg"
tags:               ["Seo", "microdata", "rich snippets"]
categories:         ["Dev", "Web"]

author:    "aldeboissieu"
---
Certaines informations extraites d'une page web sont **directement affichées dans la page de résultats du moteur de recherche Google**, permettant ainsi au géant du web d'*enrichir* ses résultats de recherche. Ces contenus supplémentaires sont appellés des extraits enrichis (ou rich snippets). Ils peuvent concerner de nombreuses thématiques : produits, recettes de cuisine, spectacles ou encore … des offres d'emploi !

C'est justement pour répondre à ce besoin que nous avons mis en place ce fameux protocole sur les pages d'un de nos clients. Nous vous proposons de partager avec vous ce que nous avons appris 🌟🤓.

## Rich snippets : mais qu'est-ce que c'est ?

Les internautes sont habitués à voir selon la thématique, pour un résultat proposé en plus de la meta description, un prix, une note sous forme d'étoile, une date de publication de l'article, ou encore un temps de cuisson s'il s'agit d'une recette de cuisine.
![Vélo avec prix](images/posts/2019/microdata-offers/produit-prix.png)
![Recette de cuisine](images/posts/2019/microdata-offers/rich-snippet-recipe.png)

C'est en encodant votre HTML que les crawlers de Google (ou d'autres moteurs de recherche) peuvent interpréter votre contenu, et ainsi peut-être l'utiliser pour alimenter ses pages de résultats. Et si vous avez la chance de figurer parmi les premières positions, **vous bénéficierez probablement de plus de clics sur votre page web, et ainsi plus de trafic**.

En plus des contenus enrichis, Google propose de véritables features, comme par exemple ce planning des concerts à venir :
![Evènements à venir](images/posts/2019/microdata-offers/position-zero-concert.png)

### Les protocoles de données structurées

Certaines données structurées sont déjà des standards du web, comme **le fil d'ariane**, ou encore **la structure des articles** (l'auteur, la date de publication, l'image, etc.). Le protocole utilisé par les moteurs de recherche, dont Google est celui décrit par [schema.org](https://schema.org/). Par ailleurs, il n'y a pas que les pages web qui peuvent être balisées, car schema.org propose également des données structurées pour les emails (par exemple pour des récapitulatifs de réservation de vol).

Il existe d'autres protocoles, comme par exemple l'[**Open Graph de Facebook**](https://developers.facebook.com/docs/sharing/webmasters?locale=fr_FR), qui facilite le partage de vos contenus sur ce réseau social en ciblant correctement le titre, la description de l'article, etc.

## Offres d'emploi : est-ce utile d'intégrer des données structurées ?

Si l'internaute saisit des mots-clés relatifs à une recherche d'emploi (ici `pizzaiolo lyon`), **il voit apparaître une feature spéciale lui proposant une liste d'offres localisée**. Au clic sur "17 offres d'emploi" (voir ci-dessous), on est redirigé vers un outil de recherche d'emplois permettant de filtrer selon plusieurs types (date de publication, type d'emploi, lieu, employeur). Voir son ou ses offres d'emploi y paraître semble donc être un bon moyen de bénéficier de plus de visibilité.

![job board Google](images/posts/2019/microdata-offers/offer-rich-snippets.png)

## Comment s'y prendre ?

Le guide suprême auquel se référer est **schema.org**. Il documente pour chaque type recherché l'implémentation des balises pour l'encodage voulu (microdata, RDFa, JSON-LD). Ainsi, la page dédiée à l'offre décrit [toutes les propriétés](https://schema.org/Offer). Cela dit, [Google propose une documentation - en français - très détaillée](https://developers.google.com/search/docs/data-types/job-posting), afin de faciliter l'implémentation des microdonnées par les webmasters.

Voici un exemple de contenu balisé pour une offre d'emploi :

```
<div itemscope itemtype="http://schema.org/JobPosting">
    <h1 itemprop="title">Chef / Cheffe de 🍕</h1>
    <p>69 - LYON 07</p>
    <ul>
        <li> Publiée
            <time datetime="2019-08-26T14:58:44" itemprop="datePosted">aujourd'hui</time>
        </li>
        <li>Valide jusqu'à
            <time datetime="2021-08-26T14:58:44" itemprop="validThrough">26/08/2021</time>
        </li>
    </ul>
    <p itemprop="description">Nous recherchons pour notre agence web un chef ou une cheffe de pizza, afin de subvenir aux besoins de l'équipe lors des séances de ciné-club.</p>
    <span itemprop="jobLocation" itemscope itemtype="http://schema.org/Place">
          <span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
            <span itemprop="addressLocality">Lyon</span> (69)
            <span class="sr-only" itemprop="postalCode">69006</span>
          </span>
    </span>
    <p itemprop="employmentType">CDI</p>
    <p>39H Horaires normaux</p>
    <p itemprop="baseSalary" itemscope itemtype="http://schema.org/MonetaryAmount">
        <span content="EUR" itemprop="currency"></span><span itemprop="value"
                                                             itemtype="http://schema.org/QuantitativeValue">
              <span content="MONTH" itemprop="unitText"></span>
              <span content="1950.0" itemprop="minValue"></span>
              <span content="2350.0" itemprop="maxValue"></span>
              <span></span></span>
        Salaire : Mensuel de 1950,00 Euros à 2350,00 Euros sur 12 mois</p>
    <h2>Savoir-faire :</h2>
    <ul>
        <li itemprop="skills">Techniques de tournage de pâte à crêpe</li>
        <li itemprop="skills">Cuire une pizza au four</li>
        <li itemprop="skills">Aimer le fromage</li>
        <li itemprop="skills">Connaissance des films à associer avec une pizza</li>
    </ul>
    <span itemprop="experienceRequirements">1 à 2 ans</span>
    <ul>
        <li>Qualification : <span itemprop="qualifications">Employé non qualifié</span></li>
        <li>Secteur d'activité : <span itemprop="industry">Bullshit Job</span></li>
    </ul>
    <div itemprop="hiringOrganization" itemscope itemtype="http://schema.org/Organization">Entreprise :
        <p itemprop="name">Pizza Lovers Inc</p>
    </div>
</div>
```

Voici un petit résumé de ce qu'il a paru utile de connaître :

- Les données structurées sont **à placer sur la page qui détaille l'offre**, ou du moins sur la page qui fournit le maximum d'infos. Par exemple, une page de catégorie qui liste des offres en cours n'est pas l'endroit où les placer.
- **Toutes les propriétés ne sont pas obligatoires, mais mieux vaut en fournir un maximum**, sinon Google risque de choisir arbitrairement des informations dans la page ou pire, de vous déclasser et ainsi de perdre en visibilité.
- N'attendez pas de mettre votre page en production pour corriger les erreurs d'implémentations !

### Tester son code :

[L'outil de test des données structurées de Google](https://search.google.com/structured-data/testing-tool/u/0/) vous donnera des informations sur l'implémentation.
Deux intérêts :

- **Repérer les erreurs** (Google classe les erreurs distinctement des avertissements, qui sont les propriétés "bien à avoir" mais qui ne génèreront pas d'erreur), comme ci-dessous :
![Liste des avertissements et erreurs](images/posts/2019/microdata-offers/test-microdata.png)

- **Prévisualiser son offre dans l'outil**, comme ci-dessous :
![Prévisualisation](images/posts/2019/microdata-offers/preview.png)

### Se servir de la Search Console

Pour ceux qui le souhaitent, il est possible d'utiliser un outil dédié aux webmasters, la [Search Console](https://search.google.com/search-console/about?hl=fr) proposée par Google. **Celle-ci permet de remonter diverses informations dont les erreurs rencontrées par les robots d'exploration au moment du crawl.** Il peut s'agir d'un code réponse spécial (404, 500 ...), ou bien d'erreurs rencontrées lors de l'analyse des microdonnées.

En ce qui concerne les offres d'emploi, celles-ci disposent d'un onglet spécifique dans le menu de la Search Console afin d'accéder à un tableau de bord qui remonte les erreurs rencontrées sur les pages, comme par exemple des propriétés manquantes.

Bien entendu, l'objectif est de corriger à tout prix les erreurs avant que les robots ne les relèvent, pour ne pas nuire à la pertinence de vos contenus et ainsi être déclassé 🤖.

### Améliorer l'exploration de vos pages

Si votre site met à disposition des internautes **un volume important d'offres d'emploi** (pour votre propre société ou pour des tiers), vous pouvez informer Google de plusieurs façons :

- **en utilisant l'[API d'indexation](https://developers.google.com/search/apis/indexing-api/v3/quickstart)** (dédiée aux offres d'emploi)
- **en envoyant un sitemap rafraîchi à Google** sur l'url suivante : `http://www.google.com/ping?sitemap=location_of_sitemap`, à chaque ajout ou suppression d'offres.

Tout ceci est expliqué dans la [documentation dédiée à la mise en place des données structurées pour les offres d'emploi](https://developers.google.com/search/docs/data-types/job-posting).

## Bon à savoir

Vous avez peut-être aperçu, au cours de vos recherches, du contenu affiché dans un encart spécial, en haut de la page, généralement en réponse à une question saisie par l'internaute. Ce sont des **"positions zero"**.
Dans ce cas particulier, il n'est pas forcément nécessaire d'implémenter des données structurées dans votre HTML même si de nombreux types existent, seul l'adéquation entre une balise de titre (H1, H2...) et un contenu disposé dans une liste à puce semble suffire.

Voici un exemple pour une question saisie par l'internaute dans un encart spécial, dont le html ne comporte qu'une liste, et non une structure de type ["How To"](https://developers.google.com/search/docs/data-types/how-to?hl=fr)) :
![Exemple de position zéro](images/posts/2019/microdata-offers/tuer-son-sim.png)
