---
name: "Stenope"
logo: "build/images/technos/default.svg"
title: ["Stenope", "le générateur de site statique pour les développeurs Symfony"]
titleSeo: "Stenope : le générateur de site statique pour les développeurs Symfony"
metaDescription : >
    Découvrez l'origine de Stenope, le générateur de site statique créé pour les développeurs Symfony
    et comment Elao tire parti de cet outil pour créer des sites performants.
articles: ["elao/rebranding-la-tech", "dev/stenope-skeleton"]
---

## L'origine

Stenope est né d'un besoin simple : **générer un site statique à partir d'un socle que nous maitrisons de bout en bout**,
c'est-à-dire un site [Symfony](./symfony.md).

En effet, tel que nous le décrivions
dans [notre blog post concernant la refonte de notre site](../blog/elao/rebranding-la-tech.md), nous avons expérimenté
par le passé de nombreux outils existants pour la génération de contenu, sans pour autant être convaincus, en raison des
restrictions techniques ou fonctionnelles imposées par ces outils.

## Les avantages d'un site statique

Un site statique a l'avantage d'être :
- performant et peu coûteux en ressources
- peu sensible aux attaques
- facilement hébergé

Plutôt que de requêter une base de données, une API ou un tiers à chaque fois qu'un utilisateur visite une page,
le site est construit une seule fois et les fichiers sont servis directement par le serveur web, 
arrivant tels quels dans votre navigateur.

## Un outil sur-mesure pour les développeurs Symfony

Stenope est un outil qui s'inscrit dans la lignée de ce que nous faisons déjà au quotidien : 
développer des applications Symfony.  
L'outil se veut minimaliste et sa fonctionnalité principale est de scanner votre application comme le ferait un robot 
d'indexation, afin de découvrir chacune des pages et de les exporter vers des fichiers statiques.

D'ailleurs, bien que Stenope fournisse également le moyen de gérer et accéder à des contenus,
cette partie de l'outil est complètement optionnelle ou peut être étendue pour lire différentes sources
(système de fichier local, base de données, <abbr title="Content Management System">CMS</abbr> headless, …).

Il est donc conçu pour répondre à nos besoins et non l'inverse, en permettant de se câbler sur la plupart des 
applications Symfony.

## Des cas d'usage variés

Du site vitrine, blogging et autre site de contenus, à la génération de catalogues mensuels au format web ou dédié au print,
l'utilisation d'un site statique peut s'avérer pertinente dans bien plus de cas qu'on ne l'imagine au premier abord.

Nous utilisons Stenope pour générer notre site web, nos sites personnels, mais aussi pour des projets clients.

## Initiative

Vous pouvez retrouver Stenope parmi la liste des [générateurs de sites statiques](https://jamstack.org/generators/stenope/)
de l'initiative [Jamstack](https://jamstack.org/), un mouvement qui promeut entre autre l'utilisation de sites statiques 
pour la construction d'applications web plus performantes, sécurisées et découplées.

## References

- [La documentation de Stenope](https://stenopephp.github.io/Stenope/)
- [Stenope skeleton](https://stenopephp.github.io/skeleton/)
- [WTF is Jamstack](https://jamstack.wtf/)
- [Jamstack : définition, avantages et bonnes pratiques pour moderniser les architectures web](https://www.blogdumoderateur.com/jamstack-definition-avantages-bonnes-pratiques-moderniser-architectures-web/)
