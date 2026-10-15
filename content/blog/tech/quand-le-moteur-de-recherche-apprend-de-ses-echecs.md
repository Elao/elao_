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

Nous travaillons sur une application destinée à des professionnels de la cuisine. Elle embarque un lexique de techniques culinaires, quelques centaines de termes précis, chacun accompagné d'une définition détaillée. L'utilisateur peut chercher un terme à partir de ce qu'il observe ou de ce qu'il souhaite réaliser.

Le problème auquel nous avons été confrontés est simple à formuler : les utilisateurs ne tapent presque jamais le bon mot. Un cuisinier qui cherche « faire revenir les oignons » s'attend à trouver un résultat. Pourtant, la technique indexée dans le lexique s'appelle **Suer**. De la même manière, « la sauce a des grumeaux » ne retourne rien, alors que les termes **Chinoiser** ou **Passer au tamis** figurent dans le référentiel.

Ce n'est pas un bug. Il s'agit d'un écart structurel entre le vocabulaire courant des utilisateurs et la terminologie métier du lexique. Les cuisiniers décrivent ce qu'ils voient ou ce qu'ils font, avec leurs propres mots. Le lexique, lui, utilise des termes techniques normalisés. Ce type d'écart se retrouve dans tous les domaines spécialisés (juridique, médical, industrie…) dès qu'un référentiel métier est confronté à des utilisateurs qui n'en maîtrisent pas le vocabulaire.

Notre objectif était de réduire cet écart de manière progressive, sans demander aux utilisateurs de changer leurs habitudes, et sans imposer une charge de maintenance permanente aux administrateurs du lexique.

## La recherche sémantique comme point de départ

Pour combler cet écart de vocabulaire, nous avons mis en place un moteur de recherche sémantique. Contrairement à une recherche classique qui compare des mots, la recherche sémantique compare des **sens**. Concrètement, chaque texte (requête utilisateur ou définition du lexique) est transformé en une représentation mathématique appelée *embedding* (un vecteur numérique) par un modèle spécialisé. Deux phrases qui expriment la même idée se retrouvent proches dans cet espace, même si elles n'utilisent pas les mêmes mots.

Le fonctionnement se décompose en trois étapes. L'utilisateur saisit sa recherche, par exemple « faire revenir les oignons ». Cette phrase est d'abord transformée en vecteur par le modèle d'embedding. Ensuite, ce vecteur est comparé à ceux de toutes les définitions du lexique pour identifier les plus proches. Enfin, une étape de réordonnancement affine les résultats : les 15 meilleurs candidats sont réévalués pour ne retourner que les 5 plus pertinents.

![Pipeline de recherche sémantique en 3 étapes](content/images/blog/2026/recherche-apprend-echecs/pipeline-recherche.svg)

Un point essentiel pour la suite : le vecteur de chaque définition est calculé à partir de la concaténation de son nom, de sa description, et de ses **synonymes**. C'est cette dernière composante qui rend le système améliorable. Chaque synonyme ajouté à une définition enrichit son vecteur et le rapproche des formulations courantes des utilisateurs. Le moteur de recherche n'est donc pas figé : il peut progresser au fil du temps.

## Capturer les échecs

Pour améliorer le système, il faut d'abord savoir où il échoue. On classe automatiquement chaque recherche en fonction du score de pertinence du meilleur résultat retourné :

- Le résultat est convaincant : rien à faire.
- Le score est moyen : la recherche est **incertaine**.
- Le score est trop bas : la recherche est **non résolue**.

Les recherches incertaines et non résolues sont journalisées automatiquement. On ne stocke aucune donnée personnelle : uniquement la requête saisie, le score obtenu, et la source de recherche utilisée.

Un point important : la déduplication. Si dix utilisateurs cherchent « faire revenir les oignons » sur une semaine, on ne crée pas dix entrées distinctes. Un mécanisme de déduplication sur la requête normalisée incrémente un compteur d'occurrences à chaque doublon. Ce compteur devient un signal de priorisation naturel : les expressions les plus fréquemment recherchées sans succès sont celles qui méritent d'être traitées en priorité.

![Boucle d'apprentissage continue en 4 étapes](content/images/blog/2026/recherche-apprend-echecs/boucle-apprentissage.svg)

Ce journal de recherches infructueuses constitue la matière première de la boucle d'amélioration. Sans lui, nous n'aurions aucune visibilité sur les lacunes du lexique, et les enrichissements devraient être devinés par les administrateurs. Avec lui, ce sont les utilisateurs eux-mêmes qui, par leurs recherches, désignent les termes manquants.

## Le pipeline nocturne : de la recherche ratée à la suggestion de synonyme

Une fois les recherches infructueuses collectées, il faut les exploiter. On a mis en place un pipeline automatisé qui s'exécute chaque nuit et transforme ces recherches en suggestions de synonymes, en six étapes.

![Pipeline nocturne en 6 étapes](content/images/blog/2026/recherche-apprend-echecs/pipeline-nocturne.svg)

### Chargement

Le pipeline récupère un lot de recherches non résolues, triées par fréquence décroissante. Traiter en priorité les requêtes les plus récurrentes permet de maximiser l'impact de chaque cycle : résoudre une expression cherchée cinquante fois par semaine bénéficie à davantage d'utilisateurs qu'une expression apparue une seule fois.

### Filtrage

Toutes les recherches journalisées ne sont pas exploitables. Certaines sont des fautes de frappe incompréhensibles, d'autres sont complètement hors domaine, comme « météo demain » dans un lexique de techniques culinaires. Un LLM évalue chaque requête et écarte celles qui ne correspondent pas au domaine du lexique.

### Clustering

Parmi les requêtes restantes, beaucoup sont des variantes d'une même intention. « Faire revenir les oignons », « faire sauter à feu vif » et « cuire dans l'huile sans colorer » expriment des idées proches. Le pipeline regroupe ces variantes en comparant la similarité entre leurs vecteurs respectifs via un algorithme de chaînage simple.

Un seuil élevé garantit que seules les requêtes réellement similaires sont regroupées. À l'issue de cette étape, une seule suggestion sera générée par groupe, ce qui évite de proposer des doublons aux administrateurs.

### Matching

Pour chaque groupe de requêtes, le pipeline identifie la définition du lexique la plus proche en comparant les vecteurs. Un seuil minimal empêche de rattacher une requête à une définition sans rapport réel : si aucune définition n'est suffisamment proche, le groupe est écarté plutôt que de produire une suggestion hasardeuse.

### Génération

Le LLM propose un synonyme concret pour chaque paire groupe-définition retenue. Il produit une sortie structurée : le synonyme lui-même, un score de confiance (de 0 à 100), et un raisonnement expliquant pourquoi ce terme correspond à la définition cible. Ces éléments seront présentés aux administrateurs pour les aider à prendre leur décision.

### Déduplication

Avant d'enregistrer une suggestion, le pipeline vérifie que le synonyme proposé n'existe pas déjà sur la définition cible. Ça évite de soumettre aux administrateurs des suggestions redondantes.

### Garde-fous

Le pipeline fonctionne avec un lot limité de requêtes par cycle (quelques dizaines), un budget temps contraint, et un verrouillage qui empêche deux exécutions simultanées. En pratique, un lot de cinquante recherches aboutit à une dizaine de suggestions exploitables après filtrage, clustering et déduplication.

## La supervision humaine : pourquoi l'IA ne décide pas seule

Le pipeline nocturne produit des suggestions, pas des décisions. Chaque synonyme proposé passe par une validation humaine avant d'être ajouté au lexique. Ce choix est délibéré : un LLM peut proposer un rapprochement pertinent, mais il ne dispose pas de l'expertise métier pour trancher dans les cas ambigus.

Prenons un exemple concret. Un utilisateur recherche « la sauce est trop épaisse ». Le pipeline pourrait suggérer de rattacher cette expression à la technique **Réduire**, alors qu'un expert culinaire sait que la bonne cible est **Détendre**, c'est-à-dire l'opération inverse, qui consiste à ajouter un liquide pour fluidifier une préparation. Ce type de nuance ne peut être résolu que par quelqu'un qui maîtrise le domaine.

L'interface de validation présente à l'administrateur tout ce dont il a besoin pour décider rapidement : la requête utilisateur d'origine (ou le cluster de requêtes regroupées), le synonyme suggéré, la définition cible avec sa description, le score de confiance du LLM, et le raisonnement derrière la suggestion. L'objectif : une décision en quelques secondes, sans aller chercher du contexte ailleurs.

Trois actions sont possibles :

- **Valider** : le synonyme est ajouté à la définition, et l'embedding est automatiquement recalculé.
- **Rejeter** : la suggestion est écartée, avec possibilité d'indiquer un motif (ce qui permettra à terme d'améliorer le filtrage du pipeline).
- **Réassigner** : le synonyme est pertinent, mais rattaché à la mauvaise définition. L'administrateur le redirige vers la bonne cible sans avoir à ressaisir quoi que ce soit.

Le rejet n'est pas définitif. Si le même besoin remonte régulièrement, le pipeline finira par générer une nouvelle suggestion, potentiellement plus pertinente. Le système est conçu pour être insistant sans être bloquant.

## Le cercle vertueux : comment le synonyme améliore la recherche

Quand un administrateur valide une suggestion, tout s'enchaîne automatiquement. Le synonyme est ajouté à la définition, l'embedding existant est marqué comme périmé, puis recalculé en intégrant le nouveau synonyme dans le texte concaténé (nom + description + synonymes). Aucune action manuelle supplémentaire.

Reprenons notre exemple fil rouge. Avant enrichissement, un utilisateur qui tape « faire revenir les oignons » obtient un score trop bas. Aucun résultat convaincant n'est retourné. La recherche est journalisée, passe par le pipeline nocturne, et une suggestion de synonyme « faire revenir » est générée pour la technique **Suer**. L'administrateur valide. À partir de ce moment, la même recherche retourne **Suer** parmi les premiers résultats. L'écart entre le vocabulaire de l'utilisateur et celui du lexique a été réduit d'un cran.

![Avant / après : impact de l'ajout d'un synonyme](content/images/blog/2026/recherche-apprend-echecs/avant-apres.svg)

Ce qui rend ce mécanisme puissant, c'est son caractère cumulatif. Chaque synonyme validé ne corrige pas seulement la recherche qui l'a généré : il améliore également toutes les recherches sémantiquement proches. Ajouter « faire revenir » sur **Suer** rapprochera également des requêtes comme « revenir à feu vif » ou « faire sauter sans colorer », même si ces formulations exactes n'ont jamais été soumises. Le vecteur enrichi capte un champ sémantique plus large que le seul synonyme ajouté.

Le pipeline nocturne n'est pas le seul canal d'enrichissement. Les experts métier peuvent aussi ajouter des synonymes directement depuis l'interface d'administration, quand ils connaissent d'avance les formulations courantes pour un terme. Par exemple, un expert sait que les cuisiniers parlent de « mouiller » pour désigner la technique **Déglacer**. Les deux canaux sont complémentaires : le pipeline détecte les lacunes à partir du comportement réel des utilisateurs, l'enrichissement manuel permet d'aller plus vite sur les cas évidents.

## Ce qu'on retient

Pour un corpus de quelques centaines de définitions, les coûts d'exploitation restent modestes : quelques centimes par recherche, moins d'un dollar par nuit pour le pipeline de suggestions. Le ratio entre l'investissement et la valeur produite est très favorable.

Cette architecture n'est pas spécifique à un lexique culinaire. Elle s'applique à tout domaine où un référentiel métier spécialisé est confronté à des utilisateurs qui n'en maîtrisent pas le vocabulaire : juridique, médical, industrie… Les briques techniques (embeddings, LLM, base vectorielle) sont interchangeables ; ce qui compte, c'est l'assemblage en boucle de feedback continue.

Pour reproduire cette approche, les ingrédients sont relativement accessibles : un corpus structuré avec des définitions, un modèle d'embedding pour la recherche vectorielle, un LLM pour le filtrage et la génération de suggestions, et un pipeline batch pour orchestrer le tout. Le vrai travail n'est pas technique : c'est de mettre en place la boucle de capture des échecs et de s'assurer qu'un humain reste dans la boucle de validation.
