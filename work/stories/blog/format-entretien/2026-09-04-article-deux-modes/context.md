---
id: STOR-001
epic: blog
feature: format-entretien
slug: article-deux-modes
title: Un article de blog porte deux modes de lecture, récit et entretien, avec bascule
created: 2026-09-04
---

# Contexte

> [!NOTE]
> Le processus `/stories:light` prévoit ici un `context.md` consolidé, produit par trois agents
> (fonctionnel, architecture, UX). Cette story arrive avec un `brief.md` fonctionnel complet et un
> `plan.md` détaillé : faire réécrire les mêmes contenus produirait de la redondance, pas du contexte.
> Ce document sert donc de **carte de lecture** et ne porte que ce que les deux autres n'ont pas.

## Où lire quoi

| Question | Document |
|---|---|
| Pourquoi cette story, quel périmètre, quels critères d'acceptance | [`brief.md`](brief.md) |
| Comment c'est construit, quelles tâches, quels risques | [`plan.md`](plan.md) |
| Chemins des ébauches éditoriales, pièges du dépôt relevés à l'exploration | `CLAUDE.local.md` (non versionné) |
| Avancement, décisions prises en cours de route | [`dev.md`](dev.md) |

## Description fonctionnelle — l'essentiel

Un article d'entretien porte deux lectures : un **récit** thématique qui se lit vite, et l'**entretien
intégral** qui restitue l'échange et remet l'invité·e au premier plan. Le lecteur arrive sur le récit,
un bloc d'annonce lui expose les deux options avec leur durée, et il bascule quand il veut — depuis le
haut de page ou depuis le rappel en fin de mode.

Les deux modes n'ont **pas la même couverture** : ce sont deux textes écrits séparément, l'un en propos
rapporté, l'autre en discours direct. La bascule change donc de forme *et* de profondeur, ce que
l'affichage des durées rend explicite.

Le mode entretien introduit deux blocs que le blog ne savait pas rendre : les **prises de parole
nommées** des intervieweurs, fondues dans le fil du texte, et les **remises en contexte** de la
rédaction, qui posent le cadre avant une question.

## Impacts UX

**Trois décisions structurent l'expérience**, toutes tranchées en atelier :

1. **Le récit par défaut.** Arriver sur 8 100 mots ferait fuir avant d'avoir vu qu'un mode court
   existait. L'inverse donne envie d'aller plus loin.
2. **La bascule est atteignable deux fois** — dans le bloc d'annonce, et en fin de chaque mode, là où
   la question se pose vraiment pour qui vient de finir sa lecture. Pas de barre flottante : rien ne
   recouvre le texte.
3. **L'adresse porte le mode**, et un lien vers une section ouvre le mode qui la contient. C'est ce qui
   rend l'entretien citable par passage.

**Ce qui est délibérément absent** : aucune mémoire du choix entre visites, aucune tentative de
conserver la position de lecture. Les deux modes n'ont ni les mêmes sections ni la même couverture —
la correspondance qui rendrait la seconde possible n'existe pas.

**Deux coûts assumés**, actés au brief plutôt que découverts en recette : chaque bascule empile une
entrée d'historique, donc sortir de l'article après plusieurs allers-retours demande autant de retours ;
et les notes de bas d'article restent une liste commune, donc une note appelée dans un seul mode reste
listée dans les deux.

**Accessibilité.** Les contrôles sont des boutons bascule `aria-pressed` dans un groupe nommé, et non
un `tablist` : les sommaires restent hors des sections de mode, ce que le motif d'onglets interdit.
Même information annoncée, sans mentir sur la structure — arbitrage à rouvrir en revue design si le
sommaire peut déménager. Sans script, les deux modes s'affichent à la suite, chacun sous son titre :
le pire cas est l'article complet, jamais un article amputé.
