---
id: STOR-001
epic: blog
feature: format-entretien
slug: article-deux-modes
title: Un article de blog porte deux modes de lecture, récit et entretien, avec bascule
created: 2026-09-04
status: Done
---

# Article de blog à deux modes de lecture — récit et entretien

## Contexte

La série « Les voix de notre veille » publie des interviews retranscrites. Trois formats ont été écrits pour arbitrer sur pièces, et les trois tiennent : le récit se lit vite mais efface les voix directes ; l'entretien intégral les restitue, au prix de 8 120 mots. La décision éditoriale (D1) est de ne pas choisir : l'article porte **deux modes de lecture** et le lecteur bascule.

Le blog ne sait rendre aujourd'hui ni les prises de parole nommées fondues dans le fil du texte, ni les remises en contexte de la rédaction, ni la bascule. Cette story livre le format complet, intégré à la charte, sur un article de démonstration jetable.

Cadrage fonctionnel : `work/stories/blog/format-entretien/2026-09-04-article-deux-modes/brief.md`.
Repères de session (chemins des ébauches, pièges relevés) : `work/stories/blog/format-entretien/2026-09-04-article-deux-modes/CLAUDE.local.md`.

## Deux constats qui fondent la conception

Vérifiés en exécutant Parsedown, pas supposés.

**1. Les commentaires HTML traversent Parsedown intacts.** `<!-- mode: entretien -->` arrive tel quel dans le DOM. C'est le séparateur de modes : invisible dans tout aperçu markdown, zéro développement de parsing.

**2. Deux blockquotes adjacents fusionnent en un seul.** C'est le comportement de `blockQuoteContinue` en amont, et il est systématique :

```markdown
> Contexte de la rédaction.

> **Eva :** La question ?
```
produit **un** `<blockquote>` de deux paragraphes, pas deux blocs.

> [!IMPORTANT]
> Ce constat retourne la contrainte en avantage. Plutôt que d'imposer un séparateur à la rédaction, le processor **re-découpe** chaque blockquote : les paragraphes de tête sans nom forment la remise en contexte, chaque paragraphe ouvrant par `**Nom :**` ouvre une prise de parole. La rédaction écrit comme elle veut — blocs collés ou séparés —, le rendu est le même.

## Syntaxe d'article

En-tête :

```yaml
type: interview          # active le format ; tout autre valeur = article inchangé
readingModes:
    intro: "…"                                   # optionnel — surcharge le texte du bloc d'annonce
    narratif:  { label: "…", readingTime: 11 }   # les deux optionnels
    entretien: { label: "…", readingTime: 33 }
```

Corps :

```markdown
Zone commune, rendue dans les deux modes. Optionnelle.

<!-- mode: narratif -->

## Une section

De la prose.

<!-- mode: entretien -->

## « Un titre verbatim »

> Remise en contexte, un à trois paragraphes, voix de la rédaction.
>
> **Eva :** La question.

La réponse de l'invité·e, en clair : c'est elle qui porte le corps.

> **Maxime :** Une relance.

La réponse suivante.
```

Le nom peut aussi s'écrire avec `<cite>`, forme déjà employée par le blog (voir plus bas) :

```markdown
> Une relance.
> <cite>Maxime</cite>
```

Les deux formes sont acceptées en entrée et produisent le même rendu.

## Sémantique des prises de parole

`> Citation` + `<cite>Auteur</cite>` **est déjà la convention de ce blog** : `content/blog/styleguide/example.md:86-91` l'enseigne, et elle est employée dans plusieurs articles publiés — `content/blog/dev/design-pattern-chain-of-responsibility.md:18`, `content/blog/methodo/github-project.md:52`. `assets/scss/generic/_blockquote.scss:41` la met en forme.

**Est-elle juste ? Non, sur deux points**, au regard de la spécification HTML :

- `<cite>` représente **le titre d'une œuvre**. La spécification écarte explicitement les noms de personnes : « A person's name is not the title of a work […] and the element must therefore not be used to mark up people's names. »
- L'attribution d'une citation **doit se placer hors** du `blockquote` ; le motif recommandé est `figure` + `blockquote` + `figcaption`.

**Est-ce grave ? Non.** `<cite>` ne porte aucun rôle ARIA conséquent : les lecteurs d'écran restituent le texte à l'identique dans les deux cas. C'est un écart de conformité, pas un défaut d'accessibilité. Corriger la convention sur l'ensemble du site sort du périmètre de cette story.

**Ce qu'on en fait ici.** On accepte la forme `<cite>` **en entrée**, parce qu'un format d'entretien qui refuserait la convention que le guide de style enseigne serait incohérent. Mais on ne la reconduit pas **en sortie** :

> [!NOTE]
> Une prise de parole est rendue en `<div class="interview-turn">` avec le nom en `<p class="interview-turn__speaker">`, texte simple — ni `<cite>`, ni `<blockquote>`.
>
> Trois raisons. Le nom d'un intervenant est une **étiquette de locuteur**, pas un titre d'œuvre. La question d'un intervieweur est un **tour de dialogue**, pas une citation prélevée ailleurs : dans un mode où tout le corps est du discours rapporté, ne marquer que les intervieweurs comme cités inverse le sens. Et `figure`/`figcaption`, seul motif conforme pour attribuer un `blockquote`, se heurterait à `assets/scss/components/_article-content.scss:19-22`, qui décale toutes les `figure` de −200 px.
>
> Ce qu'on perd : le signal « ceci est une citation ». Dans ce contexte précis, il ne signale rien d'utile.

Conséquence à assumer : dans le mode entretien, **tout** `blockquote` devient un bloc de contexte ou une prise de parole. La citation classique en exergue n'y est donc pas disponible — cohérent avec la décision « blocs réservés à l'entretien », et sans effet sur le reste du site.

## Architecture

```
content/…/*.md
  │  Parsedown (les commentaires passent, les blockquotes fusionnent)
  ▼
content ──► processors Stenope existants (ids, ancres, notes, tableaux, images, liens)   prio 10 … -20
  │
  ▼  ArticleReadingModesProcessor                                                        prio -95
  ├──► content              zone commune (le crawler est élagué, cf. avertissement)
  ├──► narrativeContent     ids de titres préfixés « narratif- », href internes réécrits
  └──► interviewContent     ids de titres préfixés « entretien- »
                              │
                              ▼  HtmlInterviewBlocksProcessor                            prio -96
                              └──► blockquotes re-découpés en contexte / prises de parole
                                     │
                                     ▼  TableOfContentProcessor ×2                       prio -100
                                     └──► narrativeTableOfContent, interviewTableOfContent
```

Tout ce qui précède `-95` opère sur le contenu **entier** : images, liens, coloration, ancres et notes sont donc déjà traités quand la découpe intervient. Rien à réenregistrer de ce côté.

> [!WARNING]
> `SharedHtmlCrawlerManager::saveAll()` réécrit `$data[$property]` depuis le crawler mis en cache, après tous les processors. Réaffecter `$data['content']` à la seule zone commune serait donc **écrasé**. La découpe doit **élaguer le DOM du crawler `content`** (retirer les sous-arbres des modes), pas réassigner la chaîne.

## Tâches

### 1. Modèle — type d'article et modes de lecture

`src/Model/Article.php` : `$type` reçoit une valeur par défaut (`post`, aujourd'hui déclarée dans tous les articles mais lue nulle part), deux constantes, et `isInterview()`. Quatre propriétés remplies par les processors — `narrativeContent`, `narrativeTableOfContent`, `interviewContent`, `interviewTableOfContent` — plus `readingModes` pour l'en-tête.

Un petit objet `src/Model/Article/ReadingMode.php` (slug, libellé, durée, contenu, sommaire, mode par défaut) et un accesseur `getReadingModes(): list<ReadingMode>` gardent le gabarit lisible et PHPStan silencieux au niveau max.

### 2. Découpe des modes

`src/Stenope/Processor/ArticleReadingModesProcessor.php`, priorité **-95** — après `DefaultTocProcessor` (-90) dont il reprend la profondeur, avant les sommaires (-100). Sur le modèle de `src/Stenope/Processor/HtmlTablesProcessor.php`.

- Sort immédiatement si `type !== 'interview'`.
- Parcourt les enfants de `<body>`, coupe sur les nœuds commentaire `mode: <slug>`.
- Préfixe les `id` des titres `h1`–`h6` par le slug du mode, et réécrit dans le même fragment les `href="#…"` qui les visaient.
- Ne touche **pas** aux `id` de notes : `HtmlFootnotesProcessor` pose des `footnote-ref-N` que le gabarit référence depuis l'extérieur du contenu.
- Reporte la profondeur de sommaire sur les deux propriétés de mode, puis **désactive le sommaire principal** pour que `TableOfContentProcessor` ne construise pas un sommaire de la seule zone commune.

### 3. Blocs d'entretien

`src/Stenope/Processor/HtmlInterviewBlocksProcessor.php`, priorité **-96**, sur `interviewContent` uniquement — ce qui satisfait mécaniquement « blocs réservés au mode entretien ».

Pour chaque `blockquote` : les paragraphes de tête sans nom deviennent `<div class="interview-context">` ; chaque nom rencontré ouvre une `<div class="interview-turn interview-turn--N">` qui absorbe les paragraphes suivants non nommés.

Deux formes d'entrée reconnues, normalisées vers la même sortie (cf. « Sémantique des prises de parole ») : un paragraphe ouvrant par `<strong>Nom :</strong>`, ou un `<cite>Nom</cite>`. Le nœud porteur du nom est retiré du flux et rendu en `<p class="interview-turn__speaker">`.

L'indice `N` est attribué **par ordre d'apparition** dans l'article, pas par hachage du nom : deux intervenants sont toujours distincts, et un même intervenant garde sa couleur d'un bout à l'autre.

### 4. Sommaires par mode

`config/services.yaml` : deux définitions de `Stenope\Bundle\Processor\TableOfContentProcessor` avec `$contentProperty` / `$tableOfContentProperty` pointant les propriétés de mode, priorité -100. Le dépôt enregistre déjà trois instances de `ResizeImagesContentProcessor` sur ce modèle — pattern connu, pas d'invention.

### 5. Gabarit

`templates/blog/article.html.twig` bascule sur `article.isInterview()`, avec deux partiels dédiés pour ne pas alourdir un fichier déjà long. Ordre dans la page :

| Position | Élément | Portée |
|---|---|---|
| après la bannière | bloc d'annonce + contrôles | commun |
| emplacement actuel du sommaire | un sommaire par mode, un seul visible | par mode |
| `.article-content__main` | zone commune | commun |
| — | une `<section>` par mode, avec rappel des contrôles en fin | par mode |
| — | notes, crédits | commun |

Les sommaires restent **à leur emplacement actuel**, hors des sections de mode. Conséquence sur les contrôles : le motif `tablist` suppose que les panneaux soient les frères du groupe d'onglets, ce que cette disposition ne permet pas.

> [!NOTE]
> D'où le choix de **boutons bascule `aria-pressed` dans un groupe nommé**, plutôt qu'un `tablist`. Même information annoncée, sans mentir sur la structure. C'est un arbitrage à remettre sur la table à la revue design si le sommaire peut déménager.

### 6. Contrôleur de bascule

`assets/js/controllers/blog/reading_modes_controller.js`, dédié — `tabs_controller.js` et `category-switch_controller.js` servent ailleurs et leur accessibilité ne tient pas.

Le sous-dossier suit `assets/js/controllers/ia/brief/`, invoqué par chemin : `stimulus_controller('ia/brief/iframe')` dans `templates/site/services/ia-brief.html.twig:41`. Le nôtre sera donc `stimulus_controller('blog/reading_modes')`. Aucune configuration : `assets/js/bootstrap.js` charge déjà `controllers/` en récursif.

- À la connexion : résout le mode depuis le fragment d'URL — slug de mode, ou identifiant de section dont le préfixe désigne le mode — sinon le mode par défaut.
- À la sélection : bascule, puis `pushState` (décision : le retour arrière ramène au mode précédent).
- Écoute `popstate` pour rejouer la résolution au retour arrière.
- Lien profond vers une section : active le mode **puis** amène à la section, l'ancre native ne pouvant pas viser un élément masqué.

Swup ne demande rien de spécial : Stimulus reconnecte le contrôleur sur le DOM remplacé, et l'écouteur `popstate` est posé et retiré au fil des connexions.

**Anti-clignotement, sans script supplémentaire.** `templates/base.html.twig:2` porte déjà `<html class="no-js">` et retire la classe au plus tôt. La règle `html:not(.no-js) .reading-mode:not(.is-active) { display: none }` masque donc le mode inactif dès le rendu, bien avant que Stimulus démarre — et sans script, les deux modes restent affichés. Cela ferme la question ouverte du brief sur le double affichage transitoire.

### 7. Styles — annonce et contrôles

`assets/scss/components/_reading-modes.scss`, importé depuis `assets/scss/style.scss`. Couleurs, polices et espacements de la charte. Les contrôles portent libellé et durée quand elle est renseignée.

### 8. Styles — blocs d'entretien

`assets/scss/components/_interview.scss`. Parti pris : **discret et dans le fil du corps**.

- `.interview-turn` — filet vertical de 2 px à la couleur de l'intervenant, nom au-dessus en petites capitales dans la même couleur, texte à la taille du corps.
- `.interview-context` — filet de 1 px neutre, texte légèrement réduit et adouci.

Palette d'intervenants, cyclique, mesurée sur fond blanc :

| Ton | Valeur | Contraste |
|---|---|---|
| `$color-primary` | #7f1A55 | 9,63:1 |
| `$color-dark` | #0d3a5a | 11,86:1 |
| bleu de charte | #007695 | 5,22:1 |
| **rouge de marque assombri** | **#e60002** | **4,81:1** |

`$color-brand` #ff4345 ne passe pas — 3,42:1, sous le seuil de 4,5:1 exigé pour du petit texte. Plutôt que de l'écarter, on en dérive `#e60002` : teinte (359,4°) et saturation (100 %) **conservées à l'identique**, luminance abaissée de 18 %. C'est le premier palier qui franchit le seuil, donc le plus proche de l'original qui soit utilisable. Nouvelle variable dédiée à cet usage, `$color-brand` restant inchangé partout ailleurs.

Aucune neutralisation des règles génériques de `assets/scss/generic/_blockquote.scss` n'est nécessaire : le processor ne laisse plus aucun `blockquote` dans le mode entretien (cf. « Sémantique des prises de parole »). Ni guillemet géant en `:before`, ni fond plein en `nth-of-type(even)` à combattre.

### 9. Article de démonstration

`content/blog/styleguide/entretien-deux-modes.md`, ajouté à `$ignored` dans `config/services.yaml` comme `styleguide/example` et `elao/trame-itw`. Miniature : `content/images/blog/interviews/trame-itw.jpg`, déjà présente.

Contenu dérivé des ébauches de l'entretien de Martin Dufresne — récit quasi complet, entretien allégé —, couvrant : une section enchaînant plusieurs échanges, une section sans remise en contexte, une section sans question (la clôture « Pour suivre… »), deux intervieweurs, et une zone commune.

## Vérification

### Contrôles navigateur — je les opère

`make serve`, puis sur `http://localhost:35080/blog/styleguide/entretien-deux-modes` :

- [ ] À l'arrivée sans fragment : le récit est affiché, l'entretien absent de la page rendue.
- [ ] Bascule : corps **et** sommaire changent, l'adresse suit.
- [ ] Retour arrière : ramène au mode précédent.
- [ ] `#entretien` ouvre l'entretien ; `#entretien-<section>` l'ouvre **et** amène à la section.
- [ ] Prises de parole : nom affiché, deux intervieweurs de couleurs différentes, chacun constant.
- [ ] Les deux formes d'écriture du nom — `**Nom :**` et `<cite>Nom</cite>` — produisent le même rendu.
- [ ] Remise en contexte : visuellement distincte, sans nom.
- [ ] Section à plusieurs échanges, section sans contexte, section sans question : rendues correctement.
- [ ] Durée affichée sur le contrôle quand renseignée, libellé seul sinon.
- [ ] Contrôles atteints et actionnés au clavier seul ; état annoncé (vérifié dans l'arbre d'accessibilité).
- [ ] Scripts désactivés : les deux modes et les deux sommaires lisibles, chacun sous son titre.
- [ ] Navigation depuis le listing du blog (transition Swup) : la bascule fonctionne.
- [ ] Aucun clignotement des deux modes au chargement ni après transition.
- [ ] Un article ordinaire (`blog/styleguide/example`) est inchangé — comparaison avant/après.

Captures d'écran à l'appui pour la revue.

### Contrôles qualité

`make lint` — php-cs-fixer, PHPStan niveau max, twig, yaml, eslint, container, composer.
`make build.content.without-images` — le build de contenu passe.

### Revue visuelle — ogi

Une fois les contrôles ci-dessus déroulés et les captures fournies : revue des rendus et ajustements. Le brief note que la revue d'Eva et de la designer reste à mener avant d'ouvrir le format à d'autres articles.

## Risques

| Risque | Traitement |
|---|---|
| Titres homonymes entre les deux modes → identifiants dupliqués, ancres cassées | Préfixe de mode sur les `id` de titres et réécriture des `href` internes (tâche 2). Le piège est déjà documenté dans `src/Stenope/Processor/HtmlTablesProcessor.php`. |
| `saveAll()` écrase la zone commune | Élaguer le DOM du crawler, ne pas réassigner la chaîne (avertissement plus haut). |
| Une prise de parole hérite des règles génériques de blockquote | Sans objet : le mode entretien ne conserve aucun `blockquote` en sortie (tâche 3). |
| Un nom d'intervenant illisible sur fond clair | Palette mesurée, tous les tons ≥ 4,5:1, dérivation du rouge de marque incluse (tâche 8). |
| Notes de bas d'article communes aux deux modes | Défaut assumé et acté au brief : une note appelée dans un seul mode reste listée dans les deux. |
| Sortie de l'article après plusieurs bascules | Coût assumé et acté au brief, conséquence directe de l'empilement d'historique. |
