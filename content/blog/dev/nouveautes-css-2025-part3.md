---
type:               "post"
title:              "Les couleurs CSS en 2026 : 4 fonctionnalités pour des thèmes vivants et adaptatifs"
date:               "2026-05-12"
lastModified:       ~
tableOfContent:     3

description: >
    color(), color-mix(), syntaxe relative des couleurs, color-scheme et light-dark()…
    Découvrez comment CSS prend en charge nativement la gestion des couleurs et des thèmes, sans préprocesseur ni JavaScript.

thumbnail:          content/images/blog/2026/dev/nouveautes-css-2025/thumbnail-part3.jpg
#banner:            ~ # Uniquement si différent de la miniature (thumbnail)
tags:               [css, frontend, design-system, theming]
authors:            [pmoreau]
#tweetId:           "" # Ajouter l'id du Tweet après publication
---

Dans les [deux](https://www.elao.com/blog/dev/nouveautes-css-2025) [premiers articles](https://www.elao.com/blog/dev/animer-structurer-css), on a vu comment CSS modernise sa façon de structurer le code et d'animer les interactions.
Cette fois, on s'attaque à un domaine où SASS, Less et leurs cousins préprocesseurs régnaient en maîtres depuis des années : **la gestion des couleurs**.

Spoiler : aujourd'hui, CSS sait définir des couleurs vives au-delà du sRGB, en mélanger plusieurs, en dériver de nouvelles depuis une couleur existante, et basculer proprement entre thème clair et thème sombre. Le tout nativement, sans la moindre dépendance.

---

## color() : sortir du sRGB des années 90

Pendant 30 ans, on a écrit nos couleurs en hex, en `rgb()` ou en `hsl()`. Tous ces formats ont un point commun : ils vivent dans le **gamut sRGB**, un espace colorimétrique conçu pour les écrans CRT des années 90. Or, les écrans modernes (la plupart des Mac, iPhone, et écrans pros récents) couvrent un gamut bien plus large appelé **Display P3**, environ 25 % plus étendu. Les rouges, verts et oranges les plus vifs que ces écrans peuvent afficher sont tout simplement inaccessibles en sRGB.

`color()` permet d'accéder explicitement à ces espaces colorimétriques modernes :

```css
.brand {
  /* sRGB classique : limité au gamut des années 90 */
  color: #ff0000;

  /* Display P3 : rouge plus saturé sur écrans modernes */
  color: color(display-p3 1 0 0);
}
```

Sur un écran compatible P3, le second rouge est visiblement plus éclatant. Sur un écran sRGB plus ancien, le navigateur convertit automatiquement vers le rouge sRGB le plus proche. Pas besoin de fallback.

`color()` accepte plusieurs espaces RGB (`srgb`, `display-p3`, `rec2020`, `prophoto-rgb`…) et XYZ (`xyz`, `xyz-d50`, `xyz-d65`). Pour les espaces perceptuellement uniformes comme **OKLCH** (qu'on va voir juste après), on utilise la fonction dédiée `oklch()`.

### oklch() : l'espace perceptuellement uniforme

Au-delà des gamuts élargis, le vrai changement de paradigme vient des espaces **perceptuellement uniformes** comme OKLCH. Concrètement, ça veut dire que si tu modifies la luminosité (`L`), la couleur **paraît** uniformément plus claire ou plus foncée pour l'œil humain. Ce n'est pas le cas avec HSL, où un jaune à 50 % de luminosité paraît bien plus clair qu'un bleu à 50 %.

```css
:root {
  --brand: oklch(60% 0.18 250);  /* L (luminosité), C (chroma), H (teinte) */
}

.btn:hover {
  background: oklch(50% 0.18 250); /* Même couleur, visiblement plus foncée */
}
```

C'est ce qui rend OKLCH idéal pour générer des palettes cohérentes : toutes les variantes (50, 100, 200… 900 façon Tailwind) peuvent être dérivées en jouant uniquement sur `L`, sans surprise visuelle.

### Support navigateurs

| Navigateur | Support |
| -- | -- |
| Chrome | ✅ 111+ |
| Edge | ✅ 111+ |
| Firefox | ✅ 113+ |
| Safari | ✅ 15+ |

!!! Note "Pour aller plus loin"
    Consultez la [documentation MDN sur color()](https://developer.mozilla.org/en-US/docs/Web/CSS/color_value/color) et celle sur [oklch()](https://developer.mozilla.org/en-US/docs/Web/CSS/color_value/oklch).

---

## color-mix() : mélanger intelligemment

Avant, pour obtenir une variante d'une couleur de marque (un hover plus foncé, un état désactivé désaturé…), on passait par SASS et ses fonctions `darken()`, `lighten()`, `mix()`. Ou alors on créait dix variables CSS à la main, une par variante.

#### Avant, en SASS

```scss
$brand: #3178c6;

.btn {
  background: $brand;

  &:hover {
    background: mix($brand, black, 75%);
  }

  &:disabled {
    background: mix($brand, gray, 40%);
  }
}
```

#### Maintenant, en CSS natif

```css
:root {
  --brand: #3178c6;
}

.btn {
  background: var(--brand);
}

.btn:hover {
  background: color-mix(in oklch, var(--brand), black 25%);
}

.btn:disabled {
  background: color-mix(in oklch, var(--brand), gray 60%);
}
```

Plus de step de compilation, plus de dépendance, et un avantage de taille : la couleur de marque devient une **variable CSS dynamique**. Tu peux la changer à la volée en JavaScript ou via un thème, et toutes les variantes se recalculent automatiquement, ce qui n'est pas possible avec SASS.

### Le choix de l'espace colorimétrique change tout

L'argument `in oklch` n'est pas anodin. Le même mélange donne un résultat **visuellement très différent** selon l'espace choisi :

```css
/* Mélange en sRGB : passe par un gris boueux au milieu */
background: color-mix(in srgb, blue, yellow);

/* Mélange en OKLCH : transition perceptuellement fluide */
background: color-mix(in oklch, blue, yellow);
```

En pratique, `oklch` (ou `oklab`) donne presque toujours les meilleurs résultats pour les variantes de couleurs et les dégradés, parce que l'interpolation respecte la perception visuelle. C'est d'ailleurs pour cette raison qu'`oklab` est aujourd'hui l'espace par défaut utilisé par les navigateurs pour interpoler les couleurs dans les transitions et les dégradés.

### Support navigateurs

| Navigateur | Support |
| -- | -- |
| Chrome | ✅ 111+ |
| Edge | ✅ 111+ |
| Firefox | ✅ 113+ |
| Safari | ✅ 16.2+ |

!!! Note "Pour aller plus loin"
    Consultez la [documentation MDN sur color-mix()](https://developer.mozilla.org/en-US/docs/Web/CSS/color_value/color-mix).

---

## Syntaxe relative des couleurs : dériver depuis une couleur existante

`color-mix()` est puissant pour mélanger deux couleurs, mais il a une limite : tu ne peux pas modifier précisément un canal spécifique d'une couleur. Tu veux juste réduire l'opacité, augmenter la luminosité de 10 %, ou décaler la teinte de 30° ? C'est la **syntaxe relative des couleurs** qu'il te faut.

Le principe : utiliser le mot-clé `from` pour décomposer une couleur existante en ses canaux, puis recomposer une nouvelle couleur en modifiant uniquement ce qu'on veut.

```css
:root {
  --brand: oklch(60% 0.18 250);
}

.card {
  background: var(--brand);
}

.card:hover {
  /* Même chroma et même teinte, on augmente juste la luminosité */
  background: oklch(from var(--brand) calc(l + 0.1) c h);
}
```

Dans `oklch(from var(--brand) calc(l + 0.1) c h)`, le navigateur :
1. Décompose `--brand` en ses trois canaux OKLCH : `l`, `c`, `h`
2. Recompose une nouvelle couleur avec `l + 0.1` (plus claire), et les mêmes `c` et `h`

À noter : en OKLCH, la valeur de `l` est un nombre entre 0 et 1 (et non un pourcentage), donc on utilise `calc(l + 0.1)` plutôt que `calc(l + 10%)`.

### Usecase concret : générer une palette complète depuis une seule couleur

C'est probablement le cas d'usage le plus puissant. À partir d'une seule couleur de marque, on peut générer toute une palette d'échelle (style Tailwind) en jouant uniquement sur le canal `L`.

#### Avant, en SASS

```scss
$brand: hsl(220, 60%, 60%);

$brand-50:  lighten($brand, 36%);
$brand-100: lighten($brand, 30%);
$brand-200: lighten($brand, 22%);
$brand-300: lighten($brand, 14%);
$brand-400: lighten($brand, 7%);
$brand-500: $brand;
$brand-600: darken($brand, 8%);
$brand-700: darken($brand, 16%);
$brand-800: darken($brand, 24%);
$brand-900: darken($brand, 32%);
```

Ces variantes sont **figées au build**. Si ta couleur de marque change dynamiquement (ex. theming utilisateur), il faut tout recompiler. Et `lighten()`/`darken()` travaillent en HSL, ce qui produit des variantes visuellement inégales.

#### Maintenant, en CSS natif

```css
:root {
  --brand: oklch(60% 0.18 250);

  --brand-50:  oklch(from var(--brand) 0.97 c h);
  --brand-100: oklch(from var(--brand) 0.93 c h);
  --brand-200: oklch(from var(--brand) 0.86 c h);
  --brand-300: oklch(from var(--brand) 0.78 c h);
  --brand-400: oklch(from var(--brand) 0.68 c h);
  --brand-500: var(--brand);
  --brand-600: oklch(from var(--brand) 0.52 c h);
  --brand-700: oklch(from var(--brand) 0.42 c h);
  --brand-800: oklch(from var(--brand) 0.32 c h);
  --brand-900: oklch(from var(--brand) 0.22 c h);
}
```

L'utilisateur change la couleur de marque ? Toute la palette se recalcule automatiquement, en live. Et grâce à OKLCH, les variantes sont **perceptuellement uniformes** : la différence visuelle entre `brand-100` et `brand-200` est la même qu'entre `brand-700` et `brand-800`. Impossible à obtenir aussi facilement en HSL.

### Autre usecase : adapter l'opacité à la volée

```css
.tooltip {
  background: var(--brand);
  border: 1px solid rgb(from var(--brand) r g b / 0.5);
}
```

La bordure utilise exactement la même couleur que le fond, mais avec 50 % d'opacité. Aucune nouvelle variable à déclarer.

### Support navigateurs

| Navigateur | Support |
| -- | -- |
| Chrome | ✅ 119+ |
| Edge | ✅ 119+ |
| Firefox | ✅ 128+ |
| Safari | ✅ 16.4+ |

!!! Note "Pour aller plus loin"
    Consultez la [documentation MDN sur la syntaxe relative des couleurs](https://developer.mozilla.org/en-US/docs/Web/CSS/Guides/Colors/Using_relative_colors).

---

## color-scheme et light-dark() : un dark mode propre, sans media query

Gérer un thème clair et un thème sombre, c'est typiquement le genre de chose qui pousse à dupliquer toutes ses variables CSS, ou à empiler les `@media (prefers-color-scheme: dark)`. Deux features qui fonctionnent ensemble simplifient drastiquement le sujet.

### color-scheme : indiquer au navigateur les modes supportés

`color-scheme` déclare au navigateur quels modes ton site supporte. Le navigateur adapte alors automatiquement les éléments natifs (scrollbars, inputs, sélection de texte) au mode actif.

```css
:root {
  color-scheme: light dark;
}
```

Sans cette déclaration, les scrollbars resteraient blanches même en mode sombre, et la sélection de texte aurait un fond bizarre. **C'est aussi un prérequis obligatoire pour utiliser `light-dark()`** : sans `color-scheme`, la fonction ne saura pas quel mode est actif.

### light-dark() : déclarer les deux valeurs en une seule ligne

`light-dark()` prend deux couleurs : la première pour le mode clair, la seconde pour le mode sombre. Le navigateur applique la bonne automatiquement, en se basant sur le `color-scheme` actif.

#### Avant, avec media queries

```css
:root {
  --bg: white;
  --text: #1a1a1a;
  --border: #e0e0e0;
  --surface: #f7f8fa;
}

@media (prefers-color-scheme: dark) {
  :root {
    --bg: #1a1a1a;
    --text: #f0f0f0;
    --border: #3a3a3a;
    --surface: #242424;
  }
}

.card {
  background: var(--surface);
  color: var(--text);
  border: 1px solid var(--border);
}
```

15 lignes pour 4 variables de thème. Et chaque ajout d'une nouvelle variable demande deux modifications : la valeur claire en haut, la valeur sombre dans la media query.

#### Maintenant, avec light-dark()

```css
:root {
  color-scheme: light dark;
}

.card {
  background: light-dark(#f7f8fa, #242424);
  color: light-dark(#1a1a1a, #f0f0f0);
  border: 1px solid light-dark(#e0e0e0, #3a3a3a);
}
```

Plus de media query, plus de duplication. Les deux valeurs sont déclarées au même endroit, ce qui rend la lecture du code beaucoup plus simple : pas besoin de scroller jusqu'au `@media` pour savoir à quoi ressemble la version sombre d'une couleur.

### Forcer un thème depuis le HTML

Pour permettre à l'utilisateur de choisir manuellement son thème (au-delà de la préférence système), il suffit de surcharger `color-scheme` :

```css
:root[data-theme="light"] { color-scheme: light; }
:root[data-theme="dark"]  { color-scheme: dark; }
```

```html
<html data-theme="dark">
```

Toutes les valeurs déclarées avec `light-dark()` basculent automatiquement, sans rien d'autre à modifier.

### Support navigateurs

| Navigateur | Support |
| -- | -- |
| Chrome | ✅ 123+ |
| Edge | ✅ 123+ |
| Firefox | ✅ 120+ |
| Safari | ✅ 17.5+ |

!!! Note "Pour aller plus loin"
    Consultez la [documentation MDN sur color-scheme](https://developer.mozilla.org/en-US/docs/Web/CSS/color-scheme) et celle sur [light-dark()](https://developer.mozilla.org/en-US/docs/Web/CSS/color_value/light-dark).

---

## Bonus : deux fonctionnalités à surveiller

Ces deux features sont prometteuses, mais leur support est encore trop limité pour être utilisées en production aujourd'hui. À garder dans un coin de la tête pour les mois à venir.

### accent-color : customiser les éléments natifs de formulaire

`accent-color` permet de customiser la couleur des checkbox, radio, range et progress natifs sans avoir à les recréer entièrement en CSS.

```css
:root {
  accent-color: var(--brand);
}
```

D'après MDN, cette fonctionnalité n'est pas encore considérée comme **Baseline** : son comportement reste partiel ou inconsistant dans certains navigateurs (notamment sur mobile). Utilisable en progressive enhancement, mais pas comme solution unique pour un design system.

### contrast-color() : calculer automatiquement la couleur de texte contrastée

`contrast-color()` choisit automatiquement entre noir et blanc selon ce qui contraste le mieux avec une couleur de fond donnée :

```css
.btn {
  background: var(--brand);
  color: contrast-color(var(--brand));
}
```

Le concept est génial pour les design systems où l'utilisateur peut choisir sa couleur de marque. **Mais deux limitations majeures** : le support est aujourd'hui limité à Chrome 136+ et Safari 26+ uniquement, et la fonction ne renvoie que noir ou blanc, donc pour les couleurs en luminosité moyenne (les fameux "mid-tones" comme un orange ou un vert moyen), le résultat peut être discutable d'un point de vue accessibilité.

À surveiller, mais pas prêt pour la prod.

---

## Conclusion

Avec `color()`, `color-mix()`, la syntaxe relative des couleurs et `light-dark()`, CSS reprend la main sur un domaine longtemps confié aux préprocesseurs. On peut désormais définir des couleurs vives au-delà du sRGB, mélanger et dériver dynamiquement, et basculer entre thèmes sans dupliquer une seule variable.

Pour finir, voici un exemple qui combine toutes ces fonctionnalités dans un cas concret de design system : un bouton qui s'adapte au thème clair/sombre, avec des états hover et disabled générés à partir d'une seule couleur de marque.

```css
:root {
  color-scheme: light dark;

  /* Une seule couleur de marque, en OKLCH pour la prévisibilité */
  --brand: oklch(60% 0.18 250);

  /* Variantes claire et foncée dérivées via la syntaxe relative */
  --brand-light: oklch(from var(--brand) 0.85 c h);
  --brand-dark:  oklch(from var(--brand) 0.35 c h);

  /* Couleurs sémantiques qui basculent selon le thème */
  --btn-bg:   light-dark(var(--brand), var(--brand-light));
  --btn-text: light-dark(white, oklch(0.15 0 0));
}

.btn {
  background: var(--btn-bg);
  color: var(--btn-text);
  border: 1px solid color-mix(in oklch, var(--btn-bg), black 20%);
  padding: 0.5rem 1rem;
  border-radius: 6px;
}

.btn:hover {
  /* Hover : on assombrit en mélangeant avec du noir */
  background: color-mix(in oklch, var(--btn-bg), black 15%);
}

.btn:disabled {
  /* Disabled : même couleur, mais désaturée et avec 50% d'opacité */
  background: oklch(from var(--btn-bg) l calc(c * 0.3) h / 0.5);
}
```

Une seule variable `--brand` pilote tout : la couleur de base, ses variantes claire/foncée, le bouton dans les deux thèmes, son hover et son état désactivé. Si demain le designer change `--brand` pour `oklch(70% 0.2 30)` (un orange), tout l'écosystème de couleurs se recompose automatiquement, en restant visuellement cohérent. Plus besoin de SASS pour ça, plus besoin de scripts de génération.

Si les deux premiers articles ont montré que CSS pouvait structurer et animer sans aide, celui-ci montre qu'il sait aussi théméiser. La boucle est presque bouclée.