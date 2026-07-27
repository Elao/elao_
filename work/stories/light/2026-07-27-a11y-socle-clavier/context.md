---
story: "Socle de navigation clavier : focus visible, lien d'évitement, reprise de focus Swup"
story_code: "a11y-socle-clavier"
created: 2026-07-27
---

# Contexte

## Description fonctionnelle

Le site est aujourd'hui inutilisable au clavier. Aucune règle `:focus-visible` n'existe dans les feuilles de style — `outline` n'y sert qu'à *supprimer* l'anneau natif — donc une personne qui tabule ne sait jamais où elle se trouve dans la page. Il n'existe aucun lien d'évitement : atteindre le contenu d'un article impose de traverser toute la navigation à chaque page. Et parce que Swup remplace `#main` à chaque navigation, le focus retombe sur `<body>` sans qu'aucun lecteur d'écran ne soit informé du changement de page. Le bénéfice ne concerne pas seulement les utilisateurs de lecteurs d'écran : **toute personne qui navigue au clavier est concernée** — handicap moteur, préférence de navigation, trackpad hors service. L'indicateur de focus est le point le plus universel du lot : il profite à n'importe qui appuie sur `Tab`, y compris un membre de l'équipe qui remplit un formulaire.

Il existe par ailleurs un argument commercial concret, à ne pas confondre avec une obligation légale. Elao vend du développement web à des donneurs d'ordre publics pour lesquels le RGAA est une exigence **contractuelle** : un prestataire dont le propre site vitrine échoue au premier test clavier est en position défavorable en appel d'offres. En revanche, l'obligation légale directe ne s'applique pas à Elao — les seuils de la loi (secteur public, ou chiffre d'affaires ≥ 250 M€) ne sont pas atteints. La motivation est donc la crédibilité commerciale et la cohérence avec ce que l'agence facture à ses clients, pas la mise en règle sous contrainte.

### Périmètre

**Ce qui change** — un indicateur de prise de focus visible sur tout le site, garanti lisible sur tous les fonds de la charte (y compris les 18 composants à fond sombre) ; un lien « Aller au contenu principal » en premier arrêt de tabulation ; la reprise du focus et l'annonce vocale de la nouvelle page après chaque navigation Swup ; un défilement instantané pour qui a activé la réduction des animations, uniquement sur le défilement que ce lot introduit. Au passage, le `<main>` imbriqué des articles de blog est corrigé, pour que le lien d'évitement vise une cible non ambiguë.

**Ce qui ne change pas** — l'apparence du site à la souris, la palette, les contrastes, les libellés de liens et d'icônes, et les animations AOS, qui restent volontairement actives : les neutraliser sans les désactiver côté JS rendrait des dizaines de blocs définitivement invisibles, précisément pour le public visé. Contrastes, noms accessibles et plan du site sont traités dans les trois lots suivants.

### Critères d'acceptation

1. **Rien ne change à la souris.** Au clic, aucun anneau n'apparaît sur les liens et les boutons ; l'anneau natif sur les champs de saisie (`select`, `textarea`) est le comportement attendu et assumé. C'est le critère qui rend ce lot acceptable pour l'équipe design, et il se vérifie explicitement : parcourir chaque page au `Tab` (anneau visible à chaque arrêt), puis cliquer les mêmes éléments (aucun anneau).
2. **Le site est parcourable au clavier de bout en bout**, sur les 7 pages du plan de test, dans Chrome, Firefox et Safari — ces deux derniers étant obligatoires, car c'est là que le comportement du focus sur une cible de fragment diffère.
3. **Le lien d'évitement fonctionne réellement** : premier arrêt de tabulation, visible, et la tabulation suivante repart du contenu et non du header. Il ne doit jamais intercepter un clic souris sur le logo du header.
4. **Après une navigation Swup**, le focus est sur `#main`, un lecteur d'écran annonce la nouvelle page, un seul mouvement de défilement est visible, et `aria-busy` ne reste jamais collé sur `<html>` (à contrôler en particulier sur `/services/optimiser` et `/services/ia`).
5. **En mouvement réduit, aucun bloc ne disparaît.** Les animations AOS doivent continuer de fonctionner — c'est le test qui prouve qu'on n'a pas introduit le bug qu'on cherchait à éviter.
6. **Ce lot ne rend pas le site conforme RGAA.** Il traite 4 critères sur les 14 non-conformités relevées (10.7, 12.7, 12.8, 13.8, plus 12.6 corrigé au passage). Toute communication interne ou commerciale doit s'en tenir à « le site est désormais utilisable au clavier », pas à « le site est conforme ».

## Vue architecturale

### Les trois coutures touchées, et ce qui les relie

Ce lot ne touche que trois fichiers de structure, mais ce sont précisément les trois points où le site décide de son comportement global : l'orchestrateur de la feuille de style (`assets/scss/style.scss`, 149 imports ordonnés), le squelette HTML unique (`templates/base.html.twig`, qui déclare à la fois le conteneur `#main` et la configuration Swup) et la seule couture d'extension du runtime de navigation (`assets/js/controllers/swup_plugins_controller.js`). Les trois couches ne se parlent pas directement : elles se coordonnent par des invariants implicites, et c'est là que le défaut d'accessibilité s'est logé. Le lien d'évitement n'a de sens que si l'anneau de focus existe (couche 1 → couche 2) ; le `tabindex="-1"` doit être déclaré côté serveur parce que le runtime détruit le nœud qui le porte (couche 3 → couche 2) ; l'annonce vocale dépend du premier titre du contenu servi (couche 2 → couche 3). Aucun de ces trois couplages n'était documenté avant cet audit, et aucun n'est vérifié par un outil.

```
templates/base.html.twig ─── déclare ──▶ #main   ◀── détruit/recrée ─── runtime Swup
        │                                  ▲                                 ▲
        │ déclare aussi                    │ stylé par                       │ étendu par
        ▼                                  │                                 │
  containers: ['#main','#nav']      style.scss (cascade)         swup_plugins_controller
  (surchargeable par page)          base/ → components/ → pages/   via `swup:pre-connect`
```

### La cascade SCSS : il manque une couche d'accessibilité protégée

Le vrai enseignement de ce lot n'est pas « il y avait dix resets à retirer », c'est que le dépôt n'a **aucun moyen structurel** de garantir une règle transverse. `base/` est importé en tête (lignes 13 à 17), donc avant une centaine de fichiers de `components/`, `generic/` et `pages/` — et en CSS, « en tête » signifie « le plus faible à spécificité égale ». Une règle de base à (0,1,0) est battue deux fois : par la spécificité (`.x:focus` = (0,2,0)) et par l'ordre. Le plan a raison de conclure qu'aucun déplacement d'import n'y suffit et que seule la suppression des resets fait gagner la règle globale — mais cette conclusion rétablit un invariant **une fois**, elle ne le rend pas durable : les ~90 fichiers de `components/` restent libres de le casser demain, exactement comme ils l'ont fait hier.

```
ordre de peinture actuel                  garantie apportée
─────────────────────────────────────────────────────────────────
normalize + vendor (prism, aos)           aucune
base/_variables … _utilities  (l.13-17)   « je suis d'accord pour perdre »
  └─ base/_focus              (l.18)      ← la règle d'accessibilité vit ICI
components/ ×~90              (l.20-116)  gagne par ordre ET par spécificité
generic/                      (l.119-127) idem
pages/                        (l.130-148) idem  (dont un `all: revert`)
```

Ce qu'il faut en retenir pour la suite : une règle d'accessibilité n'est pas un style, c'est un **contrat**, et un contrat ne se défend pas par convention d'import. Deux réponses architecturales existent, non exclusives. La première est structurelle : rendre la couche a11y indépendante de l'ordre des ~150 imports (couches de cascade explicites, ou une couche a11y placée en fin de feuille avec la spécificité nécessaire) — c'est propre, mais c'est un refactor global de `style.scss`, hors de proportion avec ce PR. La seconde est un garde-fou de processus : une règle de lint interdisant la neutralisation d'un indicateur de focus sans substitut. Le plan l'identifie déjà comme ticket à ouvrir ; je le classerais comme **prérequis de clôture du chantier**, pas comme nice-to-have, parce que c'est la seule chose qui empêche les PR B, C et D — et les suivantes — de rouvrir la dette que le PR A ferme.

### Swup a fait du site une quasi-SPA, l'accessibilité est restée en site classique

Le remplacement de conteneur par `outerHTML` n'est pas un détail d'implémentation de Swup 3, c'est la **cause racine commune** des trois symptômes constatés, et cela mérite d'être écrit une fois pour toutes plutôt que redécouvert à chaque lot. Détruire le nœud, c'est perdre son identité (le focus retombe sur `<body>`), perdre tout ce que le JS y avait posé (d'où le `tabindex` déclaré côté serveur, seule position stable), et ne rien signaler aux technologies d'assistance (d'où la nécessité d'une région live). Le site s'est doté d'un cycle de vie de navigation applicatif sans se doter du contrat d'accessibilité qui va avec — focus, annonce, état d'occupation.

```
clic interne
  loadPage()    ──▶ transitionStart  ──▶ <html aria-busy="true">
  renderPage()  ──▶ #main remplacé par outerHTML   ← identité du nœud perdue
                ──▶ contentReplaced   ──▶ [replacer le focus] + [annoncer]
  enterPage()   ──▶ transitionEnd     ──▶ retrait de aria-busy
```

La règle durable à consigner tient en trois lignes : ce qui est **hors des conteneurs** survit (le lien d'évitement en dépend) ; ce qui est **dans le HTML servi** revient à chaque navigation (le `tabindex` en dépend) ; ce qui est **posé en JS à l'intérieur d'un conteneur** est volatile, et doit donc être réinstallé sur un événement du cycle de vie. Le choix du plugin plutôt que d'un hook maison est cohérent avec cette lecture : le hook « évident » (`pageView`) se déclenche aussi à l'activation, donc au chargement initial de chaque page — le piège n'est pas dans l'API, il est dans la confusion entre « la page est affichée » et « la page vient d'être remplacée ». Le brancher en premier dans la liste de plugins, avec `preventScroll`, résout par ailleurs une interaction que je souligne parce qu'elle est invisible en lecture de code : deux plugins indépendants (focus et défilement) revendiquent le même effet visuel, et rien dans l'architecture ne les arbitre à part l'option passée à la main.

### Une version majeure figée par le pont PHP, pas par `package.json`

Le point de dette le plus intéressant du lot n'est pas le plugin ajouté, c'est **d'où vient le verrou**. Le dépôt n'instancie jamais Swup lui-même : c'est le contrôleur Stimulus du pont `@symfony/ux-swup`, dont le paquet npm n'est même pas résolu depuis npm mais par un lien `file:vendor/symfony/ux-swup/assets`. Sa version est donc gouvernée par Composer, et il déclare `swup: ^3.0` en `peerDependencies`. Autrement dit : une contrainte du gestionnaire de dépendances PHP dicte la version majeure d'une brique frontend, et par ricochet la version majeure de tout plugin Swup ajouté ici. La v3 du plugin d'accessibilité n'est pas un choix, c'est la seule branche compatible avec ce verrou.

```
composer.json ── symfony/ux-swup ^2.16
                    └─ vendor/…/assets  ⇒  peerDependencies: swup ^3.0   ← LE VERROU
package.json  ── swup ^3.0 (résolu 3.1.1)
                 @swup/scroll-plugin · progress-plugin · matomo-plugin · fade-theme
                 @swup/a11y-plugin ^3.0   ← contraint par le verrou, pas par préférence
```

La dette créée est bornée mais elle change de **nature** plus que de taille : passer à Swup 4 n'a jamais été une montée de version npm, c'est un remplacement du pont (qui possède l'instanciation, le contrôleur Stimulus et le contrat `swup:pre-connect` sur lequel tout notre code repose), plus le renommage complet de la nomenclature d'événements, plus une majeure compatible pour chacun des plugins. Ce PR ajoute un quatrième plugin à ce lot : il **élargit** la migration d'un paquet, il ne la complique pas conceptuellement. Le coût marginal réel est faible — la dépendance transitive centrale du plugin est déjà dans l'arbre, seuls deux paquets feuilles s'ajoutent. Ce que je demande, en revanche, c'est que la migration Swup 4 devienne un ticket explicite plutôt qu'un sous-entendu : le jour où elle sera lancée, la reprise de focus et l'annonce vocale devront figurer dans son plan de test, sinon elles seront perdues silencieusement — une régression d'accessibilité qu'aucun outil du dépôt ne détecterait.

### Un défaut latent que ce PR promeut en risque, et l'absence totale de filet

Les deux pages qui posent une liste de conteneurs vide (`services/optimiser`, `services/ia`) sont l'illustration parfaite de la fragilité de cette architecture : un tableau vide ne déclenche pas le repli Twig — `[]` n'est pas nul — et se propage jusqu'au défaut de Swup, un sélecteur qui ne correspond à rien. Aujourd'hui, la conséquence est cosmétique. Une fois le plugin en place, elle devient une perte totale de la page pour un lecteur d'écran, parce que l'attribut d'occupation posé sur `<html>` n'a **qu'une seule transition de sortie, sur le chemin heureux**. C'est un défaut de complétude de machine à états, pas un bug de plugin : dès qu'on écrit un état global sur la racine du document, tous les chemins — échec de rendu, 404, coupure réseau — doivent le libérer.

```
NORMAL ──transitionStart──▶ OCCUPÉ  (<html aria-busy="true">)
                              │
              transitionEnd ──┴──▶ NORMAL          ← chemin heureux, le seul câblé
   échec de rendu / 404 / réseau ──▶ ⊘             ← pas de transition : état collé
```

Ce risque n'est détectable ni par `lint.twig`, ni par ESLint, ni par le build statique : c'est de la configuration exprimée dans un template, dont l'effet ne se manifeste que dans un navigateur. Et le dépôt n'a rien pour l'attraper — pipeline de lint statique riche (sept étapes), étape de tests unitaires commentée dans le workflow, `tests/` réduit à son amorce, et une cible `make test` qui vérifie en réalité que le build passe. La bonne nouvelle est que l'architecture Stenope offre la cible idéale d'un contrôle automatisé d'accessibilité : le build produit un site statique complet, sans serveur, sans base, sans fixtures — un corpus d'HTML final directement analysable, juste après l'étape de build qui existe déjà, dans le workflow de tests comme dans celui de preview par PR. Je recommande donc un garde-fou à deux étages : la règle de lint CSS pour l'invariant que ce PR rétablit (au plus près de la cause), et une cible `lint.a11y` sur le build pour tout ce qui est niveau DOM. Avec une réserve que je veux voir écrite noir sur blanc, parce qu'elle est contre-intuitive et que ce PR en fournit la démonstration : un outil automatisé rend « vert » sur l'absence de lien d'évitement, la règle correspondante étant satisfaite par la simple présence de `<main id="main">`. Une cible `lint.a11y` est un **cliquet anti-régression sur le sous-ensemble détectable**, jamais une preuve de conformité — le verdict de conformité reste un protocole manuel documenté, exécuté sur la preview du PR. Confondre les deux serait la pire évolution possible de ce chantier : croire couvert ce qui ne l'est pas.

## Impacts UX

### Un nouvel élément d'interface : le lien d'évitement

Le site gagne son premier composant réellement nouveau : un lien « Aller au contenu principal », posé en haut à gauche, en dehors du flux, et **révélé uniquement à la prise de focus**. Texte blanc sur `$color-primary`, souligné, `antikor bold` 16 px, padding 15/20 — il emprunte la typo et la couleur d'accent de la charte, donc il ne détonne pas visuellement même s'il n'a jamais été dessiné. Deux points de conception à connaître : il est masqué **par positionnement hors écran** et non par une boîte de 1 px, ce qui évite une zone cliquable fantôme par-dessus le logo du header (un défaut classique de ce composant, et un vrai risque ici puisque le logo occupe précisément ce coin) ; et une fois révélé, il **recouvre la zone du logo** le temps du focus — acceptable, c'est le comportement standard de ce motif, mais c'est un recouvrement à valider à l'œil, en particulier sur mobile où le header ne fait plus que 60 px de haut et où le lien en occupe donc une part bien plus large.

```
ÉTAT MASQUÉ — au chargement, et pour tout utilisateur souris/tactile
(hors viewport : aucune boîte, aucune zone cliquable, aucun décalage de mise en page)

        ┆ Aller au contenu principal ┆   ← hors écran, au-dessus du viewport
   ─────┆────────────────────────────┆──────────────────  limite du viewport
  ╔══════════════════════════════════════════════════════════════╗
  ║  ◼ elao            Services   Réalisations   Blog   Contact  ║ header 120px
  ╠══════════════════════════════════════════════════════════════╣
  ║   H1 de la page                                              ║


ÉTAT RÉVÉLÉ — 1er Tab depuis la barre d'adresse (clavier uniquement)

  ╔══════════════════════════════════════════════════════════════╗
  ║ ┌────────────────────────────┐ ← anneau violet 3px           ║
  ║ │▒┏━━━━━━━━━━━━━━━━━━━━━━━━┓▒│ ← halo blanc 2px              ║
  ║ │▒┃ Aller au contenu       ┃▒│                               ║
  ║ │▒┃ principal  ‾‾‾‾‾‾‾‾‾‾‾ ┃▒│   fond $color-primary         ║
  ║ │▒┗━━━━━━━━━━━━━━━━━━━━━━━━┛▒│   texte #fff souligné         ║
  ║ └────────────────────────────┘                               ║
  ║   ↑ recouvre la zone du logo    Réalisations   Blog   Contact ║
  ╠══════════════════════════════════════════════════════════════╣
  ║   Entrée → le focus part sur le contenu, Tab reprend ici ↓    ║
```

### Le double anneau de focus : un compromis visuel assumé

L'indicateur de focus se pose désormais sur **tous** les éléments interactifs du site : halo blanc de 2 px collé à l'élément, puis anneau `$color-primary` de 3 px juste à l'extérieur. Les deux étant contigus, cela se lit comme **un seul anneau bicolore de 5 px** — pas comme deux cercles concentriques — mais oui, c'est plus chargé qu'un liseré simple, et c'est validé en connaissance de cause. La raison est arithmétique et non stylistique : sur `$color-tertiary` (#f4756d), **aucune couleur unique n'atteint le 3:1 exigé** — le violet plafonne à 3,47 et le blanc à 2,77 sur les autres fonds, aucun des deux ne couvre les huit tokens de la charte. L'alternative « anneau simple + surcharges par contexte » a été écartée à raison : 18 composants à fond sombre à énumérer, une règle à maintenir à chaque nouveau composant, et une case qui reste vide de toute façon sur le corail. Introduire une couleur d'accent tierce (jaune, cyan vif) réglerait le contraste mais ajouterait à la charte un token qui n'y appartient pas, visible sur chaque page — plus coûteux, esthétiquement, que 2 px de halo. **Point crucial pour l'acceptabilité : rien ne change à la souris.** L'indicateur est en `:focus-visible`, donc un clic sur un lien, un bouton ou une vignette ne déclenche aucun anneau ; seuls les champs de saisie (les 3 `<select>` de `/social`, le `<textarea>` de la page signature) en montrent un au clic, ce qui est le comportement natif attendu d'un contrôle de formulaire. Le rendu « au repos » et le rendu à la souris du site sont donc strictement inchangés — ce PR est invisible pour la majorité des visiteurs.

### Impacts sur le parcours, et la dette de maquettes à rattraper

Pour un utilisateur clavier, le changement le plus perceptible n'est pas visuel mais comportemental : après chaque navigation, le **point de départ de la tabulation se déplace** du haut du DOM vers le début du nouveau contenu. Concrètement, on ne re-traverse plus le header à chaque page — c'est un gain net de plusieurs arrêts par navigation, et cela s'accompagne d'une annonce vocale du changement de page qui n'existait pas du tout. Effet de bord à documenter : après une navigation, le lien d'évitement n'est plus le prochain arrêt puisque le focus est déjà dans le contenu ; son utilité se concentre donc sur le chargement initial et les rechargements, ce qui est normal et sans conséquence. Pour un utilisateur souris, **absolument rien ne change** dans ce parcours. Enfin, un manque à nommer sans dramatiser : **il n'existe aujourd'hui aucune maquette de ces états** — ni l'anneau de focus, ni les deux états du lien d'évitement. L'audit d'origine l'avait relevé dans son bloc « Documentation » sans trancher, et ce PR comble le vide par du code, sans passer par le design. Ce n'est pas bloquant — les choix sont bons, la palette est respectée et le compromis est justifié — mais c'est une **dette à rattraper juste après le merge** : intégrer l'état focus et les deux états du lien d'évitement à la bibliothèque de composants, pour que les futurs composants les héritent au lieu de les réinventer, ou pire, de reposer un `outline: none`. C'est aussi le bon moment pour verser ces états au design system avant l'atelier palette annoncé pour le lot suivant, qui touchera les mêmes tokens.
