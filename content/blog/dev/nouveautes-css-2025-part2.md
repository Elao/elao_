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

Tu connais cette sensation quand tu navigues sur une SPA et que les pages "popent" sans aucune transition ? Ou quand tu passes 3 jours à câbler Framer Motion pour un simple fondu entre deux vues ?

La **View Transitions API** règle ça nativement. Elle permet de créer des transitions animées fluides entre différents états ou pages, que ce soit dans une SPA ou une MPA (Multi-Page Application).

### Exemple en SPA

```tsx
function navigateTo(newContent: string) {
  document.startViewTransition(() => {
    document.querySelector('#content')!.innerHTML = newContent;
  });
}
```

Le navigateur capture un "snapshot" de l'état avant, applique ta modification, puis anime la transition entre les deux. Par défaut, tu obtiens un fondu enchaîné. Mais tu peux personnaliser l'animation en CSS :

```css
::view-transition-old(root) {
  animation: fade-out 0.3s ease-out;
}

::view-transition-new(root) {
  animation: fade-in 0.3s ease-in;
}

@keyframes fade-out {
  from { opacity: 1; }
  to { opacity: 0; }
}

@keyframes fade-in {
  from { opacity: 0; }
  to { opacity: 1; }
}
```

### Exemple en MPA (Level 2)

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

Problème classique : tu as un composant avec un `transform: translateX(10px)` en état par défaut, et tu veux lui ajouter une animation qui fait un `scale(1.1)` au hover. Sauf qu'en CSS, la dernière valeur de `transform` écrase la précédente. Ton `translateX` disparaît.

**`animation-composition`** résout ça. Elle définit comment plusieurs animations se combinent sur une même propriété, au lieu de se remplacer.

```css
.badge {
  transform: translateX(10px);
  animation: pulse 1s infinite alternate;
  animation-composition: accumulate;
}

@keyframes pulse {
  to {
    transform: scale(1.1);
  }
}
```

Avec `animation-composition: accumulate`, les deux transforms se cumulent : l'élément garde son `translateX(10px)` **et** pulse en scale. Sans cette propriété, le `translateX` serait perdu pendant l'animation.

### Les trois modes

- **`replace`** (défaut) : la valeur de l'animation remplace celle de la propriété. Comportement classique.
- **`add`** : la valeur de l'animation est ajoutée par-dessus la valeur existante (au niveau de la liste de transforms).
- **`accumulate`** : les valeurs sont combinées arithmétiquement (ex. `translateX(10px)` + `translateX(20px)` = `translateX(30px)`).

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

Avec les View Transitions, les Scroll-Driven Animations, `animation-composition` et `@property`, CSS ne se contente plus de "mettre en forme" : il **anime, compose et structure** les interactions de manière native.

Le plus frappant, c'est à quel point ces features réduisent la dépendance au JavaScript pour des cas d'usage qui, jusqu'ici, nécessitaient systématiquement des libs tierces. Moins de JS, moins de bundle, de meilleures perfs, et un code plus maintenable.

Ces fonctionnalités sont supportées (ou en passe de l'être) sur tous les navigateurs majeurs. Le CSS n'a pas fini de se renouveler !
