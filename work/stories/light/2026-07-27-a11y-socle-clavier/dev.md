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
| T3 — Lien d'évitement vers le contenu principal (`components/_skip-link.scss`, import, `base.html.twig`) | Terminé | 2026-07-28 |
| T4 — Rétablir le focus après les transitions Swup (`@swup/a11y-plugin`, `swup_plugins_controller.js`) | Terminé | 2026-07-28 |
| T5 — Neutraliser le défilement animé sous `prefers-reduced-motion` (`animateScroll` conditionnel) | Terminé | 2026-07-28 |
| Q1 — Vérifications automatiques (`make lint.eslint`, `make lint.twig`, `make test`) | Terminé | 2026-07-28 |
| Q2a — Validation automatisée des points objectifs (Chrome DevTools) | Terminé | 2026-07-28 |
| Q2b — Validation manuelle (visuel, souris, Firefox + Safari, VoiceOver) | En attente | |

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

**Notes (T2)** : absence de risque visuel reconfirmée après coup — `grep` sur tout le dépôt : un seul `<main>` subsiste (`base.html.twig:193`), `.article-content__main` est la seule cible SCSS (`_article-content.scss:32,68`), l'élément `main` n'apparaît en SCSS que dans `_normalize.scss:24` (`display: block`, déjà le défaut d'un `div`) et aucun JS ne référence `main`.

### 2026-07-28 : T3 — Lien d'évitement vers le contenu principal

**Statut** : Terminé

**Actions réalisées** :
- Création de `assets/scss/components/_skip-link.scss` : masquage `position: absolute; top: -100px`, révélation `&:focus { top: 8px }`, `z-index: 1002`, fond `$color-primary` / texte `#fff`
- `@import "components/_skip-link";` en tête du bloc « layout components » de `style.scss` (l. 20)
- `templates/base.html.twig` : `<a class="skip-link" href="#main">Aller au contenu principal</a>` inséré juste après l'ouverture de `<body>`, avant `{% block header %}` — donc hors des conteneurs Swup (`['#main', '#nav']`)
- `templates/base.html.twig` : `<main id="main">` → `<main id="main" tabindex="-1">`
- `make lint.twig` OK · build Encore OK

**Fichiers modifiés** :
- `assets/scss/components/_skip-link.scss` (créé)
- `assets/scss/style.scss` (1 import)
- `templates/base.html.twig` (lien + `tabindex` + 2 commentaires Twig)

**Notes** :
- **Écart au plan (mineur)** : révélation à `top: 8px` et non `top: 0`. À `top: 0`, l'`outline-offset: 2px` de `base/_focus` place le bord de l'anneau à −2px, rogné par le viewport. `left: 8px` pour la même raison sur l'axe horizontal.
- Contraste texte : `#fff` sur `$color-primary` (#7f1a55) ≈ 9,6:1 — largement au-dessus de 4,5:1. L'anneau de focus violet reste lisible car il se détache sur le fond blanc de la page, séparé du fond violet du lien par le halo blanc.
- `z-index: 1002` reconfirmé suffisant : maximum du dépôt = 1001 (`snake.scss:8`), puis 1000 (`_nav-mobile.scss:17`).
- `font-family: faktum semibold` sort non quoté du minifieur — comportement identique aux 3 autres occurrences déjà présentes dans le CSS compilé, pas une régression.

### 2026-07-28 : T4 — Rétablir le focus après les transitions Swup

**Statut** : Terminé

**Actions réalisées** :
- `"@swup/a11y-plugin": "^3.0.0"` ajouté aux `dependencies` de `package.json`, avant `@swup/fade-theme`
- `npm install` → **3.0.0** résolu (3 paquets ajoutés : le plugin + `on-demand-live-region` + `focus-options-polyfill`), `package-lock.json` mis à jour
- `swup_plugins_controller.js` : import de `SwupA11yPlugin`, instancié **en premier** dans le `plugins.push()`, avec `contentSelector: '#main'` et les templates d'annonce en français
- `npx eslint assets/js` : OK · build Encore : OK

**Fichiers modifiés** :
- `package.json`, `package-lock.json`
- `assets/js/controllers/swup_plugins_controller.js`

**Notes** :
- API du plugin vérifiée dans `node_modules/@swup/a11y-plugin/dist/index.modern.js` avant d'écrire la config, plutôt que sur la foi de la doc. Confirmé : options `contentSelector` / `headingSelector` / `announcementTemplate` / `urlTemplate` ; hooks `contentReplaced` + `transitionStart` + `transitionEnd` ; `focus({ preventScroll: true })`. Conforme à ce que le plan décrivait (D1).
- `headingSelector` laissé au défaut (`h1, h2, [role=heading]`).
- Ordre de priorité de l'annonce, tel que codé dans le plugin : `urlTemplate` → écrasé par `document.title` s'il existe → écrasé par le premier heading de `#main` s'il en trouve un. En pratique `urlTemplate` ne sert donc quasiment jamais sur ce site (le `<title>` est toujours renseigné) ; il est traduit par cohérence.
- ⚠️ **`make lint.eslint` lance `npm run fix`, pas `npm run lint`** — c'est-à-dire `eslint --fix`, qui corrige silencieusement au lieu d'échouer. Le contrôle réel a donc été fait avec `npx eslint assets/js --ext .js,.json` (aucune correction automatique n'a été appliquée à ce commit). À signaler à l'équipe : la cible du Makefile ne remplit pas son rôle de garde-fou en CI.

### 2026-07-28 : T5 — Neutraliser le défilement animé sous mouvement réduit

**Statut** : Terminé

**Actions réalisées** :
- `swup_plugins_controller.js` : `const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;` en tête de `_onPreConnect`
- `animateScroll: reducedMotion ? false : { betweenPages: true }` sur le `SwupScrollPlugin`
- `npx eslint assets/js` : OK · build Encore : OK

**Fichiers modifiés** :
- `assets/js/controllers/swup_plugins_controller.js`

**Notes** :
- `doScrollingRightAway: true` est laissé inchangé : il pilote *quand* le défilement se produit, pas s'il est animé.
- Limite assumée (déjà actée au plan) : `matchMedia` est lu une seule fois, à la configuration. Un changement de préférence en cours de session impose un rechargement — c'est ce que dit le protocole de test manuel D.

### 2026-07-28 : Q1 — Vérifications automatiques

**Statut** : Terminé

**Résultats** :
- `npx eslint assets/js --ext .js,.json` → **OK** (et non `make lint.eslint`, cf. note T4)
- `make lint.twig` → **OK**, 52 fichiers
- `make test` (= `build.content.without-images`) → **OK**, 607 pages générées

**Contrôles complémentaires sur le HTML généré** (le build statique permet de vérifier le rendu réel plutôt que le seul template) :
- Lien d'évitement et `<main id="main" tabindex="-1">` présents sur toutes les pages de contenu
- Un seul `<main>` par page sur l'intégralité du build. Les 19 fichiers sans `<main>` sont tous des stubs de redirection Symfony (`<meta http-equiv="refresh">`, ex. `/la-tribu` → `/equipe`) — attendu, pas une lacune.
- Page d'article vérifiée : `<main id="main">` unique + `<div class="article-content__main">` (T2 confirmé côté sortie)
- `/nos-services/ia` et `/nos-services/optimiser` : `containers-value="[]"` confirmé dans le HTML. Défaut **préexistant**, hors périmètre de correction, mais c'est ce qui motive l'étape C-5 du test manuel (piège `aria-busy`).

**Notes** :
- 2 erreurs `Unsupported language "tree"` pendant le build : préexistantes, liées à une coloration syntaxique dans un article, sans rapport avec ce lot.
- Les `Deprecated:` PHP au lancement viennent du décalage entre le PHP local et le 8.3 attendu par le projet — préexistants eux aussi.
- `php-cs-fixer` et `phpstan` non lancés : aucun fichier PHP touché par ce lot.

### 2026-07-28 : Q2a — Validation automatisée des points objectifs

**Statut** : Terminé

Passage sur `make serve` (Chrome DevTools) des étapes du protocole qui se vérifient en console, pour ne
laisser au test manuel que le visuel, la souris, les autres navigateurs et VoiceOver.

**A — Lien d'évitement** (sur `/`) :
- A1 ✅ le lien est bien le **premier élément focusable** du document ; au focus, `top: 8px`, entièrement
  dans le viewport, anneau non rogné (il faut 5 px = 2 d'offset + 3 de trait, on en a 8)
- A1 ✅ règle appliquée au focus clavier : `outline: 3px solid rgb(127,26,85)`, `outline-offset: 2px`,
  `box-shadow: rgb(255,255,255) 0 0 0 2px` — `:focus-visible` matche bien
- A2 ✅ après `Entrée`, `document.activeElement` = `<main id="main" tabindex="-1">`
- A3 ✅ le `Tab` suivant va au premier lien **dans `#main`** (`/nos-services/`), pas dans le header,
  et l'anneau s'y applique
- A4 ✅ `elementFromPoint` au centre du logo renvoie le `<path>` du logo, pas le lien d'évitement.
  Hors focus, le lien est à `bottom: -51px` — au-dessus du viewport, il ne peut rien intercepter.

**C — Reprise de focus Swup** : sur `/blog`, un article, `/equipe`, plus `history.back()` /
`history.forward()` — à chaque fois `activeElement === #main`, `aria-busy` absent après transition,
un seul `<main>`, `scrollY: 0`, lien d'évitement toujours présent (il survit bien aux transitions).

**Écart au plan assumé — `headingSelector: 'h1'`**

Le défaut du plugin (`h1, h2, [role=heading]`) prend le **premier** titre trouvé dans `#main`. Mesuré sur
le build : **280 des 544 pages** de contenu n'ont aucun `<h1>` dans `#main`. Sur celles-là l'annonce
partait sur le premier `<h2>` venu — vérifié sur `/blog` : « Navigation vers : Design et développement :
un process souvent oublié (épisode 1) », soit le titre du premier article de la liste au lieu de celui de
la page. Plus de la moitié du site annonçait donc autre chose que la page atteinte.

Restreint à `'h1'`, le plugin retombe sur `document.title` quand il n'en trouve pas. Vérifié après
correction : `/blog` → « Navigation vers : Le blog de l'équipe d'Elao » ; article → son `h1` ; `/equipe`
→ son `h1`. Aucun cas dégradé : sur une page avec `h1` le comportement est inchangé (et même plus sûr,
puisque le défaut aurait pris un `h2` le précédant dans l'ordre du document).

Le plan listait cette dépendance aux `h1` en « risque à surveiller » sans la chiffrer ; la mesure montre
qu'elle est majoritaire, d'où la correction plutôt que la simple mention. Elle annule la note de T4
« `headingSelector` laissé au défaut ».

**C-5 — Piège `aria-busy` sur les deux pages à `containers: []` : reproduit, mais moins grave qu'anticipé**

Caractérisation précise, en instrumentant `window.__probe` pour distinguer transition Swup et
rechargement dur :
- Le défaut ne se manifeste **que si la page est chargée directement** (lien profond, rafraîchissement,
  entrée externe). Swup lit `containers` une seule fois à l'initialisation, sur `<body>` — qui n'est
  jamais remplacé. Arrivé sur `/nos-services/ia` **par Swup** depuis une autre page, `containers` vaut
  encore `['#main','#nav']` et tout fonctionne : vérifié, `probe` survit, focus sur `#main`, pas d'
  `aria-busy` résiduel, y compris en repartant de la page.
- En entrée directe puis clic sur un lien interne : le contexte d'exécution est détruit et
  `performance.navigation.type === 'navigate'` → Swup échoue et **le navigateur retombe sur un
  chargement de page complet**.
- `aria-busy="true"` est donc posé pendant la fenêtre d'échec (~2-3 s) puis **disparaît avec le
  rechargement** — il ne reste pas collé. État final propre : pas d'`aria-busy`, un seul `<main>`, lien
  d'évitement présent, focus sur `<body>` (le comportement natif d'un chargement de page, pas une
  régression par rapport à un site sans Swup).

**Verdict** : pas bloquant, contrairement à ce que le plan envisageait. Défaut préexistant, hors
périmètre de ce lot, mais à ouvrir en ticket — il coûte la transition douce et la gestion du focus sur
ces deux pages en entrée directe.

### 2026-07-28 : Ouverture de la PR

**Statut** : Terminé

- Branche poussée sur `origin`, **PR #690** ouverte : https://github.com/Elao/elao_/pull/690
- Base `master`, assignée à `Le-Polemil`. **Aucune issue a11y n'existe sur le dépôt** → pas de mot-clé
  de fermeture (vérifié via `gh issue list --search`).
- Le commit `[A11y] Plan du chantier RGAA (4 PR)` (`f7c9d64dd`, branche `feat/a11y-rgaa-audit`) est
  inclus dans la PR : la branche de story en descend. Le doc couvre les 4 lots, mais le laisser en
  branche orpheline non poussée aurait privé la PR de son contexte de relecture — il arrive donc avec le
  lot A, et les PR B/C/D y renverront.

**Contrôle du `package-lock.json` avant push** — le diff affiche 1479 lignes modifiées / −1228 net, ce
qui méritait vérification. Comparaison structurelle master ↔ branche : **3 paquets ajoutés**
(`@swup/a11y-plugin`, `on-demand-live-region`, `focus-options-polyfill`), **0 retiré, 0 version
modifiée**, y compris dans la section legacy `dependencies` (1029 → 1032, aucune suppression). Le volume
du diff n'est que du reformatage npm.

**Reste ouvert** : Q2b (validation manuelle visuelle / Firefox / Safari / VoiceOver / mouvement réduit).
La synthèse de story sera écrite une fois ce passage fait.
