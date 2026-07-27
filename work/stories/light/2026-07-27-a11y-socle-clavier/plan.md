---
story: "Socle de navigation clavier : focus visible, lien d'évitement, reprise de focus Swup"
story_code: "a11y-socle-clavier"
created: 2026-07-27
status: "In Progress"
---

# PR A — Socle de navigation clavier (a11y-socle-clavier)

## Contexte

Une check-list RGAA interne de 141 lignes (89 critères, 14 NC) a été produite par l'équipe puis
recroisée avec le code. Trois défauts rendent aujourd'hui le site **inutilisable au clavier** :

1. **Aucune règle `:focus-visible` dans tout `assets/scss/`** (vérifié : 0 occurrence). `outline` n'y
   sert qu'à *supprimer* l'anneau natif — 7 occurrences — et les ~51 règles `&:focus` ne changent
   qu'une couleur. Un utilisateur au clavier ne sait jamais où il se trouve.
2. **Aucun lien d'évitement.** Piège à connaître : la règle axe-core `bypass` est *satisfaite* par la
   seule présence de `<main id="main">`, donc axe, Lighthouse et pa11y renvoient tous « vert » ici.
   Le défaut n'était détectable qu'à l'œil.
3. **Aucune reprise de focus après navigation Swup.** Swup remplace `#main` via `outerHTML` : le focus
   retombe sur `<body>` et aucun lecteur d'écran n'est informé du changement de page. Ces deux lignes
   étaient cochées « conforme » dans l'audit — c'est une correction de verdict.

Ce lot est le **premier de quatre** et conditionne les suivants : un lien d'évitement sans anneau
visible ne sert à rien. Résultat attendu : le site devient parcourable au clavier de bout en bout, avec
un indicateur de focus visible sur tous les fonds de la charte.

Décisions techniques, ratios recalculés et arbitrages : **`work/a11y-rgaa-audit.md`** (§ 2 D1→D6).
Ce plan ne les répète pas, il les exécute.

## Périmètre

**Dedans** — P1-1 focus visible · P1-2 lien d'évitement (+ le `<main>` imbriqué des articles) ·
P1-6 reprise de focus Swup · **la seule tranche de P1-7 que ce PR provoque** : le défilement.

**Dehors** — AOS reste actif volontairement. `[data-aos]` part à `opacity: 0` et compte sur l'animation
pour se révéler : neutraliser les animations sans désactiver AOS en JS rendrait des dizaines de blocs
invisibles, **précisément pour le public visé**. L'équipe connaît déjà ce piège, c'est la raison d'être
du garde-fou `html.no-js [data-aos]` (`assets/scss/style.scss:6-10`). Le reste (contrastes, noms
accessibles, plan du site) est en PR B, C, D — cf. `work/a11y-rgaa-audit.md` § 9.

## Préalables

Le worktree `/Users/polemil/github/elao_a11y` **n'a ni `node_modules/` ni `vendor/`** (les worktrees ne
les partagent pas). Rien ne tourne avant `make install`.

`work/a11y-rgaa-audit.md` est encore non suivi. Il couvre **les 4 PR**, donc il est commité sur la
branche parente `feat/a11y-rgaa-audit`, pas sur la branche de story.

## Tâches

### T0 — Préparer l'environnement
- `make install` dans le worktree (composer + npm).
- Commiter `work/a11y-rgaa-audit.md` sur `feat/a11y-rgaa-audit` : `[A11y] Plan du chantier RGAA (4 PR)`.
- Créer la sous-branche : `git checkout -b story-light/a11y-socle-clavier`.

### T1 — Indicateur de prise de focus visible
*WCAG 2.4.7 + 1.4.11 / RGAA 10.7*

- **Créer `assets/scss/base/_focus.scss`** : `outline: 3px solid $color-primary` +
  `outline-offset: 2px` + `box-shadow: 0 0 0 2px #fff`.
  Le **double anneau** est le cœur de la décision (D2) : 18 composants ont un fond sombre et sur
  `$color-tertiary` **aucune couleur unique n'atteint 3:1**. L'un des deux anneaux atteint toujours ≥ 3:1
  sur tous les tokens (tableau des 8 ratios dans le doc d'audit). Aucune surcharge par contexte.
  **Pas de `border-radius`** : la propriété s'applique à l'élément, pas à l'anneau, et arrondirait les
  fonds carrés à la prise de focus.
- Y inclure `@media (prefers-reduced-motion: reduce) { html { scroll-behavior: auto } }` — contrepartie
  du défilement que T3 introduit, avec un commentaire disant explicitement que P1-7 n'est pas fait.
- **`assets/scss/style.scss`** : ajouter `@import "base/_focus";` après la ligne 17
  (`base/_utilities`) — après `base/_variables` (l. 13) pour `$color-primary`.
- **Supprimer les 10 resets** (liste et sélecteurs vérifiés) :
  `components/_form-group.scss:39,77` · `components/_kudo.scss:12,13,38,39` ·
  `components/_gallery.scss:48` · `components/_social-post.scss:16,17` ·
  `pages/_page-signature.scss:31`.
  C'est la **seule** façon de faire gagner la règle globale : tous ces sélecteurs la battent en
  spécificité (0,2,0 vs 0,1,0), un changement d'ordre d'import n'y suffirait pas. **Aucun `!important`,
  aucun `:focus:not(:focus-visible)`** — aucun des 7 n'était un choix design « pas d'anneau à la
  souris » (D4 détaille les 5 cas, dont 2 morts).

### T2 — Corriger le `<main>` imbriqué des articles
*WCAG 1.3.1 / RGAA 12.6* — **avant T3**, le lien d'évitement doit viser un `#main` non ambigu.

- `templates/blog/article.html.twig` : l. **151** `<main class="article-content__main">` → `<div …>`,
  l. **194** `</main>` → `</div>`.
- Sans risque visuel : la classe est la seule cible SCSS (`_article-content.scss:32,68`), `main`
  n'apparaît en SCSS que dans `_normalize.scss:24` (`display: block`, déjà le défaut d'un `div`), et
  aucun JS ne référence `main`.

### T3 — Lien d'évitement vers le contenu principal
*WCAG 2.4.1 / RGAA 12.7*

- **Créer `assets/scss/components/_skip-link.scss`** + `@import "components/_skip-link";` en tête du
  bloc « layout components » de `style.scss` (l. 19), car c'est le premier élément du DOM.
- **Ne pas réutiliser `.screen-reader`** (`base/_utilities.scss:1-8`) : `left: -10000px` **sans règle de
  révélation au focus** (vérifié : 0 `:focus` dans le fichier) → le lien resterait invisible pour
  l'utilisateur clavier voyant, échec 2.4.7.
- Masquage par `top: -100px` en `position: absolute`, révélation par `&:focus { top: 0 }`. Motif : une
  boîte « visuellement masquée » conservée au coin haut-gauche intercepterait les clics sur le logo du
  header ; et en `absolute` le lien n'est pas un flex item de `body`
  (`base/_layout.scss:5-16` : `display: flex; flex-direction: column`).
- `z-index: 1002` — au-dessus du max réel du dépôt : 1001 (`snake.scss:8`) puis 1000
  (`_nav-mobile.scss:17`).
- **`templates/base.html.twig`** : insérer `<a class="skip-link" href="#main">Aller au contenu
  principal</a>` après la l. **110** (`}}>`), avant `{% block header %}` (l. 111) — donc **hors** des
  conteneurs Swup (`containers: ['#main', '#nav']`, l. 93), pour qu'il survive aux transitions.
- **`<main id="main" tabindex="-1">`** (l. 193), **en dur dans le template** : sans le `tabindex`,
  Safari et Firefox ne déplacent pas le focus sur une cible de fragment non focusable ; et posé en JS il
  serait perdu à chaque remplacement de conteneur par Swup.

### T4 — Rétablir le focus après les transitions Swup
*WCAG 2.4.3 + 4.1.3 / RGAA 12.8*

- **`@swup/a11y-plugin@^3.0.0`** dans `dependencies` de `package.json`, avant `@swup/fade-theme`
  (l. 29) — cohérent avec `@swup/scroll-plugin` (l. 32) et `@swup/progress-plugin` (l. 31) qui y sont
  déjà. Puis `npm install` (`package-lock.json` **est** suivi par git, à commiter).
- **Version imposée, pas un choix esthétique** : swup installé = **3.1.1** (`^3.0` en devDependencies).
  L'API `hooks` de swup 4 (`swup.hooks.on('page:view')`) **n'existe pas ici**. Seule la v3 du plugin
  déclare `peerDependencies: { swup: '^3.0.0' }`, et son `@swup/plugin@2.0.3` est **déjà dans l'arbre**.
- **Plugin plutôt qu'un hook maison** (D1) : il hooke `contentReplaced` et non `pageView` — ce dernier
  est aussi déclenché par `enable()`, un hook maison aurait **volé le focus au chargement de chaque
  page**. Il fait `focus({ preventScroll: true })`, ce qui évite le double saut avec
  `SwupScrollPlugin` (`animateScroll.betweenPages: true`). Et sa région live est créée à la demande,
  ce qui évite le piège du `aria-live` inséré en même temps que le message.
- **`assets/js/controllers/swup_plugins_controller.js`** : importer `SwupA11yPlugin`, l'ajouter **en
  premier** dans le `plugins.push()`, avec `contentSelector: '#main'` et les templates d'annonce en
  français (`announcementTemplate`, `urlTemplate`).

### T5 — Neutraliser le défilement animé sous mouvement réduit
*WCAG 2.3.3 / RGAA 13.8* — **même fichier que T4**, donc après.

- Dans `_onPreConnect`, lire `window.matchMedia('(prefers-reduced-motion: reduce)').matches` et passer
  `animateScroll: reducedMotion ? false : { betweenPages: true }` au `SwupScrollPlugin`.
- **Pourquoi ici et pas dans un lot « motion »** : cette ligne partage le `plugins.push()` avec T4. Les
  séparer garantit un conflit de merge. C'est aussi la contrepartie directe du scroll inter-pages que
  T4 met en scène.
- Limite assumée : `matchMedia` est lu une fois, à la configuration. Un changement de préférence en
  cours de session n'est pas répercuté côté Swup (la partie CSS de T1, elle, réagit). Ne pas
  complexifier pour ce cas.

## Fichiers touchés

**Créés** — `assets/scss/base/_focus.scss` · `assets/scss/components/_skip-link.scss`
**Modifiés** — `assets/scss/style.scss` (2 imports) · `templates/base.html.twig` (lien + `tabindex`) ·
`templates/blog/article.html.twig` (2 lignes) · `assets/js/controllers/swup_plugins_controller.js` ·
`package.json` + `package-lock.json`
**Nettoyés** — `components/_form-group.scss` · `components/_kudo.scss` · `components/_gallery.scss` ·
`components/_social-post.scss` · `pages/_page-signature.scss`

## Découpage en commits

Style du dépôt sur `master` : messages français à préfixe entre crochets. Préfixe retenu `[A11y]`.

1. `[A11y] Indicateur de prise de focus visible sur tout le site` (T1)
2. `[A11y] Corrige le <main> imbriqué des articles de blog` (T2)
3. `[A11y] Ajoute un lien d'évitement vers le contenu principal` (T3)
4. `[A11y] Rétablit le focus après les transitions Swup` (T4)
5. `[A11y] Neutralise le défilement animé sous prefers-reduced-motion` (T5)

Ordre imposé : 1 avant 3, 2 avant 3, 4 avant 5.

## Vérifications automatiques

`make lint.eslint` · `make lint.twig` · `make test` (= `build.content.without-images`, valide que le
build statique passe — il attrapera une erreur de syntaxe Twig, rien de plus).

**Aucune couverture automatisée d'accessibilité n'existe** : `tests/` ne contient que `bootstrap.php`.
D'où le plan manuel ci-dessous, qui est la vraie validation de ce PR.

## Plan de test manuel

`make serve` → **http://www.ela.ooo:35080**. Chrome en principal, **Firefox et Safari obligatoires sur
A et B** : c'est là que le comportement du focus sur cible de fragment diffère.

**A. Lien d'évitement, sur `/`**
1. Barre d'adresse puis `Tab` : premier arrêt = « Aller au contenu principal », visible, avec anneau.
2. `Entrée` → la page défile ; console : `document.activeElement` = `<main id="main" tabindex="-1">`.
3. `Tab` → le focus va au **premier lien du contenu**, pas dans le header. *(L'étape qui échoue si le
   `tabindex` manque, notamment sous Safari/Firefox.)*
4. À la **souris**, cliquer le logo du header : le lien ne doit pas intercepter le clic.
5. Menu mobile ouvert (< 995 px) puis `Tab` : le lien reste lisible au-dessus de l'overlay.

**B. Anneau de focus** — sur chaque page : tout parcourir au `Tab`, l'anneau doit être **visible à
chaque arrêt** ; puis **cliquer** les mêmes éléments : **aucun anneau** sur liens et boutons.

| Page | Point de contrôle |
|---|---|
| `/` | briques à fond `$color-dark` / `$color-primary` / `$color-tertiary` → le **halo blanc** doit porter ; footer (fond `#fff`) → l'**anneau violet** doit porter |
| `/blog` + un article | `.kudo` (l'anneau doit **réapparaître**), `.article-footer` (fond sombre). Console : `document.querySelectorAll('main').length === 1` |
| `/etudes-de-cas/exemple` | `.gallery__item > button` : anneau au clavier, aucun au clic ; halo non écrêté par `.image { overflow: hidden }` |
| `/social` | les 3 `<select>` : anneau au focus, au clavier **et** au clic (comportement natif attendu sur un contrôle de saisie) |
| `/equipe/<membre>/signature` | `<textarea>` : anneau suivant l'arrondi |

**C. Reprise de focus Swup**
1. `/` → clic sur `/blog` : **un seul** mouvement de défilement, pas de double saut.
2. Console : `document.activeElement` = `<main id="main">` ; `Tab` part du **début du nouveau contenu**.
3. 4-5 navigations puis `Précédent`/`Suivant` (`animateHistoryBrowsing: true`) : même comportement.
4. **VoiceOver** (`Cmd+F5`) : entendre « Navigation vers : <titre> » à chaque navigation.
5. `document.documentElement.hasAttribute('aria-busy')` → `false`.
   **Répéter sur `/services/optimiser` et `/services/ia`** : ces deux pages posent
   `{% set swupContainers = [] %}`, donc `containers` retombe sur `#swup` qui ne matche rien. Si
   `renderPage` échoue, `transitionEnd` n'est jamais émis et l'`aria-busy="true"` **reste collé sur
   `<html>`** → page muette aux lecteurs d'écran. Tester aussi un clic sur un lien mort. Si reproduit :
   bloquant, ouvrir un ticket.

**D. Mouvement réduit** — *Réglages Système › Accessibilité › Affichage › Réduire les animations*, puis
**recharger** (la config Swup est lue au chargement).
- Lien d'évitement : saut **instantané**. Navigation : défilement instantané, page bien **en haut**.
- **Les animations AOS doivent toujours fonctionner** — aucun bloc ne doit rester invisible. C'est le
  test qui prouve qu'on n'a pas introduit le bug qu'on cherchait à éviter.
- Repasser le réglage sur *off*, recharger : le défilement animé revient.

## Risques à surveiller

- **Halo écrêté** par un ancêtre `overflow: hidden` (`.gallery .image`, `.social-post`). L'`outline`,
  non écrêtable, reste dans tous les cas → dégradation acceptable.
- **`all: revert`** (`pages/_page-signature.scss:14`) rend l'anneau **natif** dans l'aperçu de
  signature. Un indicateur subsiste → pas d'échec 2.4.7, laissé tel quel.
- **Annonce Swup** : le plugin préfère le premier `h1`/`h2` de `#main` au `<title>` — la qualité de
  l'annonce dépend donc des `h1`.
- **Support `:focus-visible`** : pas de `.browserslistrc` ni de clé `browserslist` (vérifié). Supporté
  partout depuis Safari 15.4. Si non reconnu, les deux règles sont ignorées et l'anneau **natif**
  reprend la main — jamais « aucun indicateur ».
- **Dette réouvrable** : rien n'empêche un futur composant de reposer `outline: none`. Garde-fou
  stylelint = hors périmètre, ticket à ouvrir.
