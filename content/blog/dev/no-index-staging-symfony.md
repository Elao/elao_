---
type:               "post"
title:              "Comment empêcher les moteurs de recherche d'indexer votre app Symfony en staging ?"
date:               "2019-07-10"
lastModified:       ~

description:        "Les pages de votre application n'ont pas vocation à être présentes dans les moteurs de recherche ? Voici une courte explication pour vous aider à empêcher le crawl et l'indexation."

thumbnail:          "content/images/blog/thumbnails/judging-sardine-small.jpg"
banner:             "content/images/blog/headers/judging-sardine-large.jpg"
tags:               ["Symfony", "seo", "no-index"]
categories:         ["Dev", "Symfony", "seo"]
authors:            ["mcolin", "aldeboissieu"]

---
L'indexation par les robots des moteurs de recherche des urls de staging ou de démonstration sont des cas classiques de [#SEOHorrorStories](https://www.webrankinfo.com/dossiers/conseils/horreurs-du-seo). En effet, cette situation est gênante, pour deux raisons :

- L'entreprise ne souhaite probablement pas exposer à ses concurrents ou aux curieux du travail en cours,
- Le contenu relatif à l'entreprise est disponible sous plusieurs urls, induisant un fort risque de dilution de la pertinence du contenu du site "officiel", puisque celui-ci peut être proposé sur deux pages différentes (c'est ce qu'on appelle la duplication de contenu).

Voyons ensemble **quelques solutions pour ne pas indexer les pages publiques de nos applicatifs**, si nous n'en avons pas besoin.

## Les meilleures solutions 💡: l'authentification côté serveur et le filtre par IP

Le meilleur moyen d'empêcher tout crawl des robots et visites des internautes est d'**imposer une authentification côté serveur**. Une autre bonne solution est de filtrer l'accès au site selon l'IP, c'est à dire qu'on autorise l'affichage que si vous y êtes autorisés.
Bien sûr, dans les deux cas, les robots ne peuvent pas accéder aux pages, empêchant de ce fait tout risque de crawl et donc d'indexation.

Néanmoins, ces deux solutions peuvent parfois entraîner de nombreuses contraintes, par exemple si vous utilisez différents sous-domaines pour vos assets ou pour vos apis. La recette peut alors se compliquer sur les features les plus importantes du site.


### Le plan B 👍 : l'en-tête de réponse HTTP

Cette **instruction X-Robots-Tag indiquera aux robots de ne pas indexer la page**. Attention, cette méthode ne doit pas être couplée avec une directive de disallow de l'intégralité du robots.txt, puisque les bots n'auraient jamais accès à ce tag.

Une des variantes est la balise meta robots noindex, [c'est une des solutions décrites par Google dans sa documentation officielle](https://support.google.com/webmasters/answer/93710?hl=fr).

### Comment paramétrer ce tag sur Symfony ?

Depuis Symfony 4.3, [une configuration](https://symfony.com/blog/new-in-symfony-4-3-automatic-search-engine-protection) permet d'ajouter automatiquement le header `X-Robots-Tag: noindex` aux réponses de Symfony.

```yaml
# config/packages/framework.yaml
framework:
    disallow_search_engine_index: true
```

Néanmoins, **vous ne pouvez modifier cette configuration qu'en fonction de l'environnement Symfony** (`dev`, `prod`, `test`, ...), et non en fonction du serveur. L'idée serait d'ajouter ce header sur les serveurs de staging, demo ou recette par exemple, et de ne pas l'ajouter sur le serveur de production, peu importe l'environnement Symfony qui est utilisé.

Malheureusement, **cette configuration ne peux pas être pilotée par une variable d'environnement** car elle impacte directement le container (définition d'un listener) et Symfony ne permet pas de faire cela au runtime. Je conseille donc de ne pas utiliser cette configuration.

La solution est de passer par la configuration **nginx** ou **Apache** de votre serveur pour ajouter le header. Par exemple avec nginx:

```nginx
server {
    ...
    add_header X-Robots-Tag "noindex";
    ...
}
```

Et avec Apache (sous réserve que le mod header soit activé) :

```apacheconf
...
SetEnv X-Robots-Tag noindex
...
```

Cette solution a l'avantage de fonctionner **peu importe la version de Symfony, le framework ou le langage utilisé par votre application**. De plus, elle ne pourra pas être désactivée lors d'un mauvais déploiement.

## La chose à ne pas faire 🙅‍♀️ : interdire l'indexation via le robots.txt

Grâce à la directive `disallow`, on pense pouvoir empêcher les robots de visiter et d'indexer les pages de notre site. Mais ce n'est pas tout à fait l'objectif de `disallow`, **qui n'empêche pas l'indexation mais le crawl**. Résultat : vous pouvez retrouver des pages indexées dans les moteurs de recherche, même si le robot ne remonte ni meta description, ni title. Vous pouvez ainsi lire le fameux message :

```A description for this result is not available because of this sites's robots.txt```

De plus, il n'est pas rare de trouver en production des fichiers `robots.txt` paramétrés pour le staging, car ils auraient été oubliés lors de la mise en production 🙀.

Bon à savoir : la directive Noindex, qui était rarement utilisée, [a été officiellement abandonnée par Google](https://webmasters.googleblog.com/2019/07/a-note-on-unsupported-rules-in-robotstxt.html).

## Oups, mon site avec des urls de staging a été indexé ...

Faites le nécessaire pour empêcher les futures visites de robots. Vous pouvez ensuite utiliser [l'outil de suppression d'URL de la Search Console](https://www.google.com/webmasters/tools/removals), et demander la suppression des URLs problématiques.
