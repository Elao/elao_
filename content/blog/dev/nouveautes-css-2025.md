---
type:               "post"
title:              "Les 4 nouveautés CSS qui changent tout (et comment les utiliser)"
date:               "2026-01-19"
lastModified:       ~
tableOfContent:     3

description: >
    CSS Nesting, @layer, Container Queries, :has()…
    Découvrez les fonctionnalités CSS modernes prêtes pour la production qui transforment notre façon de concevoir les styles.

thumbnail:          content/images/blog/2026/dev/nouveautes-css-2025/thumbnail.jpg
#banner:            ~ # Uniquement si différent de la miniature (thumbnail)
tags:               [css, frontend, responsive, design-system]
authors:            [pmoreau]
#tweetId:           "" # Ajouter l'id du Tweet après publication
---

Moderniser son code CSS sans recourir à des librairies tierces ou des hacks complexes, c'est désormais possible. Voici quatre fonctionnalités qui, à mon sens, font passer CSS dans une nouvelle dimension. Il ne s'agit pas de gadgets expérimentaux, mais de véritables changements de paradigme dans la façon de concevoir et d'organiser nos styles — testés, approuvés, et prêts à être intégrés dans n'importe quel projet front.

## CSS Nesting : l'imbrication native

C'est la fin des classes à rallonge et des sélecteurs spaghetti.
On imbrique désormais ses règles comme avec Sass ou d'autres pré-processeurs, sauf que cette syntaxe est maintenant **supportée nativement par CSS**.

```css
.article {
  padding: 1rem;

  header {
    background: #f2f2f2;

    h1 {
      color: #3178c6;
      margin: 0;
    }
  }

  a {
    text-decoration: none;

    &:hover {
      text-decoration: underline;
    }
  }
}
```

Les bénéfices sont immédiats :

- Un code plus compact et lisible
- Une structure plus intuitive
- Un esprit "component-driven", même en Vanilla CSS

### Support navigateurs

- **Chrome** : ✅ 112+
- **Edge** : ✅ 112+
- **Firefox** : ✅ 117+
- **Safari** : ✅ 16.5+

!!! Note "Pour aller plus loin"
    Consultez la [documentation MDN sur CSS Nesting](https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_nesting) pour découvrir tous les cas d'usage.

---

## @layer : la fin du "specificity hell"

La gestion de la priorité en CSS, c'est souvent un casse-tête. On finit par abuser des `!important` pour forcer des styles, ce qui rend le code rapidement ingérable dès que le projet grandit.

Quand on travaille sur un design system — entre les librairies UI, les overrides et le CSS custom — les conflits de priorité deviennent vite problématiques.

`@layer` permet d'organiser le CSS en couches, et de définir explicitement qui l'emporte en cas de conflit. C'est beaucoup plus propre que de multiplier les `!important`.

```css
@layer base, components, overrides;

@layer components {
  .btn {
    padding: 8px 24px;
    background: salmon;
  }
}

@layer overrides {
  .btn {
    background: limegreen; /* Priorité sur components */
  }
}
```

Grâce à `@layer`, la structure du CSS devient explicite :

- Le code des librairies ne casse plus les styles custom
- Les overrides sont maîtrisés et prévisibles
- Plus besoin de bidouiller la spécificité

### Support navigateurs

- **Chrome** : ✅ 99+
- **Edge** : ✅ 99+
- **Firefox** : ✅ 97+
- **Safari** : ✅ 15.4+

!!! Note "Pour aller plus loin"
    Consultez la [documentation MDN sur @layer](https://developer.mozilla.org/en-US/docs/Web/CSS/@layer).

---

## Container Queries : le responsive au niveau composant

Les Container Queries représentent l'évolution naturelle des media queries. Plutôt que de réagir uniquement à la taille du viewport, les composants s'adaptent désormais à la taille de leur conteneur parent.

Cela permet un responsive beaucoup plus fin, au niveau du composant lui-même, et ouvre la voie à des design systems véritablement modulaires.

```html
<div class="card">
  <h2>Card title</h2>
  <p>Card content</p>
</div>
```

```css
.card {
  container-type: inline-size;

  h2 {
    font-size: 1em;
  }

  @container (min-width: 600px) {
    h2 {
      font-size: 2em;
    }
  }
}
```

Ici, `.card h2` sera agrandi uniquement si la `.card` dispose d'un parent suffisamment large — peu importe la taille du viewport.

!!! Success "Réutilisabilité maximale"
    Chaque composant gère son propre responsive de manière autonome, ce qui facilite grandement la composition et la réutilisation.

### Support navigateurs

- **Chrome** : ✅ 105+
- **Edge** : ✅ 105+
- **Firefox** : ✅ 110+
- **Safari** : ✅ 16.0+

!!! Note "Pour aller plus loin"
    Consultez la [documentation MDN sur les Container Queries](https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_containment/Container_queries).

---

## Le sélecteur :has() : styliser un parent selon ses enfants

Avant `:has()`, styliser un élément parent en fonction de ses enfants nécessitait du JavaScript ou des contournements complexes. Désormais, c'est faisable en une seule ligne de CSS.

```css
/* Met en évidence un div s'il contient une image */
div:has(img) {
  border: 2px solid limegreen;
}
```

Un autre cas d'usage courant concerne les formulaires :

```css
.input-group:has(input:focus) label {
  color: #3178c6;
}
```

Ultra pratique pour du feedback UI, la gestion des états "checked", ou toute logique conditionnelle basée sur le contenu.

### Support navigateurs

- **Chrome** : ✅ 105+
- **Edge** : ✅ 105+
- **Firefox** : ✅ 121+
- **Safari** : ✅ 15.4+

!!! Note "Pour aller plus loin"
    Consultez la [documentation MDN sur :has()](https://developer.mozilla.org/en-US/docs/Web/CSS/:has).

---

## Conclusion

Ces fonctionnalités ne sont plus expérimentales : elles sont prêtes pour la production sur tous les navigateurs majeurs. Testées et validées dans nos projets React, elles transforment véritablement la façon d'écrire et d'organiser le CSS.

Le CSS n'a pas fini de se renouveller !
