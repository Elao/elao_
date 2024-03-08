---
type:               "post"
title:              "La pratique du DDD au quotidien"
date:               "2024-01-30"
lastModified:       ~

description:        "Quelques réflexions sur notre pratique quotidienne du DDD, ses avantages et certains écueils"
tableOfContent:     false

thumbnail:          "content/images/blog/2024/ddd-bg.jpg"
banner:             "content/images/blog/2024/ddd-bg.jpg"
tags:               ["DDD", "Conception"]

authors:            ["xavierr"]
---

## Préambule : Kesako le dédédé ?

Le Domain-Driven Design a été formalisé par Eric Evans dans un livre datant de 2003 et qui fait référence encore aujourd'hui : **Domain-Driven Design, Tackling Complexity in the Heart of Software**.

<figure>
    <img width="400" src="content/images/blog/2024/ddd-bible.jpg" alt="DDD bible">
</figure>

Pour faire court, il s'agit d'une méthodologie de conception d'applications proposée par Evans visant à mettre le métier à modéliser au coeur de cette conception, dans le cadre d'un échange soutenu et régulier entre équipes techniques et experts du métier.

Cette méthodologie repose sur plusieurs concepts de haut niveau qui sont les piliers du DDD : le langage omniprésent ("ubiquitous language"), l'acquisition du domaine, la cartographie d'ensemble de l'application et le découpage en contextes bornés ("bounded contexts"), les itérations et le raffinement continu (distillation), etc. Elle propose également aux développeurs des modèles et des pratiques concrètes pour modéliser au mieux un domaine métier ; ce sont par exemple les blocs constitutifs d'une conception métier, tels que les entités, les value objects, les services, les fabriques, les repositories, ainsi que les architectures en couches, etc.

!!! info "Et depuis 2003 ?"
    Le livre d'Evans a été publié en 2003. Entretemps, les développeurs se sont approprié les pratiques pronées dans le livre et ont également enrichi sa pratique avec de nouvelles approches (en particulier, l'architecture hexagonale, l'event sourcing ou le CQRS). Pour autant, de nombreux concepts présentés dans ce livre demeurent tout-à-fait pertinents et applicables aux applications métiers d'aujourd'hui

!!! info "Le DDD vite fait"
    Le livre d'Evans compte près de 600 pages et n'est pas forcément d'une lecture facile. Il en existe une version résumée : http://seedstack.org/pdf/DDDViteFait.pdf

## Le DDD : un modèle vertueux ?

Un des leitmotivs du DDD recommande de structurer son code pour qu'il colle au plus près du métier. En particulier, les concepts du métier doivent transpirer dans votre code, en adoptant par exemple des noms de package, de classe ou de méthodes qui sont le reflet des concepts et des actions du métier. De même l'organisation de votre code (les bounded contexts, les packages ou les namespaces) devra refléter autant que possible les grands pans fonctionnels du métier (la logistique, les ventes, les expéditions, le catalogue des produits, etc.). Un des grands avantages de cette pratique, c'est que l'acquisition de la logique métier, étape préalable avant toute intervention sur du code, constitue en soi un grand pas vers l'assimilation du code : dès lors que vous avez digéré le métier, il est plus simple de s'approprier le code écrit par un autre développeur si ce code a été organisé selon une logique métier partagée par tous, et non pas selon des considérations techniques ou très personnelles. Cela peut paraître évident aujourd'hui, mais avant la généralisation de la pratique du DDD, où les influences techniques ou les habitudes personnelles primaient dans la structuration et la rédaction du code, il n'était pas rare pour un développeur de devoir assimiler d'abord le métier (ce qui peut déjà constituer en soi une difficulté non négligeable, notamment lorsque les règles métiers sont complexes), puis au moment de se plonger dans le code, d'essayer de deviner comment ses prédécesseurs avaient traduit ce métier dans leur code. Pirouette intellectuelle très dispensable, qui ne faisait qu'ajouter de la complexité, de manière totalement artificielle ! Le DDD tend à gommer au maximum les écarts pouvant exister entre le métier et votre code et ça n'est pas la moindre de ses vertus.

!!! info "A propos de l'acquisition du métier"
    Les concepts importants du métier n'apparaissent pas toujours dès le début de l'analyse du métier, notamment lorsque ce métier est complexe ou fonctionnellement riche. Il faut parfois plusieurs itérations avant de dessiner de manière satisfaisante les frontières des différents pans fonctionnels, identifier le coeur de métier, assimiler et modéliser finement une partie du métier un peu complexe ou bien dégager les concepts essentiels. C'est ce long processus de révélation et d'acquisition qu'Evans appelle la distillation, et ce travail peut parfois faire émerger en cours de route un concept métier qui n'était pas apparu clairement au départ. Et Evans recommande bien évidemment de faire évoluer ou restructurer son code chaque fois que les frontières s'affinent ou lorsqu'un concept métier essentiel se révèle tardivement, pour maintenir un code aussi proche que possible du modèle métier à mesure qu'il s'affine.

Vous avez peut-être croisé au cours de votre carrière des projets dont le code source s'articulait autour des namespaces (ou packages) `Domain|Application|Infrastructure` : il s'agit sans aucun doute de projets inspirés du DDD et [des architectures en couches](./architecture-hexagonale-symfony.md) tels que l'architecture hexagonale, l'onion architecture ou la clean architecture définie par Robert C. Martin (aka Uncle Bob), qui préconisent d'isoler le code modélisant le métier et de limiter au maximum l'adhérence de l'environnement technique (bases de données, frameworks, contexte d'exécution, etc.) sur ce code métier.

  - on sait où mettre les choses, ça se fait naturellement
  - expressivité du code, architectures criantes https://blog.cleancoder.com/uncle-bob/2011/09/30/Screaming-Architecture.html
  - découpages => facilité à tester unitairement ses composants
  - le pattern Specification
  - n'exclut pas les autres modes de conception : TDD, BDD

## Les principaux écueils

- Les difficultés :
  - un peu de réflexion avant de pisser du code (bounded contexts, mise en avant des concepts les plus importants)
  - noblesse des couches => recours intensif aux interfaces
  - raisonner métier
  - maturité des équipes
  - fuites techniques dans le code métier => les accepter parfois
  - les nazis du DDD : suppression des suffixes techniques (Interface)
