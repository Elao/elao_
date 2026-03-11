---
type:               "post"
title:              "Animer et structurer intelligemment : 4 fonctionnalités CSS qui changent la donne"
date:               "2026-03-13"
lastModified:       ~
tableOfContent:     3

description: >
    View Transitions, Scroll-Driven Animations, animation-composition, @property…
    Découvrez les fonctionnalités CSS modernes qui transforment la façon d'animer et de structurer les interactions — sans JavaScript.

thumbnail:          content/images/blog/2026/dev/nouveautes-css-2025/thumbnail.jpg
#banner:            ~ # Uniquement si différent de la miniature (thumbnail)
tags:               [css, frontend, animation, design-system]
authors:            [pmoreau]
#tweetId:           "" # Ajouter l'id du Tweet après publication
---

Dans le [premier article](https://www.elao.com/blog/dev/nouveautes-css-2025), on a couvert les bases modernes du CSS : nesting natif, `@layer`, container queries et `:has()`.
Cette fois, on monte d'un cran. On parle animation, scroll, et custom properties typées — le tout **sans une ligne de JavaScript** (ou presque).

Ces features ne sont plus expérimentales : elles sont là, prêtes à l'emploi, et elles changent radicalement la façon dont on conçoit les interactions en CSS.

---

## View Transitions API : des transitions de page sans prise de tête

Animer une transition entre deux pages ou deux états d'interface, ça a longtemps rimé avec JavaScript obligatoire — une lib comme Framer Motion, GSAP, ou quelques dizaines de lignes de glue code pour gérer les entrées/sorties.

La **View Transitions API** change ça. Elle permet de créer des transitions animées fluides entre différents états ou pages — que ce soit dans une application monopage ou un site classique multi-pages — nativement, avec quelques lignes de CSS.

Le JS reste nécessaire en SPA, mais uniquement pour signaler au navigateur qu'un changement de DOM est sur le point de se produire — c'est lui qui se charge ensuite de capturer les états avant/après et de calculer la transition. Pour un site multi-pages en revanche, même ce minimum de JS disparaît : une ligne de CSS suffit à tout activer.

### Exemple en SPA

Imaginons une galerie : au clic sur une miniature, on affiche l'image en grand. On enveloppe le changement de DOM dans `startViewTransition()`, et on associe un `view-transition-name` aux deux éléments concernés — la miniature et l'image principale.

```html
<img class="thumbnail" src="photo.jpg" />
<img class="hero" src="photo.jpg" />
```

```css
.thumbnail {
  view-transition-name: selected-image;
}

.hero {
  view-transition-name: selected-image;
}
```

```ts
thumbnail.addEventListener('click', () => {
  document.startViewTransition(() => {
    hero.src = thumbnail.src;
  });
});
```

Le navigateur sait que `.thumbnail` et `.hero` partagent le même `view-transition-name`. Il capture l'état avant (la miniature en bas), applique le changement de DOM, puis anime automatiquement la transition entre les deux positions — un effet "zoom" natif, sans une seule lib.

Pour une transition plus globale — par exemple faire glisser toute la page sur le côté lors d'un changement de vue — on cible le pseudo-élément `root`, qui représente l'ensemble de la page :

```css
::view-transition-old(root) {
  animation: slide-out 0.3s ease-in forwards;
}

::view-transition-new(root) {
  animation: slide-in 0.3s ease-out forwards;
}

@keyframes slide-out {
  to { transform: translateX(-100%); }
}

@keyframes slide-in {
  from { transform: translateX(100%); }
}
```

Les deux approches sont complémentaires : `root` pour les transitions globales de vue, `view-transition-name` pour animer des éléments spécifiques entre deux états.

### Exemple en MPA

Pour les sites multi-pages (Astro, PHP, bon vieux HTML…), il suffit d'activer l'opt-in CSS :

```css
@view-transition {
  navigation: auto;
}
```

Et pour cibler des éléments spécifiques entre les pages :

```css
.hero-image {
  view-transition-name: hero;
}
```

Le navigateur fait le lien entre les éléments portant le même `view-transition-name` d'une page à l'autre, et anime la transition automatiquement. On obtient des effets façon "shared element transition" sans aucune lib.

### Support navigateurs

- **Chrome** : ✅ 111+ (Level 1), 126+ (Level 2)
- **Edge** : ✅ 111+ (Level 1), 126+ (Level 2)
- **Firefox** : ✅ 144+ (Level 1 uniquement)
- **Safari** : ✅ 18+ (Level 1), 18.2+ (Level 2)

!!! Note "Pour aller plus loin"
    Consultez la [documentation MDN sur les View Transitions](https://developer.mozilla.org/en-US/docs/Web/API/View_Transition_API).

---

## Scroll-Driven Animations : le parallaxe sans JavaScript

Historiquement, pour synchroniser une animation au scroll, il fallait un `addEventListener('scroll', ...)`, un `IntersectionObserver`, voire une lib type GSAP ou ScrollMagic. Bref, du JavaScript, du calcul, de la perf à surveiller.

Les **Scroll-Driven Animations** permettent de lier une animation CSS directement à la position du scroll. Plus besoin de JS : le navigateur gère tout, et c'est optimisé nativement (compositing thread).

### Parallaxe au scroll de la page

```css
.parallax-bg {
  animation: parallax linear;
  animation-timeline: scroll();
}

@keyframes parallax {
  from { transform: translateY(0); }
  to { transform: translateY(-200px); }
}
```

Ici, l'élément `.parallax-bg` se déplace progressivement au fur et à mesure que l'utilisateur scrolle. L'animation est liée à la progression du scroll (0 % en haut, 100 % en bas), pas au temps.

### Révélation au scroll (entrée dans le viewport)

```css
.reveal {
  animation: fade-in-up linear both;
  animation-timeline: view();
  animation-range: entry 0% entry 100%;
}

@keyframes fade-in-up {
  from {
    opacity: 0;
    transform: translateY(40px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
```

Avec `animation-timeline: view()`, l'animation se déclenche quand l'élément entre dans le viewport. `animation-range: entry 0% entry 100%` indique que l'animation se joue pendant toute la phase d'entrée de l'élément dans la zone visible.

Résultat : un effet de "reveal" fluide, sans une seule ligne de JS, et performant par défaut.

### Les deux types de timelines

- **`scroll()`** : progression liée au scroll global (ou d'un conteneur). Parfait pour le parallaxe, les barres de progression de lecture.
- **`view()`** : progression liée à la visibilité d'un élément dans le viewport. Parfait pour les effets d'apparition et de révélation.

### Support navigateurs

- **Chrome** : ✅ 115+
- **Edge** : ✅ 115+
- **Firefox** : ⚙️ 110+ derrière flag (`layout.css.scroll-driven-animations.enabled`)
- **Safari** : ✅ 26+

!!! Note "Pour aller plus loin"
    Consultez la [documentation MDN sur animation-timeline](https://developer.mozilla.org/en-US/docs/Web/CSS/animation-timeline).

---

## Animation Composition : combiner sans écraser

Problème classique : tu as un composant avec un `transform: translateX(10px)` en état par défaut, et tu veux lui ajouter une animation qui déplace encore plus l'élément. Sauf qu'en CSS, la valeur de l'animation remplace celle de la propriété de base. Ton `translateX` initial disparaît, remplacé par celui de l'animation.

**`animation-composition`** résout ça. Elle définit comment la valeur d'une animation se combine avec la valeur de base de la propriété, au lieu de simplement l'écraser.

```css
.badge {
  transform: translateX(10px);
  animation: slide 1s infinite alternate;
  animation-composition: accumulate;
}

@keyframes slide {
  to {
    transform: translateX(20px);
  }
}
/* Résultat : translateX(30px) — combinaison arithmétique */
```

Avec `animation-composition: accumulate`, les valeurs de même type se combinent arithmétiquement : `translateX(10px)` + `translateX(20px)` = `translateX(30px)`. Sans cette propriété, l'animation remplacerait la valeur de base et l'élément n'irait qu'à `translateX(20px)`.

### Les trois modes

- **`replace`** (défaut) : la valeur de l'animation remplace celle de la propriété. Comportement classique.
- **`add`** : la valeur de l'animation est ajoutée par-dessus la valeur existante (au niveau de la liste de transforms). C'est le mode à utiliser quand les fonctions sont de types différents — par exemple ajouter un `scale(1.1)` en plus d'un `translateX` ou d'un `rotate` déjà défini sur l'élément.
- **`accumulate`** : les valeurs sont combinées arithmétiquement quand les fonctions sont du même type (ex. `translateX(10px)` + `translateX(20px)` = `translateX(30px)`).

Très utile dès qu'on travaille avec des composants réutilisables qui ont des transforms de base et qu'on veut animer sans tout casser.

### Support navigateurs

- **Chrome** : ✅ 112+
- **Edge** : ✅ 112+
- **Firefox** : ✅ 115+
- **Safari** : ✅ 16+

!!! Note "Pour aller plus loin"
    Consultez la [documentation MDN sur animation-composition](https://developer.mozilla.org/en-US/docs/Web/CSS/animation-composition).

---

## @property : des variables CSS typées (et animables)

Les custom properties CSS (`--ma-variable`), on les utilise tous les jours. Mais elles ont une grosse limitation : le navigateur les traite comme de simples chaînes de caractères. Résultat, impossible de les animer, et pas de garde-fou si on leur assigne une valeur incohérente.

**`@property`** permet d'enregistrer une custom property avec un **type**, une **valeur initiale** et un contrôle sur l'**héritage**. Et le game changer : une fois typée, la propriété devient **animable**.

### Animer un dégradé

Animer un `background: linear-gradient(...)` en CSS, c'est normalement impossible. Avec `@property`, ça devient trivial :

```css
@property --gradient-angle {
  syntax: "<angle>";
  initial-value: 0deg;
  inherits: false;
}

.gradient-box {
  --gradient-angle: 0deg;
  background: linear-gradient(var(--gradient-angle), #3178c6, #e535ab);
  transition: --gradient-angle 0.6s ease;
}

.gradient-box:hover {
  --gradient-angle: 180deg;
}
```

Au hover, le dégradé pivote de 0° à 180° avec une transition fluide. Sans `@property`, le changement serait instantané (pas d'interpolation possible sur une chaîne de caractères).

### Animer une couleur avec un type

```css
@property --accent-color {
  syntax: "<color>";
  initial-value: #3178c6;
  inherits: true;
}

.card {
  background: var(--accent-color);
  transition: --accent-color 0.3s ease;
}

.card:hover {
  --accent-color: #e535ab;
}
```

La transition entre les deux couleurs est interpolée par le navigateur, comme n'importe quelle propriété CSS native. Impossible sans le typage via `@property`.

### Ce que ça change concrètement

- **Animer l'inanimable** : dégradés, ombres complexes, tout ce qui repose sur des custom properties.
- **Valeurs par défaut robustes** : `initial-value` garantit un fallback propre.
- **Héritage contrôlé** : `inherits: false` empêche la propagation non désirée dans l'arbre DOM.
- **Sécurité du typage** : le navigateur ignore les valeurs qui ne correspondent pas au `syntax` déclaré.

### Support navigateurs

- **Chrome** : ✅ 85+
- **Edge** : ✅ 85+
- **Firefox** : ✅ 128+
- **Safari** : ✅ 16.4+

!!! Note "Pour aller plus loin"
    Consultez la [documentation MDN sur @property](https://developer.mozilla.org/en-US/docs/Web/CSS/@property).

---

## Conclusion

Si le premier article montrait que CSS pouvait désormais gérer la structure et la logique de sélection sans JS, celui-ci montre qu'il peut aussi prendre en charge l'animation et l'interactivité. La tendance est claire : CSS devient de plus en plus autonome. Et franchement, c'est pas pour déplaire.
