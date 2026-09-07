---
type:               interview
title:              "Le format d'article à deux modes de lecture"
date:               "2026-09-04"
description:        "Comment écrire un article qui porte deux lectures — un récit et l'entretien intégral — et bascule de l'une à l'autre. Contenu fictif, à usage de démonstration."

thumbnail:          content/images/blog/interviews/trame-itw.jpg
tableOfContent:     3 # les sections sont en `h2`, leurs sous-titres en `h3`
tags:               ["Elao"]
authors:            ["equentin", "msteinhausser"]

# Modes de lecture. Tout y est facultatif.
#
#   intro       Texte du bloc d'annonce, si celui du gabarit ne convient pas.
#   label       Libellé du contrôle. Par défaut « Récit » et « Entretien intégral ».
#   readingTime Durée de lecture en minutes, affichée sur le contrôle. Omis, le
#               contrôle n'affiche que son libellé.
readingModes:
    narratif:  { readingTime: 3 }
    entretien: { readingTime: 8 }

footnotes:
  - text:   "Petit guide de style du blog"
    url:    "/blog/styleguide/example"
    key:    "styleguide"
  - text:   "Understanding Success Criterion 1.4.3: Contrast (Minimum)"
    url:    "https://www.w3.org/WAI/WCAG22/Understanding/contrast-minimum.html"
    source: "W3C"
    key:    "contraste"
---

Ce guide complète le [guide de style du blog](./example.md) : il documente le format d'article à **deux modes de lecture**. Un même fichier markdown y porte deux lectures — un récit, et l'entretien intégral — entre lesquelles le lecteur bascule sans quitter la page.

Son contenu est **fictif**. L'échange rapporté n'a jamais eu lieu : il n'est là que pour donner à voir tous les cas de rendu. Comme le guide de style, cette page n'est pas publiée en production.

!!! info "Ce que cette page donne à voir"
    Le bloc d'annonce et ses contrôles, un sommaire par mode, une section enchaînant plusieurs échanges, une section sans remise en contexte, une section sans question, quatre intervenants de couleurs distinctes, un intervenant qui reprend la parole et garde la sienne, deux sections homonymes restées adressables séparément, et la citation classique en exergue, disponible dans le récit mais pas dans l'entretien.

!!! warning "Deux défauts assumés, visibles ici"
    La liste de notes est **commune aux deux modes** : chacune des deux notes n'est appelée que depuis un seul mode, et reste pourtant listée dans les deux. Et chaque bascule ajoute une entrée à l'historique : sortir de l'article après plusieurs allers-retours demande autant de retours.

Ce paragraphe-ci appartient à la **zone commune** : tout ce qui précède le premier commentaire de mode est rendu quel que soit le mode affiché.

### La syntaxe, en un coup d'œil

Le format s'active par `type: interview` dans l'en-tête. Tout autre article est inchangé.

```md
<!-- mode: narratif -->

## Une section du récit

De la prose. Un paragraphe nu est de la narration, et la citation
en exergue reste disponible.

<!-- mode: entretien -->

## « Un titre verbatim »

> Une remise en contexte, un à trois paragraphes, voix de la rédaction.
>
> **Eva :** La question.

La réponse de l'invité·e, en clair : un paragraphe nu, c'est sa parole.

> Une relance écrite autrement.
> <cite>Maxime</cite>

La réponse suivante.
```

Les deux slugs de mode sont `narratif` et `entretien` ; tout autre fait échouer la construction du site. Les titres de section sont en `##` : le titre de l'article porte déjà le `h1` de la page.

La zone commune est facultative : un article peut s'ouvrir directement sur un commentaire de mode, et n'avoir alors rien de partagé entre les deux lectures.

Le nom d'un intervenant s'écrit indifféremment `**Nom :**` en tête de paragraphe ou `<cite>Nom</cite>` en fin. Sa couleur lui est attribuée à sa première apparition et ne change plus. Rien ne distingue une question d'une relance : les deux sont une prise de parole.

Deux citations qui se suivent fusionnent en une seule sous Parsedown, et le rendu les re-découpe : les écrire collées ou séparées d'une ligne vide revient au même. Une conséquence à connaître, en revanche — **une remise en contexte ne se place qu'en tête de citation**, avant tout nom. Placée juste après une question, elle est absorbée par la prise de parole de celle-ci ; il faut un paragraphe nu, c'est-à-dire une réponse, pour rouvrir un bloc de contexte.

<!-- mode: narratif -->

## Ce que le récit sait faire

Le mode récit est un article de blog ordinaire. Un paragraphe nu y est de la narration, la voix de la rédaction ; les titres, les listes, le code, les images et les notes[^styleguide] s'y comportent comme partout ailleurs sur le blog.

Il se lit vite et s'organise par thème. C'est le mode affiché à l'arrivée, sauf si l'adresse en désigne un autre.

### Un sous-titre, pour le sommaire

Le sommaire du mode reprend les deux niveaux, `h2` et `h3`, selon la profondeur déclarée dans l'en-tête. Chaque mode a le sien, et un seul est affiché à la fois.

## La citation en exergue

Contrairement au mode entretien, le récit conserve la citation en exergue du blog. La première prend la variante simple :

> Une citation, dans le style habituel des articles.
> <cite>Une personne citée</cite>

Et la suivante, le fond plein, comme partout ailleurs sur le blog :

> Une seconde citation, qui reçoit l'autre variante.
> <cite>Une autre personne citée</cite>

## Pour finir

Cette section porte le même titre que celle du mode entretien. Les deux restent pourtant adressables séparément : les identifiants de titres sont préfixés par le slug du mode, et les liens internes réécrits en conséquence.

<!-- mode: entretien -->

## « Une section qui enchaîne plusieurs échanges »

> Le titre d'une section d'entretien est une citation de l'invité·e, pas un titre descriptif. Ce bloc-ci est une remise en contexte : il porte la voix de la rédaction et n'est attribué à personne.
>
> Il peut compter plusieurs paragraphes. Il est facultatif — la section suivante n'en a pas.
>
> **Eva :** Et la question vient à la suite, dans la même citation ou dans une autre, au choix.

La réponse est un paragraphe nu. C'est elle qui porte le corps du texte, et c'est pourquoi elle n'est pas attribuée : dans ce mode, tout ce qui n'est pas marqué est la parole de l'invité·e.

Elle peut évidemment compter plusieurs paragraphes.

> **Maxime :** Un second intervenant reçoit une couleur distincte, sans que la rédaction ait rien à saisir.

Les couleurs sont attribuées par ordre d'apparition dans l'article, et toutes tiennent le contraste exigé pour du petit texte[^contraste].

> Une relance écrite avec l'autre forme, celle que le guide de style enseigne déjà.
> <cite>Camille</cite>

Les deux écritures produisent exactement le même rendu.

## « Une section sans remise en contexte »

> **Sacha :** Ici, la citation ouvre directement sur un nom : la section n'a pas de remise en contexte.

Une prise de parole peut aussi absorber les paragraphes qui la suivent dans la même citation, quand ils ne portent pas de nom.

> **Sacha :** Comme celle-ci.
>
> Ce paragraphe-là appartient encore à Sacha.

Quatre intervenants sont désormais apparus : la palette est bouclée. Un cinquième reprendrait la première couleur.

## « Une section où un intervenant reprend la parole »

> **Eva :** Eva revient, et retrouve la couleur qui lui avait été attribuée au début de l'article.

Un même nom garde sa couleur d'un bout à l'autre, quelle que soit la forme employée pour l'écrire.

## Pour finir

> **Maxime :** Cette section porte le même titre que celle du récit, et reste adressable séparément.

Les deux sommaires pointent chacun vers la sienne, et un lien venu de la zone commune est résolu vers le mode affiché à l'arrivée.

## Pour aller plus loin

Cette dernière section ne contient aucune question : elle referme l'entretien sur des liens, comme le ferait la clôture d'un vrai article.

- [Le guide de style du blog](./example.md)
- [Les critères de contraste du W3C](https://www.w3.org/WAI/WCAG22/Understanding/contrast-minimum.html)
