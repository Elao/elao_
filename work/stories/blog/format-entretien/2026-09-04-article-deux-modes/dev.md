---
id: STOR-001
epic: blog
feature: format-entretien
slug: article-deux-modes
title: Un article de blog porte deux modes de lecture, récit et entretien, avec bascule
created: 2026-09-04
status: Done
---

# Journal de développement

## Progression

| Tâche | Statut | Date |
|-------|--------|------|
| 1. Modèle — type d'article et modes de lecture | Terminé | 2026-09-04 |
| 2. Découpe des modes — `ArticleReadingModesProcessor` | Terminé | 2026-09-04 |
| 3. Blocs d'entretien — `HtmlInterviewBlocksProcessor` | Terminé | 2026-09-04 |
| 4. Sommaires par mode — définitions de services | Terminé | 2026-09-04 |
| 5. Gabarit — bloc d'annonce, contrôles, sections de mode | Terminé | 2026-09-04 |
| 6. Contrôleur de bascule — `blog/reading_modes` | Terminé | 2026-09-04 |
| 7. Styles — annonce et contrôles | Terminé | 2026-09-04 |
| 8. Styles — blocs d'entretien | Terminé | 2026-09-04 |
| 9. Article de démonstration | Terminé | 2026-09-04 |
| 10. Contrôles navigateur | Terminé | 2026-09-04 |
| 11. Contrôles qualité — `make lint` et build de contenu | Terminé | 2026-09-04 |

## Journal

<!-- Les entrées sont ajoutées au fur et à mesure du développement. -->

### 2026-09-04 : Modèle — type d'article et modes de lecture

**Statut** : Terminé

**Actions réalisées** :

- `Article::$type` reçoit `self::TYPE_POST` par défaut, avec les constantes `TYPE_POST` et `TYPE_INTERVIEW`, et l'accesseur `isInterview()`.
- Constantes de slugs de modes (`READING_MODE_NARRATIVE` = `narratif`, `READING_MODE_INTERVIEW` = `entretien`) et libellés par défaut privés (« Récit », « Entretien intégral »).
- Propriété `readingModes` pour l'en-tête, avec une forme de tableau documentée pour PHPStan.
- Quatre propriétés remplies par les processors : `narrativeContent`, `narrativeTableOfContent`, `interviewContent`, `interviewTableOfContent`.
- Objet de valeur `App\Model\Article\ReadingMode` et accesseur `getReadingModes(): list<ReadingMode>`, qui n'expose que les modes réellement présents et marque le premier comme mode par défaut.
- Accesseur `getReadingModesIntro()` pour la surcharge du texte du bloc d'annonce.

**Fichiers modifiés** :

- `src/Model/Article.php`
- `src/Model/Article/ReadingMode.php` (nouveau)

**Notes** :

- `type: "post"` est déclaré dans les 118 articles et n'était lu nulle part : la valeur par défaut ne change donc rien à l'existant, et le champ devient le déclencheur du format.
- Vérifié avant d'écrire : `TableOfContentProcessor` sort si **l'une des deux** propriétés (contenu, sommaire) manque de `$data`. C'est le processor de découpe qui doit poser `narrativeTableOfContent` / `interviewTableOfContent` à un entier (tâche 2).
- `make lint.phpstan` : aucune erreur. `php-cs-fixer` a voulu corriger au passage `src/Model/JobContractType.php`, sans rapport avec la story — correction annulée.
- Arbitrage validé avec ogi en début de session : pas de suite PHPUnit (le dépôt n'en a aucune, `make test` se réduit au build de contenu). Les scénarios `[unit]` du brief sont couverts par observation sur l'article de démonstration.

### 2026-09-04 : Découpe des modes — `ArticleReadingModesProcessor`

**Statut** : Terminé

**Actions réalisées** :

- Processor créé sur le modèle de `HtmlTablesProcessor`, enregistré en priorité **-95**.
- Sortie immédiate hors `App\Model\Article` et hors `type: interview`.
- Découpe des enfants de `<body>` sur les commentaires `<!-- mode: <slug> -->`, retirés au passage ; ce qui précède le premier commentaire reste la zone commune.
- Chaque mode est **déplacé** dans un conteneur temporaire enfant du corps : le déplacement élague le DOM du crawler `content` (obligatoire, cf. `saveAll()`) et le conteneur borne les recherches de titres et de liens.
- Préfixe de mode sur les `id` de titres `h1`–`h6`, puis réécriture des `href="#…"`.
- Report de la profondeur de sommaire sur `narrativeTableOfContent` / `interviewTableOfContent`, et neutralisation de `tableOfContent` à `false`.
- Article de démonstration créé en version minimale pour exercer la chaîne, et ajouté à `$ignored` du `SampleRemovalProvider`. Sa version complète est la tâche 9.

**Fichiers modifiés** :

- `src/Stenope/Processor/ArticleReadingModesProcessor.php` (nouveau)
- `config/services.yaml`
- `content/blog/styleguide/entretien-deux-modes.md` (nouveau, minimal)

**Notes** :

- **Réécriture des liens, au-delà du plan.** Le plan ne prévoyait la réécriture qu'à l'intérieur d'un même fragment. Un lien de la zone commune ou du récit vers une section de l'entretien serait alors resté cassé. Deux tables sont donc construites : celle du mode, prioritaire, et une table partagée des `id` qu'**un seul** mode porte. Un `id` présent dans les deux modes est ambigu hors de ceux-ci : le lien est laissé tel quel plutôt que d'en désigner un arbitrairement.
- Un slug de mode inconnu lève une `LogicException` : une coquille dans le commentaire déverserait sinon silencieusement tout l'entretien dans la zone commune.
- Vérifié sur l'article de démonstration via `debug:stenope:content` : `content` ne porte plus que la zone commune, `#pour-finir` (présent dans le seul entretien) est réécrit en `#entretien-pour-finir` depuis le récit, `#une-section-homonyme` reste dans son mode, et `tableOfContent` est bien retombé à `null`.

### 2026-09-04 : Blocs d'entretien — `HtmlInterviewBlocksProcessor`

**Statut** : Terminé

**Actions réalisées** :

- Processor créé, enregistré en priorité **-96**, sur la seule propriété `interviewContent`.
- Chaque `blockquote` est re-découpé : les paragraphes de tête sans nom deviennent `<div class="interview-context">`, chaque nom rencontré ouvre une `<div class="interview-turn interview-turn--N">` qui absorbe les paragraphes suivants non attribués.
- Deux écritures du nom reconnues et normalisées vers la même sortie : `<strong>Nom :</strong>` en tête de paragraphe (ouvre une prise de parole), et `<cite>Nom</cite>` en fin de paragraphe (attribue ce qui précède et clôt).
- Le nom sort en `<p class="interview-turn__speaker">`, texte simple : ni `cite`, ni `blockquote`. Le mode entretien ne conserve donc aucun `blockquote` en sortie.
- Indice de couleur attribué par ordre d'apparition dans l'article et cyclé sur `SPEAKER_COLORS = 4`, pour que la classe reste toujours dans la palette.

**Fichiers modifiés** :

- `src/Stenope/Processor/HtmlInterviewBlocksProcessor.php` (nouveau)
- `config/services.yaml`

**Notes** :

- Formes de sortie de Parsedown vérifiées sur pièces avant d'écrire : `**Eva :** …` donne `<p><strong>Eva :</strong> …</p>`, et `<cite>Maxime</cite>` atterrit **dans** le paragraphe précédent, sans `<br>`. D'où deux détections distinctes, l'une en tête, l'autre en fin.
- La forme `**Nom** :` (deux-points hors du gras) est acceptée aussi. Le deux-points, dedans ou juste après, est le signal retenu : sans lui, un paragraphe qui commence simplement par du gras reste du contexte — vérifié.
- Le tiret décoratif de `<cite>- Jane Doe</cite>`, montré par le guide de style, est retiré du nom.
- **Écart au plan, sans conséquence fonctionnelle** : le découpage a d'abord été écrit avec une closure `flush()` capturant `$speaker` par référence. PHPStan niveau max ne suit pas ce genre de capture et signalait deux comparaisons « toujours vraies ». Réécrit en construction linéaire de groupes, plus lisible au passage.
- Vérifié sur un banc d'essai couvrant six cas : contexte multi-paragraphes, prise de parole simple, prise de parole avec suite non attribuée, forme `<cite>`, forme `**Nom** :`, et gras sans nom. Un même intervenant garde sa couleur quelle que soit l'écriture employée.

### 2026-09-04 : Sommaires par mode — définitions de services

**Statut** : Terminé

**Actions réalisées** :

- Deux définitions de `Stenope\Bundle\Processor\TableOfContentProcessor` en priorité **-100**, `table_of_content_processor.article.narrative` et `…interview`, chacune sur son couple de propriétés.

**Fichiers modifiés** :

- `config/services.yaml`

**Notes** :

- `autoconfigure: false` sur les deux définitions : `StenopeExtension` autoconfigure `ProcessorInterface` avec le tag `stenope.processor`, et le tag explicite ajouté ici s'y serait superposé. La liste compilée des processors confirme qu'aucun n'apparaît deux fois.
- Ordre compilé vérifié dans le conteneur : `…ArticleReadingModesProcessor` (20), `…HtmlInterviewBlocksProcessor` (21), puis les `TableOfContentProcessor` (22+). Conforme au plan.
- Vérifié sur l'article de démonstration : `narrativeTableOfContent` porte la seule section du récit, `interviewTableOfContent` les deux sections de l'entretien, et `tableOfContent` reste `null`.

### 2026-09-04 : Gabarit — bloc d'annonce, contrôles, sections de mode

**Statut** : Terminé

**Actions réalisées** :

- Trois partiels créés : `_reading-modes.html.twig` (bloc d'annonce), `_reading-modes-controls.html.twig` (groupe de boutons, rendu trois fois) et `_table-of-content.html.twig` (sommaire, extrait du gabarit pour être rendu une fois par mode).
- `templates/blog/article.html.twig` : contrôleur Stimulus `blog/reading_modes` posé sur le conteneur de l'article, uniquement au format entretien ; bloc d'annonce après la bannière ; un sommaire par mode à l'emplacement d'origine du sommaire ; une `<section id="<slug>">` par mode après la zone commune, avec rappel des contrôles en fin.
- Boutons `aria-pressed` dans un groupe `role="group"` nommé, plutôt qu'un `tablist` — les sommaires restent hors des sections de mode, un `tablist` mentirait sur la structure.
- Titre de mode en `h2` dans chaque section et chaque sommaire, référencé par `aria-labelledby`.

**Fichiers modifiés** :

- `templates/blog/article.html.twig`
- `templates/blog/_reading-modes.html.twig` (nouveau)
- `templates/blog/_reading-modes-controls.html.twig` (nouveau)
- `templates/blog/_table-of-content.html.twig` (nouveau)
- `src/Model/Article.php`

**Notes** :

- **Piège rencontré, corrigé dans le modèle.** `article.readingModes` en Twig résolvait la propriété publique de front-matter, pas l'accesseur `getReadingModes()` — l'accès à une propriété l'emporte sur la méthode. Erreur 500 immédiate. La propriété est passée **privée**, renseignée par un `setReadingModes()` que le dénormaliseur Stenope utilise sans configuration supplémentaire. `article.readingModes` désigne désormais sans ambiguïté la liste d'objets `ReadingMode`, et le piège ne peut plus se reproduire.
- **Écart au plan, en faveur de l'accessibilité.** Les boutons sont rendus `disabled` et le contrôleur Stimulus lève l'attribut à la connexion. Le brief prévoyait des contrôles « affichés, sans effet » sans script ; un bouton visiblement inerte vaut mieux qu'un bouton qui ne répond pas.
- Le titre de mode est rendu en permanence : masqué à l'écran quand les scripts tournent — les contrôles nomment déjà le mode — mais toujours restitué aux technologies d'assistance, et visible sans script, où les deux modes se lisent l'un après l'autre (critère 15).
- L'`id` de section porte le slug du mode : l'adresse `#entretien` désigne alors un élément réel, et le contrôleur y lit le mode à activer.
- Rendu vérifié sur pièces : deux sommaires, deux sections, trois groupes de contrôles, `is-active` posé côté serveur sur le mode par défaut, `id` de titres préfixés et liens internes cohérents.

### 2026-09-04 : Contrôleur de bascule — `blog/reading_modes`

**Statut** : Terminé

**Actions réalisées** :

- `assets/js/controllers/blog/reading_modes_controller.js` créé, chargé sans configuration par le `require.context` récursif de `bootstrap.js`, et posé par `stimulus_controller('blog/reading_modes')` sur le modèle de `ia/brief/iframe`.
- Cibles `mode` (sommaires et sections), `panel` (les seules sections) et `control` ; valeur `defaultMode`.
- À la connexion : les contrôles sont dé-`disabled`, le mode est résolu depuis le fragment d'URL, et l'écouteur `popstate` est posé.
- À la sélection : bascule, puis `pushState` — chaque bascule empile une entrée, le retour arrière ramène au mode précédent.
- Lien profond : le mode est activé, puis la section visée est amenée à l'écran, l'ancre native ayant échoué sur un élément masqué.
- `data-action` ajouté sur les boutons, `tabindex="-1"` et cible `panel` sur les sections.

**Fichiers modifiés** :

- `assets/js/controllers/blog/reading_modes_controller.js` (nouveau)
- `templates/blog/_reading-modes-controls.html.twig`
- `templates/blog/article.html.twig`

**Notes** :

- **Swup vérifié sur pièces avant d'écrire, pas supposé.** Swup 3.1.1 ignore un `popstate` dont l'état ne porte pas `source: 'swup'` — c'est son option `skipPopStateHandling` par défaut — et son gestionnaire sort de toute façon quand `pathname + search` est inchangé. Nos entrées d'historique, qui ne diffèrent que par le fragment, ne déclenchent donc aucune navigation Swup. Rien de particulier à prévoir côté transitions : Stimulus reconnecte le contrôleur sur le DOM remplacé, et l'écouteur `popstate` suit les connexions.
- **Défilement à la bascule, non prévu au plan.** Basculer depuis les contrôles de fin laissait le lecteur à un décalage arbitraire dans un document de longueur différente. Le focus est désormais porté sur la section activée — ce qui annonce le mode aux technologies d'assistance —, et le défilement n'a lieu que si cette section est déjà passée au-dessus de la fenêtre. Depuis les contrôles du haut, la page ne bouge donc pas et le bloc d'annonce reste visible.

### 2026-09-04 : Styles — annonce, contrôles et blocs d'entretien

**Statut** : Terminé

**Actions réalisées** :

- `assets/scss/components/_reading-modes.scss` : bloc d'annonce sur fond `$color-secondary`, contrôles en boutons segmentés `aria-pressed`, rappel des contrôles en fin de mode sur un filet, et les deux règles de bascule d'affichage.
- `assets/scss/components/_interview.scss` : filet vertical de 2 px à la couleur de l'intervenant, nom en petites capitales dans la même couleur, remise en contexte sur un filet de 1 px neutre et un texte légèrement réduit.
- `$color-brand-dark: #e60002` ajouté aux variables, documenté avec son ratio.
- Les deux composants importés dans `style.scss`.

**Fichiers modifiés** :

- `assets/scss/components/_reading-modes.scss` (nouveau)
- `assets/scss/components/_interview.scss` (nouveau)
- `assets/scss/base/_variables.scss`
- `assets/scss/style.scss`

**Notes** :

- **Palette d'intervenants : `$color-info` retenu à la place du `#007695` du plan.** La charte fournissait `#007695`, absent des variables ; `$color-info` vaut `#1e7695`, mesuré à 5,15:1 sur blanc contre 5,22:1 pour l'autre. L'écart est imperceptible, et ajouter une variable quasi jumelle à la palette coûterait plus qu'il ne rapporte. Les trois autres tons sont ceux du plan, dont le dérivé `#e60002` du rouge de marque, à 4,81:1.
- Le nom d'intervenant est écrit petit : les quatre tons devaient franchir 4,5:1, pas 3:1. Vérifié : 9,63 / 11,86 / 5,15 / 4,81.
- **Spécificité.** `.article-content p { margin: 0 0 30px }` l'emporte sur une classe simple. Les règles de marge des blocs d'entretien passent donc par `.interview-turn > …`, à deux classes.
- `outline: none` sur `.reading-mode:focus` : le focus y est porté par le contrôleur pour annoncer le mode, ce n'est pas une commande, et l'anneau global n'aurait rien à y signaler. `:focus-visible` reste intact partout ailleurs.
- Compilation vérifiée : le serveur webpack a bien repris les deux composants.

### 2026-09-04 : Article de démonstration

**Statut** : Terminé

**Actions réalisées** :

- `content/blog/styleguide/entretien-deux-modes.md` rédigé, dérivé des ébauches de l'entretien de Martin Dufresne : récit quasi complet (8 sections, 2 sous-sections), entretien allégé (6 sections).
- Cas de rendu couverts : zone commune, section enchaînant plusieurs échanges, section sans remise en contexte, section sans question (« Pour suivre Martin »), deux intervieweurs, deux sections homonymes (« Pour finir » dans les deux modes), les deux écritures du nom, et deux notes appelées depuis chacun des deux modes.
- L'en-tête documente les clés de `readingModes` en commentaire, et n'en renseigne qu'une partie à dessein.

**Fichiers modifiés** :

- `content/blog/styleguide/entretien-deux-modes.md`

**Notes** :

- **`readingTime` n'est renseigné que sur le récit.** L'article a pour objet de donner à voir tous les rendus : les deux états du contrôle, avec durée et sans, sont ainsi visibles côte à côte. Le critère 14 est vérifié d'un coup d'œil.
- **Titres de section en `h2`, pas en `h1`.** Les ébauches emploient `#` ; le titre de l'article porte déjà le `h1` de la page, et un second `h1` dans le corps serait une régression sémantique. Sections en `h2`, sous-sections en `h3`, `tableOfContent: 3` pour que les deux niveaux figurent au sommaire.
- Le bloc d'annonce garde le texte par défaut du gabarit : c'est celui que verront la plupart des articles, autant le montrer. La clé `intro` reste documentée dans l'en-tête.
- Le bloc de code de la zone commune contient les commentaires de mode : Parsedown les échappe à l'intérieur d'une clôture, ils ne sont donc pas pris pour des séparateurs. Vérifié.

### 2026-09-04 : Contrôles navigateur

**Statut** : Terminé

**Actions réalisées** :

Les quatorze contrôles du plan ont été déroulés sur `http://localhost:35080/blog/styleguide/entretien-deux-modes`, en 1280×900 et en 390×844. Captures dans `.ignore/2026-09-04-article-deux-modes/` (hors git).

| Contrôle | Résultat |
|---|---|
| Arrivée sans fragment : récit affiché, entretien absent du rendu | ✅ `display: none` sur les deux blocs du mode inactif |
| Bascule : corps **et** sommaire changent, l'adresse suit | ✅ `#entretien`, une entrée d'historique de plus |
| Retour arrière : ramène au mode précédent | ✅ |
| `#entretien` ouvre l'entretien | ✅ |
| `#entretien-pour-finir` ouvre l'entretien **et** amène à la section | ✅ section à 130 px du haut de la fenêtre |
| Prises de parole : nom affiché, deux couleurs, chacune constante | ✅ Eva en `--1`, Maxime en `--2`, y compris via `<cite>` |
| Les deux écritures du nom produisent le même rendu | ✅ |
| Remise en contexte : distincte, sans nom | ✅ filet neutre, texte adouci |
| Section à plusieurs échanges, sans contexte, sans question | ✅ |
| Durée affichée si renseignée, libellé seul sinon | ✅ les deux états côte à côte |
| Contrôles au clavier, état annoncé | ✅ `Entrée` bascule ; arbre d'accessibilité : `button … pressed` dans un `group "Mode de lecture"` |
| Scripts désactivés : deux modes et deux sommaires lisibles, chacun sous son titre | ✅ (méthode ci-dessous) |
| Navigation depuis le listing (transition Swup) : la bascule fonctionne | ✅ contrôleur reconnecté, bascule et retour arrière opérants |
| Aucun clignotement au chargement ni après transition | ✅ |
| Article ordinaire inchangé | ✅ sommaire au HTML identique, `data-aos` compris ; aucun marquage de mode |

**Correctifs issus de ces contrôles** :

- **Bloc d'annonce et sommaire se confondaient** : tous deux sur `$color-secondary`, sans séparation, ils formaient un seul aplat. Marge basse ajoutée sur `.reading-modes`.
- **Filet des remises en contexte en `$color-tertiary`** : le corail se lisait comme une troisième couleur d'intervenant. Passé à un gris neutre dérivé de `$color-text`, texte adouci à 80 % — 9,55:1 sur blanc.
- **`data-aos="fade-up"` perdu sur le sommaire des articles ordinaires** en extrayant le partiel. Rétabli, et **volontairement non repris** sur les sommaires de mode : masqué au chargement, un tel sommaire n'obtiendrait jamais sa classe `aos-animate` et resterait invisible une fois révélé.
- **Bascule depuis les contrôles de fin** : le lecteur atterrissait au milieu du nouveau mode. `focus()` seul ne suffit pas — sur une section plus haute que la fenêtre, le navigateur s'arrête où bon lui semble. Le défilement est désormais explicite vers le début de la section, et uniquement quand elle est passée au-dessus de la fenêtre. Depuis les contrôles du haut, la page ne bouge pas (985 → 983 px mesurés).

**Fichiers modifiés** :

- `assets/scss/components/_reading-modes.scss`
- `assets/scss/components/_interview.scss`
- `assets/js/controllers/blog/reading_modes_controller.js`
- `templates/blog/_table-of-content.html.twig`
- `templates/blog/article.html.twig`

**Notes** :

- **Méthode du test sans script.** En développement, le serveur webpack injecte la CSS par JavaScript : scripts coupés, la page perdrait aussi ses styles, et le test ne dirait rien du rendu réel. L'état servi a donc été reproduit à l'identique — classe `no-js` rétablie sur `<html>`, boutons remis à `disabled` — la feuille de style restant chargée. C'est exactement ce que rend la production sans script. Les quatre blocs passent alors en `display: block` et les quatre titres redeviennent visibles.
- **Anti-clignotement, confirmé sur pièces.** `base.html.twig` retire `no-js` dans le `<head>`, avant le bloc `stylesheets`. La règle `html:not(.no-js) .reading-mode:not(.is-active)` masque donc le mode inactif dès le premier rendu, sans script dédié. Après une transition Swup, le HTML entrant porte déjà `is-active` côté serveur : mesuré `entretien:none` immédiatement après la navigation.
- **Swup, vérifié en conditions réelles** en plus de la lecture du code : la navigation depuis `/blog/styleguide` reconnecte le contrôleur, la bascule et le retour arrière fonctionnent, et aucun `popstate` de mode ne déclenche de navigation.
- **Contraste des contrôles inertes** : `$color-tertiary` sur blanc ne fait que 2,76:1, sous le seuil de 3:1 de WCAG 1.4.11. Sans objet ici — le critère exempte explicitement les composants désactivés — et le texte, lui, tient 6,18:1 sur le bouton actif comme 17:1 sur l'inactif.
- Aucun débordement horizontal en 390 px : les contrôles s'empilent.

### 2026-09-04 : Contrôles qualité

**Statut** : Terminé

**Actions réalisées** :

- `make lint` : php-cs-fixer (0 fichier corrigé sur 60), PHPStan niveau max (aucune erreur), Twig (56 fichiers valides), YAML (22 fichiers valides), ESLint, `lint:container`, `composer validate`. Tout passe.
- `make build.content.without-images` : 615 pages construites.
- `INCLUDE_SAMPLES=0 make build.content.without-images` — la valeur employée par le workflow de déploiement : 608 pages, et `build/blog/styleguide/` absent du résultat.

**Notes** :

- L'exclusion de production a été vérifiée avec la valeur réelle du CI (`.github/workflows/deploy.yaml`), et non seulement par lecture de `config/services.yaml` : en local `.env` porte `INCLUDE_SAMPLES=1`, un build par défaut aurait donc conclu à tort.
- Deux erreurs `Unsupported language "tree"` remontent du build : préexistantes, sans rapport avec la story.

### 2026-09-04 : Trois vérifications complémentaires

**Statut** : Terminé

**Actions réalisées** :

Trois angles morts des contrôles de la tâche 10, relevés à la relecture puis traités.

- **Appels de notes répétés d'un mode à l'autre.** Le critère 17 avait été vérifié sur la liste (deux notes, une seule liste), pas sur les appels. `HtmlFootnotesProcessor` s'exécute en -10, donc sur le contenu entier avant la découpe : les deux notes étaient appelées depuis les deux modes. Vérifié : le processor ne pose l'`id="footnote-ref-N"` que sur le **premier** appel, il n'y a donc pas d'identifiant en double. Mais le lien de retour de la liste vise ce premier appel — situé dans le récit, masqué pour qui lit l'entretien. L'article de démonstration n'appelle désormais chaque note que depuis un seul mode : le lien de retour aboutit toujours pour la note dont on vient, et le défaut acté au brief — une note appelée dans un seul mode reste listée dans les deux — devient visible plutôt que théorique. Reste inerte le lien de retour d'une note dont l'appel est dans l'autre mode ; c'est le corollaire direct de la liste unique, à signaler à la revue.
- **Citations classiques dans le récit.** Le mode entretien ne conserve aucun `blockquote`, mais le récit, lui, doit garder l'exergue habituelle — et la nouvelle `<section>` change ce sur quoi porte le `nth-of-type(even)` de `_blockquote.scss`. Deux citations ajoutées au récit : la première sort en variante simple, la seconde sur fond `$color-primary`. Les deux variantes génériques fonctionnent dans le conteneur de mode, sans débordement.
- **Citations adjacentes séparées par une ligne vide.** Le banc d'essai de la tâche 3 l'avait prouvé, l'article l'affirmait en prose sans le montrer. La section « Ça ne dégage pas de temps » écrit désormais le contexte et la question en deux citations distinctes : rendu obtenu `interview-context` puis `interview-turn--1`, identique à l'écriture collée.

**Vérification menée en parallèle** : `article.content` ne porte plus que la zone commune pour un article au format entretien. Recherche des autres consommateurs dans `templates/` — le flux RSS emploie `article.description`, jamais le contenu, et `job`/`term`/`caseStudy` sont d'autres modèles. Aucun autre point d'usage.

**Fichiers modifiés** :

- `content/blog/styleguide/entretien-deux-modes.md`

**Notes** :

- `make lint` et le build de contenu repassés après ces modifications : 615 pages, aucune erreur.

### 2026-09-04 : Retours de revue visuelle — ogi

**Statut** : Terminé

**Actions réalisées** :

- **Texte par défaut du bloc d'annonce réécrit.** « Cet article se lit de deux façons » pouvait s'entendre comme « cet article est ambigu » ; remplacé par « Cet article existe en deux modes de lecture ». « Réorganisé par thème » devient « en un format narratif ». La phrase « À vous de choisir — et vous pouvez changer d'avis en cours de route » est retirée : les contrôles juste dessous le disent d'eux-mêmes.
- **Bloc d'annonce plus discret en desktop.** Il occupait trop de hauteur au-dessus d'un article déjà long. Interlignage et graisses inchangés, échelle réduite : rembourrage 35/40 → 26/32 px, chapô 18 → 16 px, libellé de contrôle 18 → 16 px, durée 14 → 13 px, rembourrage des boutons 15/22 → 11/18 px, gouttière 15 → 12 px. Le bloc passe de 246 à 190 px de haut, les boutons de 92 à 68 px.
- **Durée de lecture en ligne sur petit écran.** Sous `$screen-xs`, le contrôle passe en `flex-direction: row`, la durée est calée à droite et le libellé prend la place restante — c'est lui qui passe sur deux lignes s'il le faut, pas la durée. Alignement sur la ligne de base plutôt que sur le haut, pour que la durée suive la première ligne du libellé. Les boutons passent de 76 à 48 px de haut.
- **Filets des blocs d'entretien à 3 px**, prise de parole comme remise en contexte. Rembourrage gauche ajusté à 19 px de part et d'autre : le texte reste aligné au même endroit qu'avant (22 px du bord). La distinction entre les deux blocs repose désormais sur la seule couleur — saturée pour un intervenant, gris neutre pour la rédaction — et tient toujours.
- **Durée de lecture ajoutée à l'entretien** dans l'article de démonstration : 33 min, factice, contre 11 pour le récit.

**Fichiers modifiés** :

- `templates/blog/_reading-modes.html.twig`
- `assets/scss/components/_reading-modes.scss`
- `assets/scss/components/_interview.scss`
- `content/blog/styleguide/entretien-deux-modes.md`

**Notes** :

- **Contrepartie de la durée sur les deux modes** : l'article de démonstration ne montre plus le rendu d'un contrôle sans durée (critère 14, seconde moitié). Le cas reste documenté dans l'en-tête de l'article et le gabarit le gère toujours — il n'est simplement plus donné à voir. À rétablir si la démonstration exhaustive prime sur le réalisme de l'exemple.
- `make lint` et le build de contenu repassés : aucune erreur, 615 pages.

### 2026-09-04 : Revue de qualité — réutilisation, simplification, efficacité, altitude

**Statut** : Terminé

**Actions réalisées** :

Quatre revues croisées du diff `main...HEAD` restreint au code. Onze correctifs appliqués, huit constats écartés. Bilan : 145 lignes retirées pour 113 ajoutées, à comportement rigoureusement identique.

*Réutilisation*

- La technique de masquage visuel du titre de mode était une copie mot pour mot de `.screen-reader` (`base/_utilities.scss`). Remplacée par `@extend .screen-reader`, idiome déjà employé par `_brick-*.scss` et `_titles.scss`. Le cas de `_skip-link.scss`, qui décline explicitement cet utilitaire, ne s'applique pas ici : il lui manque une règle de révélation au focus, dont un titre n'a pas besoin.
- La sélection des titres passe par `Crawler::filter('h1[id], …')` au lieu d'un XPath `self::h1 or self::h2 or …`. L'XPath reste là où le CSS ne peut rien : `descendant-or-self::a`, qui doit accepter un nœud racine.

*Simplification*

- `splitTableOfContent()` supprimé : les propriétés de contenu et de sommaire de chaque mode sont posées dans la même boucle. Une seconde traversée de `$wrappers` et un `array_keys()` en moins.
- `SPEAKER_PATTERN` supprimé : sur un texte déjà `trim()`é dont on sait si le deux-points est présent, `rtrim(substr($text, 0, -1))` dit la même chose qu'une expression régulière à groupe nommé.
- Prédicat de nœud blanc écrit trois fois → une méthode `isBlank()`. `lastMeaningfulChild()` remonte par `previousSibling` au lieu de matérialiser puis renverser la liste des enfants — symétrique de `firstMeaningfulChild()`, et deux allocations en moins par élément.
- Deux blocs de boîte identiques dans `_interview.scss` fusionnés en un sélecteur commun. Les déclarations `$color-primary` de repli étaient mortes — le processor émet toujours une variante `--N` — et dupliquaient la variante `--1` ; remplacées par `currentColor`.
- Le gabarit avait deux `{% if readingModes is not empty %}` adjacents : fusionnés.
- `article.isInterview ? article.readingModes : []` → `article.readingModes`. Le garde-fou était le troisième du même test : `getReadingModes()` renvoie déjà `[]` hors format entretien, et le processor a déjà refusé de découper en amont.

*Efficacité*

- La zone commune n'est plus collectée par `split()` : les conteneurs de mode sont détachés d'abord, et ce qui reste du corps **est** la zone commune. Une seule évaluation XPath pour ses liens, au lieu d'une par nœud de premier niveau — une vingtaine sur l'article de démonstration, dont plusieurs sur des nœuds de texte qui ne peuvent contenir aucun lien.
- Le contrôleur ne lit plus la géométrie après avoir basculé les classes, ce qui forçait un recalcul de mise en page complet sur un document portant les deux corps d'article. La question posée n'était d'ailleurs pas géométrique mais « le clic vient-il des contrôles de fin ? » : `event.currentTarget.closest('.reading-mode')` y répond directement.
- Le fragment d'URL était décodé deux fois par bascule ; un accesseur `fragment` le calcule une fois. `resolve()` parcourait `controlTargets`, qui compte six entrées pour deux modes, au lieu de `panelTargets`.
- Le test du type d'article passe avant `is_a()` : les autres types de contenu (membres, études de cas, offres, termes) ne portent aucune clé `type` et sortent donc à la première condition.

*Altitude*

- **La convention de préfixe `<mode>-<id>` n'est plus écrite deux fois, en deux langages.** Le contrôleur reconstruisait avec `fragment.startsWith(\`${mode}-\`)` ce que le processor pose côté PHP ; il cherche désormais quel mode **contient** l'élément visé. Plus robuste — un identifiant de titre qui contiendrait lui-même un slug de mode suivi d'un tiret résolvait silencieusement vers le mauvais mode — et un lien vers n'importe quel autre élément d'un mode aboutit maintenant.
- **Les deux `TableOfContentProcessor` par mode héritent désormais de la définition du bundle** (`parent:`) au lieu de repartir de zéro. Ce n'était pas une divergence latente mais **réelle** : `StenopeExtension` pose `min_depth: 2` sur la définition du bundle, et les deux instances prenaient les valeurs par défaut du constructeur, soit `1`. Sans effet visible sur l'article de démonstration, dont les sections sont en `h2`, mais un article employant `h1` aurait vu son sommaire de mode diverger de celui du reste du site.
- `apply(mode, { reveal, focus })` encodait trois raisons d'appel mutuellement exclusives en deux booléens, dont une combinaison impossible et une branche structurellement inatteignable. `apply(mode)` synchronise et renvoie la section ; l'appelant décide de révéler ou de porter le focus.

**Constats écartés** :

- *Un `enum` pour la table slug → libellé et propriétés, aujourd'hui écrite à quatre endroits.* Consolider vraiment demanderait un accès dynamique aux propriétés (`$this->{$nom}`), que PHPStan niveau max n'accepte pas ; sans cela le gain tombe à quatre endroits sur trois. Disproportionné pour un format que le brief borne à deux modes.
- *`$property` et `$tableOfContentProperty` décoratifs sur `ArticleReadingModesProcessor`.* Quatre processors voisins du dépôt portent le même paramètre avec la même valeur par défaut, et `content` est configurable à l'échelle du projet dans Stenope. Les retirer diverge de la convention.
- *Renommer `.reading-mode` pour le distinguer de `.reading-modes`.* La paire est cohérente en BEM — le bloc de choix des modes, et un mode. Le risque de coquille est réel mais le renommage sur trois fichiers l'est aussi.
- *Déplacer le cyclage de la palette d'intervenants dans le SCSS.* Remplacerait un plafond exact côté PHP (`SPEAKER_COLORS = 4`) par un plafond arbitraire côté SCSS.
- *Déplacer `$color-brand-dark` dans `_interview.scss`.* Tous les jetons de couleur du dépôt vivent dans `base/_variables.scss`.
- *Supprimer `ReadingMode::$default` au profit de `loop.first`.* Dérivable, oui, mais le brief en fait un concept — « le mode récit est affiché à l'arrivée ». `loop.first` dans trois gabarits l'exprime moins bien, et le contrôleur porte déjà le pendant `defaultModeValue`.
- *Mettre en cache les cibles Stimulus.* Quelques `querySelectorAll` par clic ; l'optimisation irait contre l'idiome du framework pour un gain non mesurable.
- *Le retour arrière depuis une note appelée dans l'autre mode reste inerte.* Déjà consigné plus haut, à arbitrer à la revue.

**Vérifications** :

- `make lint` : aucune erreur (php-cs-fixer, PHPStan niveau max, Twig, YAML, ESLint, conteneur, composer). PHPStan a exigé l'annotation `@var \DOMElement` sur l'itération du `Crawler` — le `Crawler` est typé `\DOMNode` ; c'est l'idiome déjà employé par `HtmlAnchorProcessor`.
- `make build.content.without-images` : 615 pages.
- Banc d'essai des blocs d'entretien : sortie identique sur les six cas.
- Navigateur : lien profond, bascule depuis le haut (985,5 px avant et après, aucun mouvement), bascule depuis les contrôles de fin (section amenée en haut, focus posé), retour arrière, couleurs d'intervenants distinctes, filets à 3 px et texte aligné à 242 px comme avant, titre de mode toujours masqué par `@extend`, et les quatre blocs en `display: block` sans script.

### 2026-09-04 : Revue de code — deux défauts corrigés

**Statut** : Terminé

**Actions réalisées** :

- **Lien de la zone commune vers une section homonyme : ancre morte.** `sharedMap()` écartait volontairement les identifiants portés par les deux modes, en laissant le lien intact « plutôt que d'en désigner un ». Le raisonnement était faux : le préfixage a renommé **les deux** titres, l'identifiant d'origine ne désigne donc plus rien dans la page. Un identifiant ambigu est désormais résolu vers le premier mode de l'article, celui affiché à l'arrivée. Les liens internes à un mode continuent de viser le leur, la table du mode primant sur la table partagée. Vérifié sur un banc d'essai : depuis la zone commune, `#pour-finir` (présent dans les deux modes) devient `#narratif-pour-finir` ; depuis le récit, il reste `#narratif-pour-finir` ; l'entretien garde le sien.
- **Lien interne vers un mode inactif : sans effet.** Le contrôleur n'écoutait que `popstate`. Un lien de même page déclenche `hashchange`, pas `popstate` : la cible étant masquée, le navigateur ne faisait rien et le contrôleur ne rejouait pas la résolution. Les deux événements sont désormais écoutés, via un `follow()` idempotent.
- Commentaire de `getReadingModes()` rectifié : il annonçait un ordre « de lecture » alors que la méthode suit l'ordre déclaré — récit puis entretien — quel que soit l'ordre des séparateurs dans le markdown. Le comportement est le bon (le brief veut le récit à l'arrivée), c'est la documentation qui mentait.

**Fichiers modifiés** :

- `src/Stenope/Processor/ArticleReadingModesProcessor.php`
- `assets/js/controllers/blog/reading_modes_controller.js`
- `src/Model/Article.php`

**Notes** :

- **Le second correctif règle le défaut signalé plus haut comme non traitable dans le périmètre.** Le lien de retour d'une note appelée depuis l'autre mode aboutit maintenant : vérifié en navigateur, un clic sur le retour de la note 2 depuis le récit bascule sur l'entretien et amène à l'appel de note. La question à arbitrer à la revue tombe.
- Constat écarté par la revue et confirmé : `decodeURIComponent` lèverait une `URIError` sur un fragment mal formé écrit à la main. Stimulus intercepte les erreurs de cycle de vie, la page retombe alors sur son état servi. Non traité.
- `make lint` et le build de contenu repassés. Les flux navigateur ont été rejoués : lien profond (cible à 130 px), bascule depuis le haut (985,5 px avant et après), bascule depuis les contrôles de fin (section amenée exactement en haut, focus posé), retour arrière, aucune erreur console.

### 2026-09-04 : Non-régression des articles ordinaires, vérifiée par comparaison de builds

**Statut** : Terminé

**Actions réalisées** :

Le site entier a été construit sur `main` puis sur `HEAD`, et les deux sorties comparées fichier par fichier — plutôt que de raisonner sur les sorties anticipées des processors.

| Constat | Résultat |
|---|---|
| Pages d'article ordinaires comparées | **166, toutes identiques** |
| `blog/styleguide/example` (4 notes) | identique — 4 appels, 4 notes listées, 4 liens de retour |
| `a-la-rencontre-d-emmanuelle-aboaf` (2 notes) | identique — 2 appels, 2 notes, 2 liens de retour |
| Pages supprimées | aucune |
| Pages ajoutées | une seule, celle de l'article de démonstration |

Les 39 pages qui diffèrent encore se répartissent en trois causes, toutes étrangères au format :

- 11 listings, pages d'auteur et de tag qui intègrent désormais l'article de démonstration ;
- 8 pages de pagination du blog, décalées d'un cran par ce même article ;
- 20 pages d'étude de cas, dont le bloc « études de cas associées » est tiré au sort à chaque build (`shuffle()` dans `CaseStudyController`) — non-déterminisme préexistant.

**Notes** :

- **Pourquoi les notes ne pouvaient pas bouger, confirmé par la mesure.** `HtmlFootnotesProcessor` s'exécute en priorité -10, `ArticleReadingModesProcessor` en -95 et sort à sa toute première condition dès que `type` n'est pas `interview`. Aucun préfixage, aucune réécriture de lien, aucun découpage : le contenu d'un article ordinaire ne passe simplement pas par le nouveau code.
- **Un écart d'octets subsiste, sans portée.** L'extraction du sommaire en partiel a changé l'indentation du HTML produit, et mes modifications de SCSS et de JS changent les URL de bundles webpack. La comparaison ci-dessus neutralise ces deux bruits ; à leur exception, les pages d'article sont rigoureusement identiques.
- Point relevé au passage : `styleguide/example` et 47 autres articles ne déclarent aucune clé `type`. La propriété `Article::$type`, typée et sans valeur, n'était donc pas initialisée pour eux — sans conséquence, puisqu'elle n'était lue nulle part. La valeur par défaut `post` ajoutée par cette story ferme ce cas latent.

### 2026-09-04 : Clôture

**Statut** : Terminé

**Actions réalisées** :

- `synthesis.md` rédigé.
- `status` passé à `Done` dans `brief.md`, `plan.md` et `dev.md`.

**Notes** :

- Clôture **en l'état** à la demande d'ogi. Deux points restent ouverts avant d'ouvrir le format à d'autres articles : la revue du rendu par Eva et la designer, explicitement bloquante au brief, et le remplacement de l'article de démonstration par le véritable article de la série. Les deux sont consignés dans la synthèse.

### 2026-09-04 : L'article de démonstration devient la référence du format

**Statut** : Terminé

**Actions réalisées** :

Décision d'ogi révisée après clôture : l'article de démonstration est **conservé**, aux côtés du guide de style, et l'article réel de la série viendra en plus, non à sa place.

- Article entièrement réécrit sur un contenu **fictif**, sans lien ni mention de l'entretien qui a motivé la story. Titre, chapô et corps refaits ; sujet et propos inventés.
- **Quatre intervenants** — Eva, Maxime, Camille, Sacha — pour couvrir toute la palette, et Eva reprend la parole en fin d'article pour montrer qu'un nom garde sa couleur.
- Cas de rendu couverts : zone commune, section enchaînant plusieurs échanges, section sans remise en contexte, prise de parole absorbant un paragraphe non attribué, section sans question, deux sections homonymes, les deux écritures du nom, les deux variantes de citation en exergue dans le récit, un sous-titre pour la profondeur de sommaire, deux notes appelées chacune depuis un mode.
- La zone commune porte désormais la **syntaxe complète**, cas limites compris, et l'article annonce explicitement que son contenu est fictif.
- `brief.md` : le point « documenter durablement la syntaxe » du hors-périmètre est barré et daté plutôt que réécrit — le brief reste le témoin de ce qui avait été décidé.
- `synthesis.md` mis à jour : l'article n'est plus jetable, il est la référence du format.

**Fichiers modifiés** :

- `content/blog/styleguide/entretien-deux-modes.md`
- `work/stories/blog/format-entretien/2026-09-04-article-deux-modes/brief.md`
- `work/stories/blog/format-entretien/2026-09-04-article-deux-modes/synthesis.md`

**Notes** :

- **Cette décision comble le manque signalé à la session `communication`.** Je lui avais répondu qu'aucun fichier de référence stable n'existait, l'article étant jetable. Ce n'est plus vrai : la correction lui a été transmise.
- Vérifié sur la page rendue : quatre couleurs distinctes attribuées dans l'ordre d'apparition, Eva retrouvant la sienne ; un seul bloc de contexte, dans la seule section qui en porte un ; aucun `blockquote` restant dans l'entretien, deux conservés dans le récit ; sommaires de 4 et 5 entrées ; `narratif-pour-finir` et `entretien-pour-finir` distincts ; et **aucune occurrence** de « Martin », « Dufresne », « morphogénèse » ou « veille » dans la page.
- Observation pour la revue visuelle : les quatre tons vus côte à côte, `$color-dark` (#0d3a5a) et `$color-info` (#1e7695) sont deux bleus assez proches. Distinguables, tous deux conformes en contraste, mais c'est le couple le moins tranché de la palette.
