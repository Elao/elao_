# a11y RGAA — découpage en 4 PR, et plan du PR A

Branche `feat/a11y-rgaa-audit`, basée sur `master` @ `afcc1df72`.
Chantier issu de la check-list RGAA interne (141 lignes, 89 critères, 14 NC). Deux analyses en amont :
priorisation P1→P3 et arbitrage automatisable/humain.

Ce document contient **le découpage retenu** (§ 1) puis **le plan d'exécution du PR A** (§ 2 à § 9).
Toutes les références fichier:ligne ont été **revérifiées** dans le worktree ; les écarts avec les
rapports amont sont listés au § 3.

---

## 1. Découpage : 4 PR, pas 1 et pas 8

### Pourquoi pas un seul PR

- **Culture du dépôt** : les 25 derniers commits font 1 à 6 fichiers. Un PR a11y de 30 fichiers serait
  illisible ici.
- **Les previews par PR existent** (`.github/workflows/deploy_pr_preview.yaml`) : chaque PR a son URL
  testable. C'est l'argument décisif — on valide le parcours clavier sur une preview *sans* que les
  changements de contraste brouillent la lecture.
- **Les profils de revue sont incompatibles** : le lot clavier se valide en tabulant, le lot contraste se
  valide page par page à l'œil, le lot palette demande un designer. Tout mélanger, c'est le relecteur le
  plus lent qui bloque le correctif le plus rapide.

### Pourquoi pas les 8 PR de la séquence amont

Deux **collisions de merge garanties**, vérifiées dans le worktree :

**C1 — `assets/scss/components/_contact.scss:124-126`.** La règle fautive fait trois lignes :

```scss
a {
  border: none;
}
```

P1-4 veut y ajouter le soulignement (le sélecteur d'élément `a { color: $color-info }` de
`generic/_a.scss:2` gagne, donc les liens Maps / `tel:` / `mailto:` sont indiscernables du texte) ;
P1-5 veut y corriger la couleur (`$color-info` sur `$color-secondary` = 4,25:1 à 20px). **Mêmes trois
lignes.** La séquence amont les plaçait en PR 3 et PR 4.

**C2 — `assets/js/controllers/swup_plugins_controller.js`.** 32 lignes, une seule méthode, un seul
`plugins.push()`. P1-6 (reprise de focus) et la part *scroll* de P1-7 (`animateScroll` conditionnel)
modifient **le même appel**. La séquence amont les plaçait en PR 1 et PR 4.

### Le découpage retenu

| PR | Lots | Rationale |
|---|---|---|
| **A** — Socle de navigation clavier | P1-1 focus visible · P1-2 lien d'évitement · P1-6 reprise de focus Swup · **la tranche mouvement/scroll de P1-7** | Une seule validation : on tabule. La tranche scroll de P1-7 rejoint P1-6 à cause de **C2**. Cf. D5 pour la frontière exacte. |
| **B** — Noms accessibles | P1-3 en entier | **Zéro chevauchement de fichiers** avec A, C et D. Parallélisable — peut partir en même temps que A. |
| **C** — Lisibilité | P1-5 contrastes · **P1-4 liens** · P1-8 réflow ponctuel | Fusionner P1-4 et P1-5 n'est pas un compromis : **C1** les rend inséparables, et elles demandent **la même relecture visuelle page par page**. Les séparer coûterait deux cycles de revue pour le même travail d'œil. |
| **D** — Plan du site | P1-9 · `<nav aria-label>` + `aria-current` de la pagination | Nouvelle route, nouveau template, décisions de contenu. Même sujet navigation. |

Ordre : **A avant tout le reste** (un lien d'évitement sans anneau visible ne sert à rien). B en
parallèle de A. C après A pour que la revue visuelle porte sur un site déjà correct au clavier. D
indépendant.

**Le reste n'est pas encore de l'ordre du PR** : P2-1 (palette) attend un arbitrage design, P2-2 (Text
Spacing) attend un test de 30 minutes, P2-3 (GIF) attend une décision éditoriale, P1-7 résiduel (AOS)
attend une QA visuelle globale. En ouvrir des branches maintenant, c'est ouvrir des branches qui vont
pourrir. → § 9.

Si un artefact unique « mise en conformité RGAA » est nécessaire (interne, appel d'offres), c'est une
**issue chapeau** qui référence les 4 PR, pas un PR monolithique.

---

## 2. Décisions — PR A

### D1 — `@swup/a11y-plugin@^3.0.0` plutôt qu'un hook maison

**Swup installé : 3.1.1** (`package-lock.json` → `node_modules/swup`), pas Swup 4. L'API `hooks`
(`swup.hooks.on('page:view')`) **n'existe pas** : Swup 3 expose `swup.on('<camelCase>')`
(`node_modules/swup/src/modules/events.ts`). Écrire le hook à la main était donc un piège de version.

`@swup/a11y-plugin@3.0.0` déclare `peerDependencies: { swup: '^3.0.0' }` et `dependencies:
{ '@swup/plugin': '^2.0.0' }` — et `@swup/plugin@2.0.3` est **déjà dans l'arbre** (tiré par
`@swup/scroll-plugin`). C'est la seule version de la lignée compatible : la 2.x cible `@swup/plugin@1`,
la 4.x exige `swup@^4`.

Source relue (dist v3.0.0) : le plugin fait exactement ce que P1-6 demande, ni plus ni moins.

```js
mount() {
    this.swup.on('contentReplaced', this.announceVisit);
    this.swup.on('transitionStart', this.onTransitionStart);   // aria-busy="true" sur <html>
    this.swup.on('transitionEnd', this.onTransitionEnd);       // retrait de aria-busy
}
// announceVisit -> requestAnimationFrame(() => { announcePageName(); focusPageContent(); })
// focusPageContent -> querySelector(contentSelector).setAttribute('tabindex','-1') + focus({preventScroll: true})
```

Trois raisons de le préférer à ~25 lignes maison :

1. **`contentReplaced` et pas `pageView`.** `pageView` est aussi déclenché par `enable()`
   (`src/Swup.ts:185`) : un hook maison sur `pageView` aurait volé le focus **au chargement initial**
   de chaque page. `contentReplaced` n'est déclenché que dans `renderPage.ts:39`, donc uniquement sur
   navigation réelle.
2. **`focus({ preventScroll: true })`.** Sans ça, le `.focus()` déclenche un scroll natif qui entre en
   conflit avec `SwupScrollPlugin` configuré en `animateScroll.betweenPages: true`
   (`assets/js/controllers/swup_plugins_controller.js:19-21`) : double saut visible.
3. **La région live.** `on-demand-live-region` crée/détruit la région à la demande, ce qui évite le
   piège classique du `aria-live` inséré en même temps que le message (non annoncé par la plupart des
   lecteurs d'écran). C'est la partie qu'on rate quand on la code soi-même.

Coût : 2 dépendances transitives légères (`on-demand-live-region`, `focus-options-polyfill`).
`focus-options-polyfill` patche `HTMLElement.prototype.focus` uniquement si `preventScroll` n'est pas
supporté — inerte sur les navigateurs cibles.

`contentSelector` est passé explicitement à `'#main'` (défaut : `'main'`) : plus lisible, et immunisé
contre tout futur `<main>` imbriqué.

### D2 — Double anneau de focus plutôt qu'énumération des fonds sombres

Le rapport amont proposait `outline: 3px solid $color-primary` + surcharges
`.brick :focus-visible, .footer :focus-visible { outline-color: #fff }`. Rejeté : voir « Corrections »
(la `.footer` est sur fond blanc, la surcharge y rendrait l'anneau invisible), et surtout parce que
l'énumération des contextes sombres est **ingérable** — j'ai relevé 18 composants avec
`background: $color-dark|$color-primary|$color-tertiary` contenant des éléments focusables
(`_brick.scss`, `_article-footer.scss:8`, `_article-author.scss:101`, `_tile.scss:12`,
`_contact.scss:170`, `prospect-contact.scss:4`, `_tabs-component.scss:126`, `_build-steps.scss:14`,
`_planning-steps.scss:22`, `_project-team.scss:30`, `_miniature-highlight.scss:70`, `_brick-*.scss`…).
Et sur `$color-tertiary` **aucune** des deux couleurs ne passe seule.

Un anneau violet **doublé d'un halo blanc collé à l'élément** garantit qu'au moins l'un des deux
atteint 3:1 (WCAG 1.4.11) sur **tous** les tokens de la charte, sans une seule surcharge. Ratios
recalculés depuis `assets/scss/base/_variables.scss:2-9` :

| Fond | anneau `$color-primary` #7f1A55 | halo `#fff` |
|---|---|---|
| `#fff` | **9,63** ✅ | 1,00 ❌ |
| `$color-secondary` #fee3e4 | **7,94** ✅ | 1,07 ❌ |
| `$color-light` #2ecccb | **4,86** ✅ | 1,98 ❌ |
| `$color-tertiary` #f4756d | **3,47** ✅ | 2,77 ❌ |
| `$color-brand` #ff4345 | 2,82 ❌ | **3,42** ✅ |
| `$color-info` #1e7695 | 1,87 ❌ | **5,16** ✅ |
| `$color-primary` #7f1A55 | 1,00 ❌ | **9,63** ✅ |
| `$color-dark` #0d3a5a | 1,18 ❌ | **11,33** ✅ |

Aucune ligne sans ✅. Le `box-shadow` est sans risque de collision : le dépôt n'utilise
`box-shadow` que dans 3 resets à `none` (traités ici) et sur `generic/_kbd.scss:5` (non focusable).

### D3 — `.skip-link` dédiée, et `tabindex="-1"` déclaré côté serveur sur `#main`

- **Ne pas réutiliser `.screen-reader`** (`assets/scss/base/_utilities.scss:1-8`) : `left: -10000px`
  sans règle de révélation au focus → le lien resterait invisible pour l'utilisateur clavier voyant,
  échec 2.4.7. Classe dédiée `.skip-link`.
- **`position: absolute` et masquage par `top: -100px`** (et non `clip-path` / `width: 1px`) : un
  élément « visuellement masqué » mais conservant sa boîte au coin haut-gauche intercepterait les
  clics sur le logo du header. Hors-écran par positionnement, pas de zone cliquable fantôme. Autre
  conséquence utile : `body` est `display: flex; flex-direction: column`
  (`base/_layout.scss:5-16`) — en `absolute` le lien **n'est pas un flex item** et ne perturbe pas la
  colonne.
- **`tabindex="-1"` sur `<main id="main">` en dur dans le template.** Sans ça, Safari et Firefox ne
  déplacent pas le focus sur une cible de fragment non focusable : la tabulation suivante repartirait
  du header, le lien d'évitement ne servirait à rien. Et il faut le déclarer **côté serveur** parce que
  Swup 3 remplace le conteneur par `block.outerHTML = html`
  (`node_modules/swup/src/modules/replaceContent.ts`) : l'attribut posé en JS serait perdu à chaque
  navigation, alors que celui du template revient avec le HTML servi.
- `href="#main"` n'est **pas** intercepté par Swup : `linkSelector` (`templates/base.html.twig:97-105`)
  ne matche que `a[href^="http://<host>"]` et `a[href^="/"]`. Comportement d'ancre natif.
- Le lien est placé **hors des conteneurs Swup** (`containers: ['#main', '#nav']`,
  `templates/base.html.twig:93`), directement sous `<body>` : il survit aux transitions.

### D4 — Aucun `!important`, les 10 resets sont supprimés

L'import de `base/_focus.scss` arrive en ligne 18 de `style.scss`, donc **avant** les composants.
Toutes les règles de reset existantes le battent, soit par spécificité soit par ordre de cascade :

| Site | Sélecteur | Spécificité vs `:focus-visible` (0,1,0) | Verdict |
|---|---|---|---|
| `components/_form-group.scss:39` | `.form-group__input:focus` | (0,2,0) — gagne | supprimer |
| `components/_form-group.scss:77` | `.form-group__message:focus` | (0,2,0) — gagne | supprimer |
| `components/_kudo.scss:12-13` | `.kudo:hover,:active,:focus` | (0,2,0) — gagne | supprimer (`outline` **et** `box-shadow`) |
| `components/_kudo.scss:38-39` | `.kudo--active` | (0,1,0) mais plus loin dans la cascade — gagne | supprimer (`outline` **et** `box-shadow`) |
| `components/_gallery.scss:48` | `.gallery__item > button` | (0,1,1) — gagne | supprimer |
| `components/_social-post.scss:16-17` | `.social-post-generator form select` | (0,1,2) — gagne | supprimer (`outline` **et** `box-shadow`) |
| `pages/_page-signature.scss:31` | `.page-signature__tutorial textarea:focus` | (0,2,1) — gagne | supprimer |

Ni `!important`, ni `html :focus-visible`, ni déplacement de l'import en fin de `style.scss` ne
règleraient le problème (une règle `.x:focus` à (0,2,0) resterait gagnante) : **il faut retirer les
resets**, point. Le dépôt souffre déjà de `!important` structurels (`_btn.scss:8,16`,
`_miniature-highlight.scss:99`, `_brick-send-message.scss:10`), on n'en ajoute pas.

**Arbitrage cas par cas : aucun des 7 ne nécessite `:focus:not(:focus-visible)`.** Aucun n'est un
choix design « pas d'anneau pour la souris » : ce sont 7 resets en aveugle, dont 5 sur des contrôles
de formulaire ou des boutons où l'anneau est souhaitable pour *tous* les utilisateurs. Détail :

1. `.form-group__input` / `.form-group__message` : **code mort**. `.form-group__input`,
   `.form-group__label` et `.form-group--dark` ne sont utilisés par aucun template ; et le seul
   `.form-group__message` réel (`templates/site/contact.html.twig:24`) est un `<div>` non focusable →
   son bloc `:focus` ne matche jamais. Suppression = dette évitée à coût nul (le jour où un vrai
   formulaire arrive, ~18 lignes de la check-list se réouvriraient).
2. `.kudo` : `<a class="kudo" target="_blank">` réel (`templates/blog/article.html.twig:197`). Anneau
   voulu. Les `box-shadow: none` ne réinitialisaient rien (aucun `box-shadow` n'est posé sur `.kudo`).
3. `.kudo--active` : classe **jamais posée** (0 occurrence dans `assets/js/` et `templates/`). No-op.
4. `.gallery__item > button` : `<button>` réel, sur `/etudes-de-cas/exemple`
   (`content/case-study/example.md:75-130`, pas exclu du build — `config/packages/stenope.yaml` n'a pas
   d'`excludes` de contenu). Chrome/Firefox ne matchent pas `:focus-visible` au clic souris sur un
   `<button>` → pas de régression souris.
5. `select` de `/social`, `textarea` de `/equipe/{membre}/signature` : contrôles de saisie, l'anneau
   est un gain net.

Les ~51 règles `&:focus` qui ne changent qu'une couleur ne sont **pas** touchées par ce PR (aucune ne
pose d'`outline`, donc aucune ne combat le nouvel anneau). Leur échec 1.4.1 est traité en **PR C**.

### D5 — P1-7 est scindé : le mouvement de *ce* parcours entre, AOS reste dehors

C'est la décision qui a changé avec le passage à 4 PR. La frontière n'est plus « P1-7 dedans / dehors »
mais **« mouvement que ce PR provoque » / « mouvement du reste du site »**.

**Entre dans le PR A** — parce que ce PR *crée* ce défilement, et parce que **C2** l'impose :

| Élément | Fichier | Motif |
|---|---|---|
| `html { scroll-behavior: auto }` sous `prefers-reduced-motion` | `base/_focus.scss` | Le lien d'évitement provoque un défilement, animé par `html { scroll-behavior: smooth }` (`base/_layout.scss:2`). Indissociable du lot. |
| `animateScroll: false` sous `prefers-reduced-motion` | `swup_plugins_controller.js` | **C2** : même `plugins.push()` que la reprise de focus. Les séparer garantit un conflit. Et c'est la contrepartie directe du scroll inter-pages que la reprise de focus met en scène. |

**Reste dehors** — le vrai chantier, et la raison est décisive :

**AOS masque le contenu et compte sur l'animation pour le révéler.** `[data-aos]` part à
`opacity: 0` ; désactiver les animations en CSS **sans** désactiver AOS en JS rend des dizaines de
blocs **définitivement invisibles**. L'équipe connaît déjà le piège — c'est exactement la raison d'être
du garde-fou `html.no-js [data-aos]` (`assets/scss/style.scss:6-10`). Une media query « kill-switch »
naïve reproduirait le bug **pour la population qu'on cherche à servir**.

Le lot résiduel demande de coordonner en une passe cohérente : `AOS.init`
(`assets/js/app.js:32-43`, où `disable` est déjà utilisé pour le mobile) · `animateHistoryBrowsing`
(`templates/base.html.twig:95` — transition de fondu, pas du scroll : non couvert ici, et non
conditionnable en Twig puisque le serveur ne connaît pas la préférence) · 54 `transition` + 8
`animation` + 6 `@keyframes` en SCSS, dont un `transition: … !important`
(`_brick-send-message.scss:10`) qui exigerait un `!important` en retour · `Typewriter.js` ·
`path_controller.js` · le tilt 3D de `contact_controller.js` + `_contact.scss:11,30,60,75`.

Profil de revue **opposé** à celui-ci : ici, revue fonctionnelle clavier sur 7 pages ; là, QA visuelle
sur tout le site, avec un risque de contenu invisible. → backlog, § 9.

**Limite connue et acceptée :** `matchMedia` est lu une seule fois, à la configuration du plugin. Un
changement de la préférence système en cours de session n'est pas pris en compte côté Swup (la partie
CSS, elle, réagit immédiatement). Sans intérêt de complexifier pour ce cas.

### D6 — Deux fichiers SCSS, pas un

`:focus-visible` va dans `base/_focus.scss` (préoccupation transverse, comme `_variables`, `_layout`,
`_utilities`) ; `.skip-link` va dans `components/_skip-link.scss`. Le dépôt tient une convention
stricte « un composant = un fichier dans `components/` » (90+ fichiers) et `base/` ne contient que du
transverse. Un `.skip-link` dans `base/` détonnerait. Si l'équipe préfère un seul fichier, la fusion
est triviale.

---

## 3. Corrections aux rapports amont

À reprendre avant de rediffuser le plan d'action :

| Référence amont | Statut | Détail |
|---|---|---|
| `swup.hooks.on('page:view')` / `content:replace` | ❌ **API inexistante ici** | Nommage **Swup 4**. Installé : **swup 3.1.1** → `swup.on('contentReplaced')`. Voir D1. |
| `.footer :focus-visible { outline-color: #fff }` | ❌ **faux** | `.footer` **n'a aucun `background`** (`grep background assets/scss/components/_footer.scss` = 0) → fond `#fff` hérité de `body` (`base/_layout.scss:14`), et `.footer a { color: $color-text }`. Un anneau blanc y serait **invisible**. Corrigé par D2. |
| `border-radius: 2px` dans la règle `:focus-visible` | ⚠️ **régression** | `border-radius` s'applique à **l'élément**, pas seulement à l'anneau : les fonds carrés (`.btn`, `.brick`, `.tag`) se seraient arrondis à la prise de focus → saut visuel. Retiré. |
| Séquence en 8 PR : P1-4 en PR 4 et P1-5 en PR 3 | ❌ **conflit** | **C1** — les deux modifient `_contact.scss:124-126`, les mêmes trois lignes. Regroupées en PR C. |
| Séquence en 8 PR : P1-6 en PR 1 et P1-7 en PR 4 | ❌ **conflit** | **C2** — les deux modifient le `plugins.push()` de `swup_plugins_controller.js`. La tranche scroll de P1-7 remonte en PR A (D5). |
| « 5 `outline: none` » (bloc auditabilité) | ⚠️ | Il y en a **7** (`outline: none|0`), plus **3** `box-shadow: none` qui neutralisent le halo. La liste à 7 du rapport de priorisation est la bonne. |
| `assets/js/app.js:32-41` (`AOS.init`) | ⚠️ | Le bloc va de **32 à 43**. |
| Les 7 sites `outline`, `_utilities.scss:1-8`, `base.html.twig:93/95/110/111/193`, `blog/article.html.twig:151`, `swup_plugins_controller.js:19-21`, `_brick-send-message.scss:40-44`, `style.scss:3/6-10/17` | ✅ | Confirmés au caractère près. |
| `$color-primary` 9,63:1 sur blanc / 7,94:1 sur rose | ✅ | Recalculés, identiques. |
| `.gallery` « composant sans template » | ✅ précisé | Utilisé uniquement en HTML brut dans `content/case-study/example.md:75-130` → page `/etudes-de-cas/exemple`, bel et bien construite. |

**Non vérifiable :** les seules références amont non contrôlées sont hors périmètre du PR A (fichiers
`content/`, `src/Emoji/`, `_banner-*`, `_category-switch`, `pages/_page-carriere`). Le worktree n'a ni
`vendor/` ni `node_modules/` — les vérifications Swup ont été faites sur l'arbre installé du dépôt
principal, au même commit.

**Découverte non signalée en amont :** `templates/site/services/optimiser.html.twig:4` et
`templates/site/services/ia.html.twig:4` posent `{% set swupContainers = [] %}`. Le contrôleur UX Swup
retombe alors sur `containers: ['#swup']`, sélecteur qui ne matche rien. Conséquence pour ce PR : sur
ces deux pages, si `renderPage` échoue, `transitionEnd` n'est jamais déclenché et
l'`aria-busy="true"` posé par le plugin sur `<html>` **reste collé** — page entière rendue muette aux
lecteurs d'écran. Même risque théorique sur erreur réseau/404 (`transitionStart` est déclenché dans
`loadPage.ts:37`, `transitionEnd` seulement dans `enterPage.ts:7,20`). → étape de test dédiée, et à
remonter en amont si reproduit.

---

## 4. Changements, fichier par fichier — PR A

### 4.1 `assets/scss/base/_focus.scss` — **nouveau**

```scss
// Indicateur de prise de focus visible sur tout le site (WCAG 2.4.7 / RGAA 10.7).
//
// Double anneau : halo blanc collé à l'élément + anneau $color-primary à l'extérieur.
// L'un des deux atteint toujours 3:1 (WCAG 1.4.11) quel que soit le token de fond,
// ce qui évite d'énumérer les 18 composants à fond sombre (.brick, .article-footer,
// .tile, .contact__infos, prospect-contact…) :
//
//   $color-primary  9,63 sur #fff · 7,94 sur $color-secondary · 4,86 sur $color-light · 3,47 sur $color-tertiary
//   #fff           11,33 sur $color-dark · 9,63 sur $color-primary · 5,16 sur $color-info · 3,42 sur $color-brand
//
// :focus-visible et non :focus : l'anneau n'apparaît pas au clic souris, les ~51
// règles `&:focus` existantes (changement de couleur seul) restent inchangées.
// Pas de border-radius ici : la propriété s'appliquerait à l'élément lui-même et
// arrondirait les fonds carrés à la prise de focus.
:focus-visible {
  outline: 3px solid $color-primary;
  outline-offset: 2px;
  box-shadow: 0 0 0 2px #fff;
}

// Le lien d'évitement et la reprise de focus après navigation Swup provoquent un
// défilement, animé par `html { scroll-behavior: smooth }` (base/_layout.scss:2).
// On le rend instantané pour qui demande moins de mouvement (WCAG 2.3.3 / RGAA 13.8).
// Pendant : `animateScroll` du ScrollPlugin, dans swup_plugins_controller.js.
//
// NB : le chantier prefers-reduced-motion complet (AOS, typewriter, tilt 3D, les 54
// transitions SCSS) n'est PAS traité ici — cf. work/a11y-rgaa-audit.md § 9.
@media (prefers-reduced-motion: reduce) {
  html {
    scroll-behavior: auto;
  }
}
```

### 4.2 `assets/scss/components/_skip-link.scss` — **nouveau**

```scss
// Lien d'évitement vers le contenu principal (WCAG 2.4.1 / RGAA 12.7).
//
// Volontairement PAS `.screen-reader` (base/_utilities.scss:1-8) : cette classe
// déporte à left: -10000px sans règle de révélation au focus, le lien resterait
// invisible pour un utilisateur clavier voyant (échec 2.4.7).
//
// Masqué par `top` négatif plutôt que par clip/1px : une boîte « visuellement
// masquée » mais conservée au coin haut-gauche intercepterait les clics sur le
// logo du header. En `absolute`, le lien n'est pas non plus un flex item de <body>
// (base/_layout.scss:5-16).
//
// :focus et non :focus-visible : le lien doit se révéler à toute prise de focus.
.skip-link {
  padding: 15px 20px;
  position: absolute;
  top: -100px;
  left: 0;
  z-index: 1002; // au-dessus de .nav-mobile (1000) et de l'overlay S.E.E. (1001)
  font-family: 'antikor bold';
  font-size: 16px;
  color: #fff;
  text-decoration: underline;
  background: $color-primary;

  &:focus {
    top: 0;
  }
}
```

### 4.3 `assets/scss/style.scss` — 2 imports

Remplacer les lignes **17 à 20** :

```scss
@import "base/_utilities";

// layout components
@import "components/_header";
```

par :

```scss
@import "base/_utilities";
@import "base/_focus";

// layout components
@import "components/_skip-link";
@import "components/_header";
```

`base/_focus` doit rester **après** `base/_variables` (l. 13) pour `$color-primary`, et
`_skip-link` est placé en tête du bloc « layout components » parce qu'il est le premier élément du DOM.

### 4.4 `templates/base.html.twig` — lien d'évitement + `tabindex`

**(a)** Après la ligne 110 (`}}>`, fin des attributs de `<body>`), **avant** `{% block header %}`
(l. 111), indentation 8 espaces :

```twig
        }}>
        <a class="skip-link" href="#main">Aller au contenu principal</a>
        {% block header %}
```

**(b)** Ligne 193 :

```twig
        <main id="main" tabindex="-1">
```

(remplace `<main id="main">`)

### 4.5 `templates/blog/article.html.twig` — `<main>` imbriqué

Deux `<main>` dans le même document = HTML invalide + deux régions `main`
(WCAG 1.3.1 / **RGAA 12.6**). Le `<main>` de `base.html.twig:193` est le bon ; celui de l'article est
un simple conteneur de mise en page.

- Ligne **151** : `<main class="article-content__main">` → `<div class="article-content__main">`
- Ligne **194** : `</main>` → `</div>`

Aucun impact visuel : `.article-content__main` n'est ciblé que par classe
(`assets/scss/components/_article-content.scss:32` et `:68`), `main` n'apparaît en SCSS que dans
`_normalize.scss:24` (`display: block`), et `<div>` est déjà `display: block`. Aucun JS ne référence
`main` (`grep "'main'\|#main" assets/js/` = 0).

### 4.6 `package.json` — dépendance

Dans `dependencies` (et non `devDependencies` : c'est un plugin de runtime, comme
`@swup/scroll-plugin` et `@swup/progress-plugin`), avant `@swup/fade-theme` (l. 29) :

```json
        "@swup/a11y-plugin": "^3.0.0",
```

Puis `npm install` pour régénérer `package-lock.json` (`@swup/plugin@2.0.3` est déjà présent, seuls
`on-demand-live-region` et `focus-options-polyfill` s'ajoutent).

### 4.7 `assets/js/controllers/swup_plugins_controller.js` — reprise de focus + scroll

**Un seul fichier, deux préoccupations** (cf. C2 et D5) : la reprise de focus et la neutralisation du
scroll animé sous `prefers-reduced-motion` touchent le même `plugins.push()`.

Fichier complet après modification :

```js
import { Controller } from '@hotwired/stimulus';
import SwupA11yPlugin from '@swup/a11y-plugin';
import SwupScrollPlugin from '@swup/scroll-plugin';
import SwupProgressPlugin from '@swup/progress-plugin';

export default class extends Controller {
    connect() {
        this.element.addEventListener('swup:pre-connect', this._onPreConnect);
    }

    disconnect() {
        this.element.removeEventListener('swup:pre-connect', this._onPreConnect);
    }

    _onPreConnect(event) {
        // Lu une seule fois, à la configuration des plugins : un changement de la
        // préférence système en cours de session n'est pas répercuté côté Swup.
        // La contrepartie CSS (scroll-behavior, base/_focus.scss) réagit, elle.
        const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        event.detail.options.plugins.push(
            // Swup remplace #main via outerHTML : le focus retombe sur <body> et le
            // lecteur d'écran n'est pas informé du changement de page.
            // Le plugin le replace sur #main (tabindex="-1" + preventScroll, pour ne
            // pas concurrencer le scroll animé du ScrollPlugin ci-dessous) et annonce
            // le nouveau titre dans une région live (WCAG 2.4.3 / 4.1.3, RGAA 12.8).
            new SwupA11yPlugin({
                contentSelector: '#main',
                announcementTemplate: 'Navigation vers : {title}',
                urlTemplate: 'Nouvelle page : {url}',
            }),
            new SwupScrollPlugin(
                {
                    doScrollingRightAway: true,
                    // Défilement instantané pour qui demande moins de mouvement
                    // (WCAG 2.3.3 / RGAA 13.8).
                    animateScroll: reducedMotion ? false : {
                        betweenPages: true,
                    }
                }
            ),
            new SwupProgressPlugin({
                transition: 300,
                delay: 0,
                initialValue: 0.25,
                hideImmediately: true
            }),
        );
    }
}
```

Style : 4 espaces, quotes simples, point-virgules, trailing comma — conforme à `.eslintrc.json`.

---

## 5. Régressions possibles et points de vigilance

| Risque | Évaluation |
|---|---|
| **Anneaux de focus apparaissant à la souris** là où le design les avait retirés | Écarté par construction : la règle est en `:focus-visible`. Chrome/Firefox/Safari ne le matchent pas au clic souris sur `<a>`/`<button>`. Ils le matchent sur `<input>`/`<textarea>`/`<select>` — c'est le comportement voulu (3 des 7 resets portaient précisément sur des contrôles de saisie). |
| **`box-shadow` écrêté** par un ancêtre `overflow: hidden` | Réel mais borné : `.gallery .image`, `.social-post`, `_last-articles.scss:24-26`, `_team.scss:44-49`. L'`outline` (hors flux de peinture, non écrêtable par `overflow`) reste dans tous les cas. À contrôler visuellement sur `/etudes-de-cas/exemple` et `/social`. |
| **Halo blanc fragmenté** sur un lien inline multi-lignes | Cosmétique. Même limite que l'`outline` sur `display: inline`. Non bloquant. |
| **`all: revert`** (`pages/_page-signature.scss:14`, `.page-signature__preview *`) écrase l'anneau | Spécificité (0,1,1) > (0,1,0) : à l'intérieur de l'aperçu de signature, l'anneau redevient **natif**. Il y a toujours un indicateur, donc pas d'échec 2.4.7. Laissé tel quel. |
| **Conteneurs Swup** : le lien d'évitement disparaît après navigation | Impossible : il est hors de `['#main', '#nav']` (`base.html.twig:93`). À vérifier tout de même sur les 2 pages `swupContainers = []`. |
| **`aria-busy="true"` bloqué sur `<html>`** si `transitionEnd` n'est pas déclenché | Cf. § 3. Test dédié sur `/services/optimiser`, `/services/ia` et un lien mort. Si reproduit : conditionner le plugin ou patcher en amont. |
| **Double saut de défilement** (focus natif vs `SwupScrollPlugin`) | Neutralisé par `preventScroll: true` du plugin. À confirmer à l'œil sur une navigation blog → article. |
| **`animateScroll: false` casse le positionnement** sous mouvement réduit | Peu probable : `false` désactive l'*animation*, pas le repositionnement (`doScrollingRightAway` est conservé). À vérifier en mouvement réduit : la page doit arriver **en haut**, instantanément. |
| **Annonce vocale en anglais** | Neutralisé : `announcementTemplate`/`urlTemplate` surchargés en français. Attention, le plugin **préfère le premier `h1, h2, [role=heading]` de `#main`** au `document.title` — donc l'annonce dépend de la qualité des `h1`, pas du `<title>`. |
| **Le `<main>` de l'article** devenu `<div>` | Aucun sélecteur d'élément ni JS concerné (vérifié). Le `<h1>` de l'article reste dans `#main`, donc l'annonce Swup ne change pas. |
| **Guerres de spécificité / `!important`** | Aucun `!important` ajouté. Les 10 resets sont retirés, ce qui est la **seule** façon de faire gagner la règle de base — un déplacement d'import n'y suffirait pas (cf. D4). |
| **Support `:focus-visible`** | Aucun `.browserslistrc` ni clé `browserslist` : autoprefixer/babel sur les valeurs par défaut. `:focus-visible` est supporté partout depuis Safari 15.4 (2022). Si un navigateur ne le connaît pas, les deux règles sont ignorées et l'anneau **natif** reprend la main : dégradation propre, jamais « aucun indicateur ». |
| **Dette qui va rouvrir** | Rien n'empêche un futur composant de reposer `outline: none`. Garde-fou (règle stylelint) = hors périmètre, à ouvrir en ticket. |

**Aucune couverture automatisée n'existe** : `tests/` ne contient que `bootstrap.php`, et `make test`
se réduit à `build.content.without-images` — il valide que le build statique passe (donc attrapera une
erreur de syntaxe Twig), rien de plus. D'où le plan de test manuel ci-dessous.

---

## 6. Découpage en commits — PR A

Style du dépôt sur `master` : messages **français à préfixe entre crochets** (`[Blog] …`,
`[Carrière] …`). Préfixe retenu : `[A11y]`.

1. **`[A11y] Indicateur de prise de focus visible sur tout le site`**
   `assets/scss/base/_focus.scss` (nouveau) · `assets/scss/style.scss` (import) · suppression des 7
   `outline: none|0` et des 3 `box-shadow: none` dans `_form-group.scss`, `_kudo.scss`,
   `_gallery.scss`, `_social-post.scss`, `_page-signature.scss`.

2. **`[A11y] Corrige le <main> imbriqué des articles de blog`**
   `templates/blog/article.html.twig` (l. 151 et 194). **Avant** le commit 3 : le lien d'évitement doit
   viser un `#main` non ambigu.

3. **`[A11y] Ajoute un lien d'évitement vers le contenu principal`**
   `assets/scss/components/_skip-link.scss` (nouveau) · `assets/scss/style.scss` (import) ·
   `templates/base.html.twig` (lien + `tabindex="-1"` sur `#main`).

4. **`[A11y] Rétablit le focus après les transitions Swup`**
   `package.json` + `package-lock.json` · `assets/js/controllers/swup_plugins_controller.js`
   (import + `SwupA11yPlugin`).

5. **`[A11y] Neutralise le défilement animé sous prefers-reduced-motion`**
   `swup_plugins_controller.js` (`animateScroll` conditionnel) — la contrepartie CSS
   (`scroll-behavior: auto`) est livrée avec le commit 1, dans `base/_focus.scss`.

Ordre imposé : 1 avant 3 (un lien d'évitement sans anneau visible ne sert à rien), 2 avant 3, 4 avant 5
(même fichier). Le 4 se teste avec le 3.

---

## 7. Plan de test manuel — PR A

`make serve` → **http://www.ela.ooo:35080** (`PROJECT_DOMAIN` + `PORT_PREFIX` 350 dans le `Makefile`).
Navigateur principal : Chrome. **Repasser au minimum Firefox et Safari** sur les étapes A et B :
c'est précisément là que le comportement du focus sur cible de fragment diffère.

Préalables automatiques : `make lint.eslint`, `make lint.twig`, `make test`.

### A. Lien d'évitement — sur `/`
1. Cliquer dans la barre d'adresse, puis `Tab` : **le premier arrêt est « Aller au contenu principal »**,
   visible en haut à gauche, texte blanc sur violet, souligné, avec l'anneau de focus.
2. `Entrée` : la page défile jusqu'au contenu.
   Console : `document.activeElement` → `<main id="main" tabindex="-1">`.
3. `Tab` : le focus va sur le **premier lien du contenu**, pas dans le header. (C'est l'étape qui
   échoue si `tabindex="-1"` manque, notamment sous Safari/Firefox.)
4. `Maj+Tab` depuis l'étape 1 : retour à la barre d'adresse, aucun élément fantôme.
5. À la **souris**, cliquer sur le logo du header : le lien d'évitement ne doit pas intercepter le clic.
6. Ouvrir le menu mobile (largeur < 995 px) puis `Tab` : le lien d'évitement ne doit pas s'afficher
   par-dessus l'overlay de façon illisible (`z-index: 1002` > 1000).

### B. Anneau de focus — parcours au `Tab` uniquement
Sur chaque page : traverser toute la page au `Tab`, **l'anneau doit être visible à chaque arrêt**, y
compris sur fond sombre. Puis **cliquer à la souris** sur les mêmes éléments : **aucun anneau** sur les
liens et boutons.

| Page | À observer |
|---|---|
| `/` | nav desktop et mobile, `.link--brand`, `.btn--*`, `.brick-send-message` / `.brick-visit` / `.brick-contact` (fonds `$color-dark`, `$color-primary`, `$color-tertiary` → le **halo blanc** doit porter), footer (fond blanc → l'**anneau violet** doit porter) |
| `/blog` puis un article | `.article-tag-list`, `.kudo` (l'anneau doit **réapparaître**), `.article-footer` et `.article-author` (fond sombre), sommaire. Console : `document.querySelectorAll('main').length === 1` |
| `/contact` | tilt 3D, liens `tel:` / `mailto:` / Maps, `.contact__infos` en variante mobile (fond `$color-dark`) |
| `/etudes-de-cas/exemple` | `.gallery__item > button` : anneau au clavier, aucun au clic. Vérifier que le halo n'est pas écrêté par `.image { overflow: hidden }` |
| `/social` | les 3 `<select>` : anneau au focus (au clavier **et** au clic — comportement natif attendu sur un contrôle de saisie) |
| `/equipe/<un-membre>/signature` | `<textarea>` (fond corail, `border-radius: 10px`) : anneau au focus, suivant bien l'arrondi |

### C. Reprise de focus Swup
1. Depuis `/`, cliquer un lien interne (ex. `/blog`). Observer : **un seul** mouvement de défilement,
   pas de double saut.
2. Console juste après : `document.activeElement` → `<main id="main">`.
3. `Tab` immédiatement : le focus part du **début du nouveau contenu**, pas du haut du DOM.
4. Enchaîner 4-5 navigations, puis `Précédent` / `Suivant` du navigateur (`animateHistoryBrowsing: true`) :
   même comportement.
5. **VoiceOver** (`Cmd+F5`) : à chaque navigation, entendre « Navigation vers : <titre de la page> ».
6. Console après transition : `document.documentElement.hasAttribute('aria-busy')` → `false`.
   **Répéter sur `/services/optimiser` et `/services/ia`** (`swupContainers = []`) et après un clic sur
   un lien volontairement mort : si `aria-busy` reste à `true`, c'est un bloquant → ouvrir un ticket.

### D. Mouvement réduit
macOS : *Réglages Système › Accessibilité › Affichage › Réduire les animations*. **Recharger la page**
(la config Swup est lue au chargement, cf. D5).
- Activer le lien d'évitement : le saut au contenu est **instantané**, aucun défilement animé.
- Naviguer vers une autre page : le défilement inter-pages est **instantané**, et la page arrive bien
  **en haut** (c'est ce que `animateScroll: false` ne doit pas casser).
- Vérifier que les animations AOS **fonctionnent toujours** : elles ne sont volontairement pas
  désactivées dans ce PR (cf. D5 et § 9) — **aucun bloc ne doit rester invisible**. C'est le test qui
  prouve qu'on n'a pas introduit le bug qu'on cherchait à éviter.
- Repasser le réglage sur *off*, recharger : le défilement animé revient.

---

## 8. PR A — titre et corps à coller

**Titre :** `[A11y] Socle de navigation clavier : focus visible, lien d'évitement, reprise de focus Swup`

```markdown
Premier des 4 lots du chantier d'accessibilité issu de la check-list RGAA interne. Il traite les trois
défauts qui rendent le site **inutilisable au clavier**, et conditionne tous les lots suivants.

## Ce que ça corrige

- **Indicateur de prise de focus** (WCAG 2.4.7 + 1.4.11 / RGAA 10.7). Il n'existait aucune règle
  `:focus-visible` dans `assets/scss/` : `outline` n'y servait qu'à **supprimer** l'anneau natif
  (7 occurrences), et les ~51 règles `&:focus` ne changent qu'une couleur. Au clavier, on ne savait
  jamais où on se trouvait.
- **Lien d'évitement** vers le contenu principal (WCAG 2.4.1 / RGAA 12.7). Absent.
- **Reprise du focus après navigation Swup** (WCAG 2.4.3 + 4.1.3 / RGAA 12.8). Swup remplace `#main`
  par `outerHTML` : le focus retombait sur `<body>` et aucun lecteur d'écran n'était informé du
  changement de page.
- **Défilement instantané sous `prefers-reduced-motion`** (WCAG 2.3.3 / RGAA 13.8) — uniquement le
  défilement que ce PR met en scène, cf. « Non fait volontairement ».
- Au passage : **`<main>` imbriqué** dans `templates/blog/article.html.twig` (deux régions `main`,
  HTML invalide — WCAG 1.3.1 / RGAA 12.6).

## Choix à connaître pour relire

- **Double anneau** (`outline: 3px solid $color-primary` + `box-shadow: 0 0 0 2px #fff`) plutôt qu'un
  anneau simple avec des surcharges par contexte. Motif : 18 composants ont un fond sombre, et sur
  `$color-tertiary` **aucune** couleur unique n'atteint 3:1. Le double anneau garantit ≥ 3:1 sur tous
  les tokens de la charte sans une seule surcharge — tableau des ratios recalculés dans
  `work/a11y-rgaa-audit.md`.
- **`:focus-visible`**, donc **rien ne change à la souris**. Les 7 `outline: none|0` (+ 3
  `box-shadow: none`) sont supprimés : c'était la seule façon de faire gagner la règle globale, un
  simple changement d'ordre d'import n'aurait pas suffi. **Aucun `!important` ajouté.**
- **Classe `.skip-link` dédiée**, pas `.screen-reader` : cette dernière déporte à `left: -10000px`
  sans révélation au focus, le lien serait resté invisible au clavier.
- **`tabindex="-1"` sur `<main id="main">` en dur dans le template** : sans lui, Safari et Firefox ne
  déplacent pas le focus sur la cible du fragment ; et posé en JS il serait perdu à chaque
  remplacement de conteneur par Swup.
- **`@swup/a11y-plugin@^3.0.0`** plutôt qu'un hook maison. Le projet est sur **swup 3.1.1** : l'API
  `hooks` de swup 4 n'existe pas ici, et le hook « évident » (`pageView`) est aussi déclenché au
  chargement initial — il aurait volé le focus sur **toutes** les pages. La v3 du plugin est celle qui
  cible swup 3 (`peerDependencies`), et son `@swup/plugin@2` est déjà dans l'arbre.

## Non fait volontairement

Le chantier **`prefers-reduced-motion` complet** n'est pas ici. Seul le mouvement que *ce* PR provoque
est traité : `scroll-behavior` et `animateScroll` du ScrollPlugin — ce dernier parce qu'il partage le
`plugins.push()` avec la reprise de focus, les séparer garantirait un conflit.

**AOS reste actif, volontairement.** `[data-aos]` part à `opacity: 0` et compte sur l'animation pour se
révéler : neutraliser les animations sans désactiver AOS en JS rendrait des dizaines de blocs
invisibles, précisément pour le public qu'on cherche à servir. Le lot complet (AOS,
`animateHistoryBrowsing`, `Typewriter`, `path_controller`, tilt 3D, 54 `transition`) demande une QA
visuelle sur tout le site → lot séparé.

Le découpage complet en 4 PR et le backlog sont dans `work/a11y-rgaa-audit.md` § 1 et § 9.

## Tests

Aucune couverture automatisée n'existe (`tests/` = `bootstrap.php`, `make test` = build statique).
Plan de test clavier manuel détaillé dans `work/a11y-rgaa-audit.md` § 7 : 7 pages, Chrome + Firefox +
Safari, un passage VoiceOver, un contrôle que `aria-busy` ne reste pas collé sur `<html>`, et une
vérification qu'aucun bloc AOS ne disparaît en mouvement réduit.
```

Créer la PR avec `gh pr create --assignee @me`. Aucun numéro d'issue identifiable dans le contexte →
pas de mot-clé de fermeture (à ajouter si un ticket existe).

---

## 9. Hors périmètre du PR A

### Les 3 autres PR du chantier

**PR B — Noms accessibles** (P1-3). Parallélisable, aucun fichier commun avec A.
`🐍` du footer (`templates/partials/footer.html.twig:82`) à passer en `<button>` · 23 des 80
`<i class="icon">` sans `aria-hidden` · émojis d'intérêts (`templates/team/member.html.twig:120-126`) ·
`alt` = slug dans `src/Emoji/ElaomojiParser.php:46-53` · `aria-hidden` sur un `<li>` contenant un lien
focusable (`templates/site/elaomojis.html.twig:34-39`) · `alt` = chemin de fichier
(`templates/glossary/term_list.html.twig:7,12`) · `alt=""` sur le seul contenu d'une colonne
(`templates/site/services/hosting.html.twig:43`) · puces du carrousel sans nom
(`assets/js/controllers/carousel_controller.js:72-76`).

**PR C — Lisibilité** (P1-5 + P1-4 + P1-8). Après A. Demande une relecture visuelle page par page.
Les 10 contrastes corrigeables par substitution de token existant (dont les 1,98:1 de
`_category-switch.scss:85,88-92` et `_admonition.scss:40`) · le soulignement des liens mutualisé en
mixin sur les 4 conteneurs rédactionnels + `.contact__infos a` et `.link--brand` · la classe
`banner-se rvices__text` avec une espace (`templates/site/services/application.html.twig:29`) et les
hauteurs fixes de `_miniature-highlight.scss:74-77,110-111`.
**Rappel C1 :** P1-4 et P1-5 modifient tous deux `_contact.scss:124-126` — inséparables.

**PR D — Plan du site** (P1-9 + une part de P2-5). Indépendant.
`SiteController::sitemap()` + template énumérant les contenus via `ContentManagerInterface` + lien
footer · `<nav aria-label="Pagination">` et `aria-current="page"` sur
`templates/blog/pagination.html.twig:13,21`.

### Backlog — pas encore des PR, décisions requises d'abord

- **P1-7 résiduel** — `prefers-reduced-motion` complet : `AOS.init` (`assets/js/app.js:32-43`),
  `animateHistoryBrowsing` (`templates/base.html.twig:95`), 54 `transition` / 8 `animation` /
  6 `@keyframes`, `Typewriter.js`, `path_controller.js`, tilt 3D. **Prérequis : décider comment AOS est
  désactivé sans laisser de bloc à `opacity: 0`.** Cf. D5.
- **P2-1** — refonte des tokens de contraste de la palette. **Prérequis : atelier design**, arbitrage
  entre assombrir les couleurs de marque (option A) ou introduire `$color-brand-text` /
  `$color-accent-text` réservés au petit texte (option B, recommandée). À lancer **après** que le PR C
  ait montré ce que « conforme » donne visuellement.
- **P2-2** — Text Spacing (WCAG 1.4.12). **Prérequis : exécuter le bookmarklet**, 30 minutes, et
  remplir la ligne 138 de la check-list restée vide. Statut actuel : non vérifié, ni C ni NC.
- **P2-3** — GIF animés contrôlables. **Prérequis : décision éditoriale** — réduire les 34 GIF avant
  d'écrire le processeur Stenope (`SkippedTypes.php:13`, `ResizeImagesContentProcessor.php:46-48`).
- **P2-4** — réflow 200 % structurel : `_banner-services.scss:30-34`, `pages/_page-services.scss:95-98`,
  `_miniature-inline.scss:17-18`. Même passe de fichiers que P2-2.
- **P2-5 résiduel** — `role="tablist"` de `templates/site/carriere.html.twig:196-212` (4 onglets en
  `aria-selected="true"`, `<div role="tab">` non focusables) : retirer les rôles plutôt qu'implémenter
  le pattern ARIA. Slides de carrousel focusables hors écran.
- **P3-1 à P3-5** — reportés / hors budget a11y : pagination des études de cas (prémisse infondée,
  il n'y a pas de scroll infini), avertissement « nouvelle fenêtre » (AAA, hors RGAA), mesure PEAT
  (remplacée par une règle éditoriale), moteur de recherche (sujet produit), `alt` redondants.

### Tickets d'outillage à ouvrir

- **Garde-fou stylelint** interdisant `outline: none|0` sans indicateur de substitution — c'est ce qui
  empêchera la dette corrigée par le PR A de se reformer.
- Cible **`make lint.a11y`** (axe-core ou pa11y sur le build statique) : sur l'état actuel du dépôt,
  un seul passage remonterait les 3 `<select>` sans `<label>` de `templates/site/social.html.twig` et
  les 6 `<iframe>` sans `title` de `content/`.
- Tests **Playwright** de mesure : reflow 320 px, zoom 200 %, injection du CSS Text Spacing.
- Les **~51 règles `&:focus` qui ne changent qu'une couleur** (échec 1.4.1) : non retouchées par le
  PR A, aucune ne pose d'`outline` donc aucune ne combat le nouvel anneau. Traitement en PR C.
- Les **2 pages `swupContainers = []`** (`services/optimiser`, `services/ia`) : configuration Swup
  cassée (`containers` retombe sur `#swup`, sélecteur inexistant), indépendante de l'accessibilité mais
  susceptible de bloquer `aria-busy` sur `<html>`.
