---
id: STOR-001
epic: blog
feature: format-entretien
slug: article-deux-modes
title: Un article de blog porte deux modes de lecture, récit et entretien, avec bascule
created: 2026-09-04
completed: 2026-09-04
status: Done
---

# Synthèse

## Résumé

Le blog sait désormais porter un article à **deux modes de lecture** — un récit et l'entretien intégral — que le lecteur bascule sans quitter la page. Le format s'active par `type: interview` dans l'en-tête ; tout autre article est rigoureusement inchangé, vérifié par comparaison des builds avant et après.

La rédaction écrit un seul fichier markdown. Un commentaire HTML `<!-- mode: … -->` sépare les zones, ce que Parsedown laisse traverser intact : aucun parsing supplémentaire, et aucun artefact dans un aperçu markdown. Dans le mode entretien, les citations sont re-découpées en remises en contexte et en prises de parole nommées, discrètement mises en forme dans le fil du corps — à l'opposé des blocs en exergue des articles classiques, qui restent disponibles dans le récit.

Les 18 critères d'acceptance du brief sont satisfaits. Un article de démonstration au contenu fictif, exclu de la production, donne à voir tous les cas de rendu et documente la syntaxe. Il est conservé aux côtés du guide de style : l'article réel de la série viendra en plus, non à sa place.

## Changements réalisés

**Modèle** — `Article::$type` reçoit une valeur par défaut et deux constantes ; `isInterview()` ; quatre propriétés remplies par les processors ; un objet de valeur `ReadingMode` et l'accesseur `getReadingModes()`. La propriété de front-matter `readingModes` est privée et renseignée par un setter : publique, elle aurait masqué l'accesseur en Twig.

**Découpe** — `ArticleReadingModesProcessor` (priorité -95) coupe le corps sur les commentaires de mode, déplace chaque mode dans le DOM du crawler plutôt que de réassigner la chaîne (`saveAll()` l'écraserait), préfixe les `id` de titres par le slug du mode et réécrit les liens internes en conséquence, y compris entre modes et depuis la zone commune.

**Blocs d'entretien** — `HtmlInterviewBlocksProcessor` (priorité -96) ne travaille que sur la propriété du mode entretien, ce qui réserve mécaniquement ces blocs à ce mode. Deux écritures du nom sont acceptées, `**Nom :**` et `<cite>Nom</cite>`, normalisées vers la même sortie. Les couleurs d'intervenants sont attribuées par ordre d'apparition, sans saisie.

**Sommaires** — deux instances de `TableOfContentProcessor`, une par mode, héritant de la définition du bundle pour suivre sa configuration de profondeur. Le sommaire principal est neutralisé.

**Rendu** — bloc d'annonce, contrôles `aria-pressed` dans un groupe nommé, un sommaire par mode à son emplacement d'origine, une `<section>` par mode avec rappel des contrôles en fin, notes et crédits communs.

**Bascule** — contrôleur Stimulus dédié : résolution du mode depuis l'adresse, `pushState` à chaque bascule, écoute de `popstate` et de `hashchange`. Le mode inactif est retiré du rendu par une règle conditionnée à l'absence de `no-js`, retirée en tête de `<head>` : pas de clignotement, et sans script les deux modes restent lisibles.

## Fichiers modifiés

| Fichier | |
|---|---|
| `src/Model/Article.php` | modifié |
| `src/Model/Article/ReadingMode.php` | nouveau |
| `src/Stenope/Processor/ArticleReadingModesProcessor.php` | nouveau |
| `src/Stenope/Processor/HtmlInterviewBlocksProcessor.php` | nouveau |
| `config/services.yaml` | modifié |
| `templates/blog/article.html.twig` | modifié |
| `templates/blog/_reading-modes.html.twig` | nouveau |
| `templates/blog/_reading-modes-controls.html.twig` | nouveau |
| `templates/blog/_table-of-content.html.twig` | nouveau |
| `assets/js/controllers/blog/reading_modes_controller.js` | nouveau |
| `assets/scss/components/_reading-modes.scss` | nouveau |
| `assets/scss/components/_interview.scss` | nouveau |
| `assets/scss/base/_variables.scss`, `assets/scss/style.scss` | modifiés |
| `content/blog/styleguide/entretien-deux-modes.md` | nouveau — référence du format |

15 fichiers, 1 472 lignes ajoutées, 20 retirées, en 15 commits.

## Tests et validation

- **Linting** : ✅ `make lint` — php-cs-fixer, PHPStan niveau max, Twig, YAML, ESLint, conteneur, composer.
- **Tests automatiques** : sans objet — le dépôt n'a pas de suite PHPUnit, arbitrage pris en début de story. `make build.content.without-images` construit 615 pages ; avec `INCLUDE_SAMPLES=0`, la valeur du workflow de déploiement, l'article de démonstration disparaît du résultat.
- **Validation manuelle** : ✅ les 14 contrôles navigateur du plan déroulés en 1280×900 et 390×844, captures à l'appui dans `.ignore/2026-09-04-article-deux-modes/`.
- **Non-régression** : ✅ site construit sur `main` et sur la branche, sorties comparées. Les 166 pages d'article ordinaires sont identiques, notes de bas d'article comprises. Aucune page supprimée, une seule ajoutée.
- **Revue de code** : ✅ quatre revues croisées (réutilisation, simplification, efficacité, altitude) puis une revue de correction. Deux défauts réels corrigés, onze simplifications appliquées.

## Notes

**Deux défauts trouvés en revue, corrigés.** Un lien de la zone commune vers une section homonyme aux deux modes pointait dans le vide : le préfixage ayant renommé les deux titres, laisser le lien intact — le parti pris initial — ne pouvait pas fonctionner. Et le contrôleur n'écoutait que `popstate`, alors qu'un lien de même page déclenche `hashchange` : un lien vers un mode inactif restait sans effet. Ce second correctif règle du même coup le lien de retour d'une note appelée depuis l'autre mode.

**Écarts au plan, tous documentés dans `dev.md`.** Les contrôles sont servis `disabled` et activés par le contrôleur — un bouton visiblement inerte vaut mieux qu'un bouton qui ne répond pas. La réécriture des liens couvre les liens inter-modes, que le plan ne prévoyait pas. La bascule depuis les contrôles de fin amène au début du nouveau mode, ce que le plan ne traitait pas. `$color-info` remplace le `#007695` de la charte, quasi jumeau et déjà présent dans les variables.

**L'article de démonstration est la documentation du format.** Le brief l'avait posé jetable et écartait toute documentation durable ; la décision a été révisée en fin de story. L'article a donc été réécrit sur un contenu entièrement fictif, sans lien ni mention de l'entretien qui a motivé la story, avec quatre intervenants pour couvrir toute la palette de couleurs. Sa zone commune porte la syntaxe complète, ses cas limites compris. C'est le fichier de référence à pointer.

**Deux corrections d'infrastructure au passage.** Les deux `TableOfContentProcessor` par mode repartaient de zéro et prenaient les valeurs du constructeur (`min_depth: 1`) là où `StenopeExtension` pose `2` — divergence réelle, sans effet visible aujourd'hui, corrigée par héritage de la définition du bundle. Et 48 articles ne déclarent aucune clé `type` : la propriété typée n'était pas initialisée pour eux, sans conséquence puisqu'elle n'était lue nulle part ; la valeur par défaut ferme ce cas.

## Reste à faire

La story est close **en l'état** : le format fonctionne et est démontré, mais deux points l'attendent avant d'ouvrir le format à d'autres articles.

- **Revue du rendu par Eva et la designer**, question ouverte du brief, explicitement bloquante avant généralisation. Le rendu a été revu et ajusté avec ogi ; les deux autres regards manquent.
- **L'article réel de la série « Les voix de notre veille »** reste à écrire dans ce format. Il s'ajoutera à l'article de démonstration, qui n'est plus destiné à disparaître.

Deux limites assumées, actées au brief et rendues visibles par l'article de démonstration : la liste de notes est commune aux deux modes, et chaque bascule ajoute une entrée à l'historique.

Un cas de rendu n'est plus donné à voir depuis que les deux modes portent une durée de lecture : celui d'un contrôle sans durée. Le gabarit le gère toujours, et l'en-tête de l'article documente la clé comme facultative.
