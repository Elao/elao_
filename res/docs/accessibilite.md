# Accessibilité : couleurs, contrastes et états

Référence pour toute nouvelle interface du site. Elle répond au point
« documenter le design graphique » de la check-list RGAA interne.

Les ratios ci-dessous ne sont pas repris d'une feuille de style : ils ont été
mesurés sur le rendu réel de 28 pages couvrant tous les gabarits, soit
2 877 éléments porteurs de texte, en relevant la couleur calculée et le fond
effectif remonté dans l'arbre du document.

## La palette

| Token | Valeur |
|---|---|
| `$color-brand` | `#ff4345` |
| `$color-primary` | `#7f1a55` |
| `$color-secondary` | `#fee3e4` |
| `$color-tertiary` | `#f4756d` |
| `$color-dark` | `#0d3a5a` |
| `$color-light` | `#2ecccb` |
| `$color-text` | `#390725` |
| `$color-info` | `#1e7695` |
| `$color-warning` | `#dbb371` |

## Seuils applicables

| Cas | Ratio minimal | Critère |
|---|---:|---|
| Texte courant | 4,5:1 | WCAG 1.4.3 |
| Grand texte — ≥ 24 px, ou ≥ 18,5 px en gras | 3:1 | WCAG 1.4.3 |
| Élément non textuel porteur de sens : bordure de champ, pictogramme, indicateur de focus | 3:1 | WCAG 1.4.11 |

## Combinaisons employées et conformes

Sur les 81 combinaisons possibles entre les neuf tokens, le site n'en emploie
que 25. Voici les 13 qui atteignent 4,5:1 — **celles sur lesquelles s'appuyer
sans réfléchir.**

| Texte | Fond | Ratio | Occurrences |
|---|---|---:|---:|
| `$color-text` | `blanc` | **17,06:1** | 706 |
| `$color-primary` | `blanc` | **9,63:1** | 441 |
| `$color-info` | `blanc` | **5,15:1** | 405 |
| `$color-text` | `$color-secondary` | **14,07:1** | 136 |
| `blanc` | `$color-dark` | **11,86:1** | 46 |
| `$color-primary` | `$color-secondary` | **7,94:1** | 42 |
| `$color-light` | `$color-dark` | **6,00:1** | 41 |
| `blanc` | `$color-info` | **5,15:1** | 19 |
| `$color-secondary` | `$color-primary` | **7,94:1** | 16 |
| `blanc` | `$color-primary` | **9,63:1** | 14 |
| `$color-dark` | `$color-secondary` | **9,79:1** | 13 |
| `$color-secondary` | `$color-dark` | **9,79:1** | 3 |
| `$color-text` | `$color-light` | **8,62:1** | 3 |
## Combinaisons employées mais non conformes

Les 12 suivantes échouent au seuil du texte courant. Elles portent **18 % du
texte du site**. Ne pas les reprendre pour du texte de taille normale.

| Texte | Fond | Ratio | Occurrences |
|---|---|---:|---:|
| `blanc` | `$color-brand` | **3,42:1** | 149 |
| `blanc` | `$color-tertiary` | **2,76:1** | 76 |
| `$color-brand` | `blanc` | **3,42:1** | 71 |
| `$color-brand` | `$color-secondary` | **2,82:1** | 67 |
| `$color-tertiary` | `blanc` | **2,76:1** | 22 |
| `$color-secondary` | `$color-brand` | **2,82:1** | 13 |
| `$color-info` | `$color-secondary` | **4,25:1** | 6 |
| `$color-brand` | `$color-dark` | **3,47:1** | 3 |
| `$color-tertiary` | `$color-primary` | **3,49:1** | 2 |
| `$color-primary` | `$color-tertiary` | **3,49:1** | 2 |
| `$color-tertiary` | `$color-secondary` | **2,28:1** | 2 |
| `$color-secondary` | `$color-info` | **4,25:1** | 1 |
Les deux premières lignes sont aussi les plus fréquentes du site : c'est là que
se concentre le problème, et il ne se règle pas au cas par cas. Une refonte des
tokens est le seul remède — c'est le sujet de l'atelier **P2-1**, pas une
correction de composant.

Repère utile : `$color-text` est le token qui passe sur le plus de fonds —
**cinq** des huit autres (`$color-brand`, `$color-secondary`, `$color-tertiary`,
`$color-light`, blanc). C'est le premier réflexe quand un fond de marque est
imposé. Il échoue en revanche sur `$color-primary`, `$color-dark` et
`$color-info`, tous trop sombres : là, c'est le blanc qu'il faut.

## États

### Prise de focus

Défini une seule fois, dans `assets/scss/base/_focus.scss`, et applicable à tout
le site :

```scss
:focus-visible {
  outline: 3px solid $color-primary;
  outline-offset: 2px;
  box-shadow: 0 0 0 2px #fff;
}
```

Le **double anneau** n'est pas décoratif. Dix-huit composants ont un fond sombre,
et sur `$color-tertiary` aucune couleur unique n'atteint 3:1. La combinaison
garantit qu'au moins l'un des deux anneaux reste visible sur les huit tokens :
l'anneau violet porte sur les fonds clairs, le halo blanc sur les fonds sombres.

**Ne jamais écrire `outline: none`.** Dix suppressions de ce type existaient dans
le dépôt ; toutes battaient la règle globale par spécificité, et il a fallu les
retirer une par une plutôt que les surcharger. Si un composant a besoin d'un
indicateur particulier, il doit en proposer un autre, pas supprimer celui-ci.

`:focus-visible`, et non `:focus` : rien ne change au clic de souris sur un lien
ou un bouton. Les champs de saisie et les listes déroulantes affichent l'anneau
même au clic, c'est le comportement natif du navigateur et il est souhaitable.

### Un piège de cascade à connaître

`assets/scss/generic/_a.scss` pose :

```scss
a:hover, a:active, a:focus { color: $color-dark; }
```

Cette règle est en spécificité **(0,1,1)**. Une classe posée sur le `<a>`
lui-même n'est qu'en **(0,1,0)** : elle perd. Un composant dont la couleur de
texte dépend d'une classe se retrouve donc avec la couleur générique dès que
l'élément est survolé ou focalisé.

Ce piège a produit deux contrastes à **1,21:1** en production, dont un resté
invisible pendant des mois. La règle : **poser la couleur sur l'état**
(`&:focus`, `&:hover`) et pas seulement sur la classe.

Le même mécanisme joue pour les règles de conteneur du type
`.composant a { color: … }` : elles écrasent les boutons et liens qui portent
leurs propres couleurs.

### Lien d'évitement

Premier élément focalisable de chaque page, masqué hors écran et révélé au focus
(`assets/scss/components/_skip-link.scss`).

Sa taille de **24 px est une contrainte de conformité, pas un choix esthétique** :
blanc sur `$color-brand` plafonne à 3,42:1, sous les 4,5:1 du texte courant. À
24 px le texte relève du « grand texte », dont le seuil est 3:1. Réduire cette
valeur casse la conformité.

## Ce qui reste à maquetter

La check-list demandait aussi de **maquetter les différents états**. Les états
ci-dessus ont été définis en code, sans revue design, parce qu'aucune maquette
n'existait — c'est une dette assumée, pas une méthode.

Restent sans définition :

- **survol** — sur la plupart des composants, il ne change qu'une couleur, ce qui
  ne suffit pas au titre de WCAG 1.4.1 ;
- **erreur et validation de formulaire** — aucun état défini, aucune couleur
  sémantique dans la palette ;
- **désactivé** — aucun état défini ;
- **la palette elle-même**, cf. la section sur les combinaisons non conformes.
