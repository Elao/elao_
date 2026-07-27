---
story: "Socle de navigation clavier : focus visible, lien d'évitement, reprise de focus Swup"
story_code: "a11y-socle-clavier"
created: 2026-07-27
status: "In Progress"
---

# Journal de développement

## Progression

| Tâche | Statut | Date |
|-------|--------|------|
| T0 — Préparer l'environnement (`make install`, commit du doc d'audit sur la branche parente, création de la sous-branche) | Terminé | 2026-07-27 |
| T1 — Indicateur de prise de focus visible (`base/_focus.scss`, import, suppression des 10 resets) | Terminé | 2026-07-28 |
| T2 — Corriger le `<main>` imbriqué des articles (`templates/blog/article.html.twig`) | Terminé | 2026-07-28 |
| T3 — Lien d'évitement vers le contenu principal (`components/_skip-link.scss`, import, `base.html.twig`) | En attente | |
| T4 — Rétablir le focus après les transitions Swup (`@swup/a11y-plugin`, `swup_plugins_controller.js`) | En attente | |
| T5 — Neutraliser le défilement animé sous `prefers-reduced-motion` (`animateScroll` conditionnel) | En attente | |
| Q1 — Vérifications automatiques (`make lint.eslint`, `make lint.twig`, `make test`) | En attente | |
| Q2 — Validation manuelle (4 protocoles A/B/C/D, 7 pages, Chrome + Firefox + Safari, VoiceOver) | En attente | |

## Journal

### 2026-07-27 : T0 — Préparer l'environnement

**Statut** : Terminé

**Actions réalisées** :
- `make install` dans le worktree `/Users/polemil/github/elao_a11y` (composer + npm) — `node_modules/` et `vendor/` présents
- Commit de `work/a11y-rgaa-audit.md` sur la branche parente `feat/a11y-rgaa-audit` (`f7c9d64dd` — `[A11y] Plan du chantier RGAA (4 PR)`)
- Création de la sous-branche `story-light/a11y-socle-clavier`
- Commit du scaffold de story (`bdf00834c` — `docs(a11y-socle-clavier): initialize story light`)

**Fichiers modifiés** :
- `work/a11y-rgaa-audit.md` (branche parente)
- `work/stories/light/2026-07-27-a11y-socle-clavier/{plan,context,dev}.md`

**Notes** : le doc d'audit couvre les 4 PR du chantier, il est donc volontairement porté par la branche parente et non par la branche de story.

### 2026-07-28 : T1 — Indicateur de prise de focus visible

**Statut** : Terminé

**Actions réalisées** :
- Création de `assets/scss/base/_focus.scss` : règle `:focus-visible` en double anneau (`outline: 3px solid $color-primary` + `outline-offset: 2px` + `box-shadow: 0 0 0 2px #fff`), sans `border-radius`
- Ajout du garde-fou `@media (prefers-reduced-motion: reduce) { html { scroll-behavior: auto } }` dans le même fichier, avec commentaire explicitant que P1-7 (animations AOS) n'est pas traité
- `@import "base/_focus";` ajouté après `base/_utilities` (l. 18) — donc après `base/_variables` (`$color-primary`) et après `base/_layout` (`scroll-behavior: smooth` sur `html`, que le média query doit battre)
- Suppression des 10 resets d'anneau de focus dans 5 fichiers
- Build de contrôle `npx encore production` : compilation OK

**Fichiers modifiés** :
- `assets/scss/base/_focus.scss` (créé)
- `assets/scss/style.scss` (1 import)
- `assets/scss/components/_form-group.scss` (`outline: none` × 2, l. 39 et 77)
- `assets/scss/components/_kudo.scss` (`box-shadow: none` + `outline: none` sur `&:hover,&:active,&:focus` ; idem sur `.kudo--active`)
- `assets/scss/components/_gallery.scss` (`outline: none` sur `.gallery__item > button`)
- `assets/scss/components/_social-post.scss` (`outline: 0` + `box-shadow: none` sur `select`)
- `assets/scss/pages/_page-signature.scss` (bloc `&:focus` du `textarea` devenu vide → supprimé entièrement)

**Notes** :
- Les « 10 resets » du plan = 7 `outline` + 3 `box-shadow: none` compagnons (`_kudo.scss:12,39`, `_social-post.scss:17`). Le `box-shadow: none` supprime le halo blanc, il devait partir avec l'`outline`.
- `.kudo--active` n'est pas un état de focus mais une classe statique posée par JS après clic. Elle a quand même été nettoyée : à spécificité égale (0,1,0) avec `:focus-visible`, `components/_kudo` étant importé après `base/_focus`, elle aurait gagné et masqué l'anneau sur un kudo déjà cliqué.
- Deux blocs `&:focus` deviennent vides après suppression : celui de `.page-signature__tutorial textarea` a été supprimé entièrement ; ceux de `_form-group` et `_kudo` conservent leurs règles imbriquées.
- Vérifications sur le CSS compilé : `.…:focus-visible{box-shadow:0 0 0 2px #fff;outline:3px solid #7f1a55;outline-offset:2px}` présent, média query présente, **0** occurrence de `outline:none`/`outline:0` survivante.

### 2026-07-28 : T2 — Corriger le `<main>` imbriqué des articles

**Statut** : Terminé

**Actions réalisées** :
- `templates/blog/article.html.twig` : `<main class="article-content__main">` (l. 151) → `<div …>`, `</main>` (l. 194) → `</div>`
- `make lint.twig` : OK (52 fichiers)

**Fichiers modifiés** :
- `templates/blog/article.html.twig`

**Notes** : absence de risque visuel reconfirmée après coup — `grep` sur tout le dépôt : un seul `<main>` subsiste (`base.html.twig:193`), `.article-content__main` est la seule cible SCSS (`_article-content.scss:32,68`), l'élément `main` n'apparaît en SCSS que dans `_normalize.scss:24` (`display: block`, déjà le défaut d'un `div`) et aucun JS ne référence `main`.
