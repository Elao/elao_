---
title: 'Quand le moteur de recherche apprend de ses échecs'
date: '2026-03-24'
lastModified: ~
description: "Retour d'expérience sur la mise en place d'une boucle d'amélioration continue d'un moteur de recherche sémantique, par l'introduction de synonymes et la capture des recherches incertaines."
authors: [bleveque]
tableOfContent: true
tags: [recherche, IA, retour d'expérience]
thumbnail: content/images/blog/thumbnails/quand-le-moteur-de-recherche-apprend-de-ses-echecs.png
---

Nous travaillons sur une application destinée à des professionnels de la cuisine. Elle embarque un lexique de techniques culinaires — quelques centaines de termes précis, chacun accompagné d'une définition détaillée. L'utilisateur peut chercher un terme à partir de ce qu'il observe ou de ce qu'il souhaite réaliser.

Le problème auquel nous avons été confrontés est simple à formuler : les utilisateurs ne tapent presque jamais le bon mot. Un cuisinier qui cherche « faire revenir les oignons » s'attend à trouver un résultat. Pourtant, la technique indexée dans le lexique s'appelle **Suer**. De la même manière, « la sauce a des grumeaux » ne retourne rien, alors que les termes **Chinoiser** ou **Passer au tamis** figurent dans le référentiel.

Ce n'est pas un bug. Il s'agit d'un écart structurel entre le vocabulaire courant des utilisateurs et la terminologie métier du lexique. Les cuisiniers décrivent ce qu'ils voient ou ce qu'ils font, avec leurs propres mots. Le lexique, lui, utilise des termes techniques normalisés. Ce type d'écart se retrouve dans tous les domaines spécialisés — le juridique, le médical, l'industrie — dès qu'un référentiel métier est confronté à des utilisateurs qui n'en maîtrisent pas le vocabulaire.

Notre objectif était de réduire cet écart de manière progressive, sans demander aux utilisateurs de changer leurs habitudes, et sans imposer une charge de maintenance permanente aux administrateurs du lexique.

## Pourquoi la recherche classique ne suffit pas

La première approche que nous avons envisagée était une recherche full-text classique, telle que celle proposée nativement par PostgreSQL. Ce type de moteur fonctionne par correspondance de mots : il découpe la requête en termes, applique éventuellement un stemming (réduction des mots à leur racine), puis cherche des correspondances dans les textes indexés.

Le problème est que cette approche repose sur un prérequis implicite : il faut que la requête et le document partagent au moins quelques mots en commun. Or dans notre cas, « faire revenir les oignons » ne contient aucun mot présent dans la définition de **Suer** — « technique consistant à chauffer un aliment dans un corps gras à feu doux, sans coloration ». Le moteur ne peut tout simplement pas faire le lien.

Même en enrichissant les définitions avec des mots-clés manuels, le problème persiste. Les formulations des utilisateurs sont trop variées et trop imprévisibles pour être anticipées de manière exhaustive. Il nous fallait un moteur capable de comprendre le sens d'une phrase, et non pas seulement les mots qui la composent.

## La recherche sémantique comme réponse

Pour combler cet écart de vocabulaire, nous avons mis en place un moteur de recherche sémantique. Contrairement à une recherche classique qui compare des mots, la recherche sémantique compare des **sens**. Concrètement, chaque texte — qu'il s'agisse d'une requête utilisateur ou d'une définition du lexique — est transformé en une représentation mathématique (un vecteur) par un LLM. Deux phrases qui expriment la même idée se retrouvent proches dans cet espace, même si elles n'utilisent pas les mêmes mots.

Le fonctionnement se décompose en trois étapes. L'utilisateur saisit sa recherche, par exemple « faire revenir les oignons ». Cette phrase est d'abord transformée en vecteur par le LLM. Ensuite, ce vecteur est comparé à ceux de toutes les définitions du lexique pour identifier les plus proches. Enfin, une étape de réordonnancement affine les résultats : les 15 meilleurs candidats sont réévalués pour ne retourner que les 5 plus pertinents.

![Pipeline de recherche sémantique en 3 étapes](content/images/blog/2026/recherche-apprend-echecs/pipeline-recherche.svg)

Un point essentiel pour la suite : le vecteur de chaque définition est calculé à partir de la concaténation de son nom, de sa description, et de ses **synonymes**. C'est cette dernière composante qui rend le système améliorable. Chaque synonyme ajouté à une définition enrichit son vecteur et le rapproche des formulations courantes des utilisateurs. Le moteur de recherche n'est donc pas figé — il peut progresser au fil du temps.

Pour un corpus de quelques centaines de définitions, nous avons fait le choix de stocker les vecteurs directement dans notre base PostgreSQL existante plutôt que d'introduire un service dédié. Cela simplifie l'architecture et évite d'ajouter une dépendance supplémentaire.

## Capturer les échecs

Pour améliorer le système, il faut d'abord savoir où il échoue. Nous avons mis en place une classification automatique de chaque recherche en fonction du score de pertinence du meilleur résultat retourné. Trois cas de figure se présentent : la recherche aboutit à un résultat convaincant, et il n'y a rien à faire. Le résultat existe mais avec un score moyen — la recherche est alors considérée comme **incertaine**. Ou bien le score est trop bas et aucun résultat ne semble pertinent — la recherche est **non résolue**.

Les recherches incertaines et non résolues sont journalisées automatiquement. Nous ne stockons aucune donnée personnelle : uniquement la requête saisie, le score obtenu, et la source de recherche utilisée. Cette journalisation se fait de manière transparente pour l'utilisateur, sans impact sur son expérience.

Un point important concerne la déduplication. Si dix utilisateurs cherchent « faire revenir les oignons » sur une semaine, nous ne créons pas dix entrées distinctes. Un mécanisme de déduplication sur la requête normalisée permet d'incrémenter un compteur d'occurrences à chaque doublon. Ce compteur devient un signal de priorisation naturel : les expressions les plus fréquemment recherchées sans succès sont celles qui méritent d'être traitées en priorité, car leur résolution bénéficiera au plus grand nombre d'utilisateurs.

![Boucle d'apprentissage continue en 4 étapes](content/images/blog/2026/recherche-apprend-echecs/boucle-apprentissage.svg)

Ce journal de recherches infructueuses constitue la matière première de la boucle d'amélioration. Sans lui, nous n'aurions aucune visibilité sur les lacunes du lexique, et les enrichissements devraient être devinés par les administrateurs. Avec lui, ce sont les utilisateurs eux-mêmes qui, par leurs recherches, désignent les termes manquants.

## Le pipeline nocturne : de la recherche ratée à la suggestion de synonyme

Une fois les recherches infructueuses collectées, il faut les exploiter. Nous avons mis en place un pipeline automatisé qui s'exécute chaque nuit et transforme ces recherches en suggestions de synonymes. Ce pipeline se décompose en six étapes successives.

La première étape est le **chargement**. Le pipeline récupère un lot de recherches non résolues, triées par fréquence décroissante. Traiter en priorité les requêtes les plus récurrentes permet de maximiser l'impact de chaque cycle : résoudre une expression cherchée cinquante fois par semaine bénéficie à davantage d'utilisateurs qu'une expression apparue une seule fois.

Vient ensuite le **filtrage**. Toutes les recherches journalisées ne sont pas exploitables. Certaines sont des fautes de frappe incompréhensibles, d'autres sont complètement hors domaine — par exemple « météo demain » dans un lexique de techniques culinaires. Un LLM évalue chaque requête et écarte celles qui ne correspondent pas au domaine du lexique. Ce tri évite de gaspiller du temps de traitement sur des impasses.

La troisième étape est le **clustering**. Parmi les requêtes restantes, beaucoup sont des variantes d'une même intention. « Faire revenir les oignons », « faire sauter à feu vif » et « cuire dans l'huile sans colorer » expriment des idées proches. Le pipeline regroupe ces variantes en comparant la similarité entre leurs vecteurs respectifs. L'algorithme utilisé est un simple chaînage : chaque requête est comparée au représentant de chaque groupe existant, et si la similarité est suffisante, elle y est rattachée. Un seuil élevé garantit que seules les requêtes réellement similaires sont regroupées. À l'issue de cette étape, une seule suggestion sera générée par groupe, ce qui évite de proposer des doublons aux administrateurs.

L'étape suivante est le **matching**. Pour chaque groupe de requêtes, le pipeline identifie la définition du lexique la plus proche en comparant les vecteurs. Un seuil minimal empêche de rattacher une requête à une définition sans rapport réel — si aucune définition n'est suffisamment proche, le groupe est écarté plutôt que de produire une suggestion hasardeuse.

Puis vient la **génération**. Le LLM propose un synonyme concret pour chaque paire groupe-définition retenue. Il produit une sortie structurée comprenant le synonyme lui-même, un score de confiance (de 0 à 100), et un raisonnement expliquant pourquoi ce terme correspond à la définition cible. Le score de confiance et le raisonnement seront ensuite présentés aux administrateurs pour les aider à prendre leur décision.

Enfin, la **déduplication**. Avant d'enregistrer une suggestion, le pipeline vérifie que le synonyme proposé n'existe pas déjà sur la définition cible. Cela évite de soumettre aux administrateurs des suggestions redondantes avec des synonymes déjà en place.

En termes de contraintes d'exécution, le pipeline fonctionne avec un lot limité de requêtes par cycle (quelques dizaines), un budget temps contraint, et un verrouillage qui empêche deux exécutions simultanées. Ces garde-fous garantissent un fonctionnement prévisible et maîtrisé. Sur un cycle typique, un lot de cinquante recherches aboutit à une dizaine de suggestions exploitables après filtrage, clustering et déduplication.

## La supervision humaine : pourquoi l'IA ne décide pas seule

Le pipeline nocturne produit des suggestions, pas des décisions. Chaque synonyme proposé doit passer par une validation humaine avant d'être ajouté au lexique. Ce choix est délibéré : un LLM peut proposer un rapprochement pertinent entre une recherche et une définition, mais il ne dispose pas de l'expertise métier nécessaire pour trancher dans les cas ambigus.

Prenons un exemple concret. Un utilisateur recherche « la sauce est trop épaisse ». Le pipeline pourrait suggérer de rattacher cette expression à la technique **Réduire**, alors qu'un expert culinaire sait que la bonne cible est **Détendre** — c'est-à-dire l'opération inverse, qui consiste à ajouter un liquide pour fluidifier une préparation. Ce type de nuance ne peut être résolu que par quelqu'un qui maîtrise le domaine.

L'interface de validation présente à l'administrateur toutes les informations nécessaires pour prendre sa décision rapidement : la requête utilisateur d'origine (ou le cluster de requêtes regroupées), le synonyme suggéré, la définition cible avec sa description, le score de confiance du LLM, et le raisonnement qui a conduit à cette suggestion. L'objectif est de permettre une décision en quelques secondes, sans avoir à aller chercher du contexte ailleurs.

Trois actions sont possibles. **Valider** : le synonyme est ajouté à la définition, et l'embedding est automatiquement recalculé. **Rejeter** : la suggestion est écartée, avec possibilité d'indiquer un motif — ce qui permettra à terme d'améliorer le filtrage du pipeline. **Réassigner** : le synonyme est pertinent, mais rattaché à la mauvaise définition. L'administrateur peut le rediriger vers la bonne cible sans avoir à ressaisir quoi que ce soit.

Le rejet n'est pas définitif. Si le même besoin remonte régulièrement via de nouvelles recherches infructueuses, le pipeline finira par générer une nouvelle suggestion, potentiellement plus pertinente que la précédente. Le système est conçu pour être insistant sans être bloquant : il continue de signaler les lacunes tant qu'elles ne sont pas comblées.

## Le cercle vertueux : comment le synonyme améliore la recherche

Lorsqu'un administrateur valide une suggestion, plusieurs choses se produisent de manière transparente. Le synonyme est ajouté à la définition dans une table dédiée. Cette insertion déclenche automatiquement un marquage de l'embedding existant comme périmé. L'embedding est alors recalculé en intégrant le nouveau synonyme dans le texte concaténé (nom + description + synonymes). Il n'y a aucune action manuelle supplémentaire à effectuer : la chaîne est entièrement automatisée.

Reprenons notre exemple fil rouge. Avant enrichissement, un utilisateur qui tape « faire revenir les oignons » obtient un score trop bas. Aucun résultat convaincant n'est retourné. La recherche est journalisée, passe par le pipeline nocturne, et une suggestion de synonyme « faire revenir » est générée pour la technique **Suer**. L'administrateur valide. À partir de ce moment, la même recherche retourne **Suer** parmi les premiers résultats. L'écart entre le vocabulaire de l'utilisateur et celui du lexique a été réduit d'un cran.

![Avant / après : impact de l'ajout d'un synonyme](content/images/blog/2026/recherche-apprend-echecs/avant-apres.svg)

Ce qui rend ce mécanisme puissant, c'est son caractère cumulatif. Chaque synonyme validé ne corrige pas seulement la recherche qui l'a généré — il améliore aussi toutes les recherches sémantiquement proches. Ajouter « faire revenir » sur **Suer** rapprochera également des requêtes comme « revenir à feu vif » ou « faire sauter sans colorer », même si ces formulations exactes n'ont jamais été soumises. Le vecteur enrichi capte un champ sémantique plus large que le seul synonyme ajouté.

## Enrichissement manuel en complément

Le pipeline nocturne n'est pas le seul canal d'enrichissement du lexique. Les experts métier peuvent ajouter des synonymes directement depuis l'interface d'administration, sans attendre qu'une recherche infructueuse déclenche le processus. C'est particulièrement utile lorsqu'un expert connaît d'avance les formulations courantes pour un terme — par exemple, il sait que les cuisiniers parlent de « mouiller » pour désigner la technique **Déglacer**, ou de « faire tomber » pour **Compoter**.

Le mécanisme sous-jacent est strictement le même que pour les synonymes issus du pipeline : une détection de doublons empêche d'ajouter un synonyme déjà existant, et l'embedding de la définition est automatiquement recalculé après chaque ajout ou suppression. Il n'y a pas de distinction entre un synonyme ajouté manuellement et un synonyme issu d'une suggestion validée — les deux alimentent la même mécanique et produisent le même effet sur la qualité de recherche.

Ces deux canaux sont complémentaires. Le pipeline nocturne détecte des lacunes que les administrateurs n'auraient pas anticipées, en s'appuyant sur le comportement réel des utilisateurs. L'enrichissement manuel permet d'aller plus vite sur des cas évidents, sans attendre qu'un volume suffisant de recherches infructueuses ne s'accumule. Ensemble, ils garantissent que le lexique s'enrichit à la fois par l'usage et par l'expertise.

## Ce qu'on retient

Pour un corpus de quelques centaines de définitions, les coûts d'exploitation de l'ensemble du système restent modestes — de l'ordre de quelques centimes par recherche et de moins d'un dollar par nuit pour le pipeline de génération de suggestions. Le ratio entre l'investissement et la valeur produite est très favorable : chaque cycle d'analyse améliore durablement l'expérience de recherche pour l'ensemble des utilisateurs.

L'architecture décrite dans cet article n'est pas spécifique à un lexique culinaire. Elle s'applique à tout domaine où un référentiel métier spécialisé est confronté à des utilisateurs qui n'en maîtrisent pas le vocabulaire — le juridique, le médical, l'industrie, ou tout autre secteur disposant d'une terminologie normalisée. Les briques techniques (embeddings, LLM, base vectorielle) sont interchangeables ; ce qui compte, c'est l'assemblage en une boucle de feedback continue.

Le vrai différenciateur de cette approche n'est pas le LLM utilisé ni le modèle d'embedding choisi. C'est la boucle elle-même : capturer automatiquement les échecs, les analyser pour en extraire des suggestions concrètes, les soumettre à une validation humaine, et enrichir l'index à chaque validation. Le modèle d'IA changera, les seuils seront ajustés. Mais la boucle continuera de tourner — et chaque recherche infructueuse rendra le système un peu plus intelligent.
