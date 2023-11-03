---
name: "Accesseo"
title:
- "Améliorez l'accessibilité de vos pages"
- "grâce au bundle Accesseo"
titleSeo: "Accessibilité et SEO de vos pages Symfony avec Accesseo"
metaDescription : ""
articles:
- "dev/donnees-structurees-offre-emploi"
- "dev/no-index-staging-symfony"
---
Chez Elao, lorsque nous travaillons sur des pages web, **nous cherchons à offrir la meilleure expérience possible à l'utilisateur**. Cela passe par un travail d'UX design et de conception au préalable, mais également d'optimisation invisible du HTML qui permettent un meilleur accès au contenu, quelque soit les conditions de lecture de la page (débit internet faible, port d'équipements contraignants comme des gants, lecture d'écran, etc.). 

Nous avons développé Accesseo afin de pouvoir, au moment du développement, avoir un œil sur ces optimisations et les mettre en place, tout en impactant le moins possible le temps passé à faire ces ajustements. 

⚡️ [Voir Accesseo sur Packagist](https://packagist.org/packages/elao/accesseo) ⚡️

## Pourquoi parler de d'accessibilité et de SEO en même temps ?

Les stratégies de SEO et d'accessibilité doivent être établis en amont du projet, par des spécialistes, car celles-ci impactent le parcours utilisateur, l'ergonomie et le design du projet. Néanmoins, nous pensons que des optimisations basiques du HTML, partagées par le SEO et l'accessibilité, peuvent déjà améliorer grandement l'exploration du contenu. En effet, le SEO et l'accessibilité poursuivent le même objectif : un site facilement accessible et compréhensible, pour les humains .... et pour les robots !

Ces optimisations passent par une navigation claire et simple, un temps de chargement rapide, une hiérarchisation des contenus, des titres de pages éloquents et enfin du contenu alternatif lorsque le media ne contient pas de texte. Bien évidemment, **ces exemples ne sont pas exhaustifs**, une mise en place de stratégie d'accessibilité et / ou de SEO dépend des objectifs et du budget alloué, et nécessite une mise en oeuvre complète. 

## Accesseo, comment ça marche ?

Acesseo permet de remonter, à partir du HTML, ces signaux que l'on peut, si on le souhaite, optimiser - ou non, selon notre stratégie. Ce bundle s'adresse aux utilisateurs de [Symfony](./symfony.md), au moment du développement : webmasters, développeurs ou intégrateurs. Les informations sont visibles sur la Symfony Debug toolbar et dans le profiler dans leur intégralité.


!!! Attention
    Est-ce qu'en installant Accesseo mon projet sera 100% accessible ? Non. Accesseo est un outil qui permet de remonter des informations simples à détecter et rapides à corriger. Une bonne accessibilité repose sur de nombreux autres critères comme le contraste, la navigation, etc.

### Quelques exemples :

<figure>
    <img src="content/images/terms/accesseo-seo.png" alt="Capture d'écran de la debug bar de Symfony">
    <figcaption>
      <span class="figure__legend">Onglet SEO dans la debug bar</span>
    </figcaption>
</figure>

<figure>
    <img src="content/images/terms/accesseo-seo-insights.png" alt="Capture d'écran de la page SEO du profiler Symfony">
    <figcaption>
      <span class="figure__legend">Informations SEO dans le profiler : les insights</span>
    </figcaption>
</figure>

<figure>
    <img src="content/images/terms/accesseo-seo-microdata.png" alt="Capture d'écran de la page SEO du profiler Symfony">
    <figcaption>
      <span class="figure__legend">Informations SEO dans le profiler : les microdata</span>
    </figcaption>
</figure>

<figure>
    <img src="content/images/terms/accesseo-accessibility.png" alt="Capture d'écran de la debug bar de Symfony">
    <figcaption>
      <span class="figure__legend">Onglet accessibilité dans la debug bar</span>
    </figcaption>
</figure>

<figure>
    <img src="content/images/terms/accesseo-accessibility-profiler.png" alt="Capture d'écran de la page accessibilité du profiler Symfony">
    <figcaption>
      <span class="figure__legend">Informations sur l'accessibilité dans le profiler</span>
    </figcaption>
</figure>

## En savoir plus sur la qualité web : 

- [Check-list Qualité web](https://checklists.opquast.com/fr/assurance-qualite-web/)

## En savoir plus sur l'accessibilité : 

- [Check-list RGAA](https://www.numerique.gouv.fr/publications/rgaa-accessibilite/methode-rgaa/criteres/#contenu)
- [Série d'articles sur l'accessibilité sur UX Collective](https://uxdesign.cc/tagged/accessibility)

## En savoir plus sur le SEO technique :

- [Web Developer SEO Cheat Sheet (MOZ)](https://moz.com/learn/seo/seo-cheat-sheet)
- [Documentation SEO pour les développeurs (Google)](https://developers.google.com/search/docs/advanced/guidelines/get-started-developers)


