---
title: "Paralléliser des tâches avec Claude Code : comparatif de 4 approches"
date: "2026-03-09"
tableOfContent: 2
description: |
  Retour d'expérience sur 4 approches de parallélisation de tâches dans Claude Code : 
  séquentielle, batch, Agent Teams et Task Worker Pool.
thumbnail: content/images/blog/thumbnails/paralleliser-taches-claude-code.jpg
banner: content/images/blog/thumbnails/paralleliser-taches-claude-code.jpg
tags: ["claude code", "ia", "automatisation", "productivité"]
authors: ["mcolin"]
#tweetId: ""
---

## Le contexte

Sur l'un de nos projets, nous avons une dizaine de commandes d'import de flux de données externes. Ces commandes reposent toutes sur le même modèle : un adaptateur qui fait correspondre les nomenclatures du flux source avec les enums internes de l'application. Quand un partenaire ajoute une nouvelle valeur dans son flux, l'import échoue avec une erreur de mapping. La correction est mécanique : ouvrir l'adaptateur, trouver l'enum cible, ajouter le cas manquant.

![Schéma du problème de mapping : quand une valeur inconnue apparaît dans le flux, l'import échoue](content/images/blog/2026/paralleliser-taches-claude-code/schema-contexte.svg)

De plus, les partenaires qui génèrent ces flux exposent rarement une documentation avec la liste complète de leur nomenclature. Le mapping se fait donc de façon empirique : on découvre les valeurs au fil de l'eau, quand elles apparaissent dans les données. Au gré de l'évolution des données publiées dans les flux, de nouvelles valeurs de nomenclature peuvent surgir à tout moment. D'où le besoin de mettre à jour fréquemment nos adaptateurs d'import — une à plusieurs fois par semaine — et c'est franchement rébarbatif pour les développeurs.

### Pourquoi Claude Code plutôt qu'un script ?

On pourrait se dire qu'un bon vieux script shell ferait l'affaire. Sauf que la correction n'est pas un simple copier-coller : il faut **comprendre** le log d'erreur pour identifier la valeur inconnue, puis **choisir** le bon enum case à associer. Les nomenclatures et les enums sont des catégories avec des intitulés compréhensibles par un humain — des noms de secteurs d'activité, des types de contrat, des niveaux de qualification. Un LLM est capable de comprendre le sens des deux côtés et de faire le rapprochement sémantique : il sait que "CDI de chantier" correspond à `PermanentContract`, que "Achats & Supply Chain" se rattache à `Purchasing`. C'est cette capacité d'interprétation qui rend la tâche difficilement scriptable mais parfaitement adaptée à un agent IA.

Claude Code sait lire les logs d'erreur, ouvrir le fichier concerné, identifier l'enum cible, ajouter le cas manquant avec le bon mapping, et relancer pour vérifier. C'est exactement le genre de tâche répétitive et bien cadrée qui se prête à l'automatisation via [Claude Code](https://docs.anthropic.com/en/docs/claude-code). J'ai donc créé une **slash command** personnalisée (`/fix-import`) qui orchestre tout le processus. Puis j'ai itéré sur la partie la plus lente : l'exécution parallèle des imports.

## Les slash commands Claude Code

Avant de parler parallélisation, un mot sur le mécanisme utilisé. Claude Code permet de définir des **slash commands** personnalisées sous forme de fichiers Markdown dans `.claude/commands/`. Ces fichiers contiennent des instructions en langage naturel que Claude suit comme un workflow. À noter que ce mécanisme a depuis été fusionné dans le système **Skills** (`.claude/skills/`), mais les fichiers dans `.claude/commands/` continuent de fonctionner.

```tree
.claude/commands/
  fix-import.md        # v1 : séquentiel
  fix-import-batch.md  # v2 : batch parallèle
  fix-import-teams.md  # v3 : Agent Teams
  fix-import-task.md   # v4 : Task Worker Pool
```

Chaque fichier décrit le workflow complet : récupérer la liste des imports, les exécuter, analyser les erreurs, corriger le code, relancer. La seule chose qui change entre les versions, c'est **comment** les imports sont exécutés.

Soyons honnêtes : la version séquentielle était tout à fait acceptable en termes de durée d'exécution (environ 4 minutes), et le gain apporté par la parallélisation dans ce cas précis n'est pas extraordinaire. Mais l'objectif n'était pas forcément de gagner beaucoup de temps : c'était d'avoir un cas d'usage simple et concret me permettant d'explorer les différentes pistes de parallélisation offertes par Claude Code.

## Approche 1 : séquentielle

La première version est la plus simple. Claude exécute chaque commande d'import l'une après l'autre dans la conversation principale :

```tree
Pour chaque commande d'import :
  1. Exécuter la commande
  2. Analyser la sortie
  3. Passer à la suivante
```

C'est l'approche naïve, celle qu'on écrit naturellement et qui fonctionne du premier coup. Tous les imports doivent être exécutés car plusieurs flux utilisent le même adaptateur (même fournisseur de flux). Cela permet de réunir toutes les erreurs par adaptateur et de les dédupliquer avant de mettre à jour les correspondances. À noter que les temps d'exécution sont très hétérogènes : certains imports sont très lents car le flux contient beaucoup de données, d'autres sont quasi instantanés car le flux est très petit.

La slash command est très directe — on décrit simplement le workflow étape par étape en langage naturel :

```markdown
### Step 1: Get the list of import commands
Run the following command to get all import commands:
docker-compose exec app sh -c "bin/console app:import:list"

### Step 2: Run each import command
For each import command extracted in Step 1, run it inside Docker:
docker-compose exec app sh -c "bin/console {command_line}"
After running each command, carefully read the full output
looking for ERROR log lines that indicate missing enum mappings.

### Step 3: Identify enum mapping errors
Look for error log lines matching these patterns: [...]

### Step 4: Fix each mapping error
[...]
```

Claude Code suit ces instructions comme un script, mais avec la capacité de s'adapter aux imprévus.

### Avantages

- Simple à écrire et à débugger
- Pas de problème de concurrence
- L'agent principal a accès direct à toute la sortie
- Permet de dédupliquer les erreurs par adaptateur avant correction

### Inconvénients

- **Très lent** : chaque import prend 5 à 90 secondes, et on attend chacun séquentiellement
- La fenêtre de contexte de l'agent se remplit avec les sorties volumineuses de chaque import
- Pour 10 imports, le temps total est la somme de tous les temps individuels

**Temps typique :** ~4 minutes pour 10 imports.

## Approche 2 : batch parallèle

J'ai naturellement décrit à Claude Code mon souhait de paralléliser les imports via des sous-agents, en limitant le nombre d'agents simultanés pour ne pas avoir trop de processus PHP qui tournent en même temps. Chaque sous-agent devait exécuter un import, extraire les erreurs de mapping et les remonter à l'agent principal qui se chargerait de modifier le code.

Claude Code m'a tout de suite proposé cette approche par batch : découper les imports en lots et traiter chaque lot en parallèle via l'outil `Task` (renommé `Agent` depuis la version 2.1.63 de Claude Code).

```markdown
Découper les 10 imports en lots de 4 :
  Lot 1 : [import-1, import-2, import-3, import-4]  → 4 agents en parallèle
  Attendre que le lot 1 finisse
  Lot 2 : [import-5, import-6, import-7, import-8]  → 4 agents en parallèle
  Attendre que le lot 2 finisse
  Lot 3 : [import-9, import-10]                      → 2 agents en parallèle
  Attendre que le lot 3 finisse
```

Cette séparation en agent principal / sous-agents a un autre avantage intéressant : elle permet d'utiliser un modèle plus simple (Haiku) pour les sous-agents dont la tâche est triviale (exécuter une commande et extraire des lignes d'erreur), tout en conservant un modèle plus puissant (Opus) sur l'agent principal pour la partie intelligente — choisir le bon enum case pour chaque mapping. On ne peut pas changer de modèle au sein d'un même agent sans perdre le contexte, cette architecture résout donc élégamment le problème.

La limite de 4 processus simultanés est une contrainte technique liée aux ressources disponibles dans notre environnement Docker.

La clé technique du prompt est dans cette instruction :

```markdown
### Step 2: Run import commands in parallel batches

Split the import commands into **batches of 4**.

For each batch, launch one **Bash agent** (via the Task tool)
per import command, **all in a single message** so they run
concurrently. Wait for the batch to complete before launching
the next one.

**Use `model: "haiku"` for all import agents** — they only need
to run a command and extract ERROR lines, which is a trivial task
that doesn't require a powerful model.
```

Le point important ici est le **"all in a single message"** : c'est ce qui indique à Claude Code de lancer les sous-agents en parallèle plutôt que séquentiellement. Sans cette précision, il les lancerait un par un.

### Avantages

- Coût tokens réduit grâce à Haiku pour les workers
- La sortie de chaque import reste dans le contexte du sous-agent, pas dans celui de l'agent principal
- Simple à comprendre

### Inconvénients

- **Sous-optimal** : si un lot ne contient que des imports rapides, ses workers finissent vite puis attendent les lots plus lents sans rien faire
- L'agent principal est bloqué entre chaque lot
- **Gain modeste** : environ 30% plus rapide que le séquentiel dans notre cas. La création et l'initialisation de chaque sous-agent, l'attente de fin de lot (bloquée par l'import le plus lent) et la collecte des résultats entre les lots génèrent un overhead qui grignote une bonne partie du gain brut de la concurrence.

**Temps typique :** ~3 minutes pour 10 imports.

## Approche 3 : Agent Teams

Claude Code propose une fonctionnalité expérimentale appelée **Agent Teams** (`CLAUDE_CODE_EXPERIMENTAL_AGENT_TEAMS=1`). J'avais conscience que cette approche serait probablement overkill pour mon cas d'usage, mais c'était l'opportunité de tester cette nouvelle fonctionnalité et de découvrir ses possibilités et ses limites sur un sujet simple.

### Comment ça fonctionne

Agent Teams permet de créer une véritable **équipe d'agents** orchestrée par un agent leader. Le leader crée une équipe, définit des tâches dans une liste partagée, puis lance des agents "teammates" qui vont collaborer de façon autonome. Chaque teammate a accès à la liste de tâches commune, peut s'en attribuer une, l'exécuter, puis envoyer un rapport au leader via un système de messagerie intégrée (`SendMessage`). Le leader supervise l'avancement et collecte les résultats.

```markdown
1. Créer une équipe "import-flux" (Teammate spawnTeam)
2. Créer 10 tâches (TaskCreate), une par import
3. Lancer 4 teammates (Task avec team_name)
4. Chaque teammate :
   - Consulte la liste de tâches (TaskList)
   - Prend la première tâche disponible
   - Exécute l'import
   - Envoie le rapport au leader (SendMessage)
   - Marque la tâche comme terminée
   - Reprend une tâche disponible
5. Le leader collecte les résultats
6. Shutdown de l'équipe (Teammate cleanup)
```

C'est le pattern **worker pool** classique : les workers s'auto-assignent les tâches et n'attendent jamais inutilement. Dès qu'un import court finit, le worker enchaîne immédiatement sur le suivant.

Voici les instructions clés du prompt pour cette approche :

```markdown
### Step 2: Create the team and task list

1. **Create the team** using `Teammate spawnTeam` with team name
   "import-flux".

2. **Create one task per import command** using `TaskCreate`.
   Each task description contains les instructions pour le teammate:
   "Run this import command and report any errors found. [...]
   Send a message to the team lead with all ERROR lines.
   Mark the task as completed.
   Check TaskList for the next available task."

### Step 3: Spawn 4 worker teammates

Spawn **4 teammates** using the `Task` tool with
`team_name: "import-flux"`.
Launch all 4 in a **single message** so they start concurrently.
- **model**: "haiku"
- Each teammate: check the task list, claim a task, execute it,
  report via SendMessage, repeat until no more tasks.

### Step 6: Shutdown the team

Send a `shutdown_request` to all workers.
Once all have shut down, clean up with `Teammate cleanup`.
```

La complexité vient du cycle de vie de l'équipe : il faut créer l'équipe, créer les tâches, spawner les teammates, attendre les messages, puis faire le shutdown proprement.

### Avantages

- Utilisation optimale des slots de concurrence
- Pas de temps mort entre les lots
- Communication structurée via `SendMessage`
- Permet d'entrevoir des usages plus complexes : coordination multi-agents sur des tâches nécessitant de la collaboration, pas seulement de la distribution

### Inconvénients

- Nécessite un feature flag expérimental
- Plus complexe à mettre en place : création d'équipe, gestion du cycle de vie, shutdown
- **Coût en tokens énorme** : plusieurs centaines de milliers de tokens consommés. La coordination entre agents, les échanges de messages et la gestion de la liste de tâches partagée génèrent un overhead considérable, totalement disproportionné pour des tâches aussi simples
- Un agent sur les 4 n'a traité **aucune tâche** sans que Claude Code soit en mesure de m'expliquer pourquoi — signe que la fonctionnalité est encore expérimentale et pas complètement stable

**Temps typique :** ~2-3 minutes pour 10 imports (mais à un coût en tokens prohibitif).

### Mon avis

Pour ce genre de tâche simple et indépendante, Agent Teams n'est clairement pas adapté. Mais cette expérimentation m'a permis de comprendre son fonctionnement et d'entrevoir son potentiel pour des cas d'usage plus complexes où la coordination entre agents a du sens — par exemple des tâches qui nécessitent de partager des découvertes intermédiaires ou de s'adapter dynamiquement au travail des autres. La fonctionnalité est encore jeune, mais le potentiel est réel.

## Approche 4 : Dynamic Worker Pool

### La première tentative et son échec

Ma première version de cette approche tentait de reproduire le pattern worker pool **sans** Agent Teams, en utilisant `TaskCreate` et l'outil `Task` avec `run_in_background: true`. L'idée : créer les tâches, lancer 4 sous-agents en background qui consultent autonomement la liste de tâches via `TaskList`, se les attribuent et bouclent.

**Le plan a échoué.** Les sous-agents lancés via `Task` n'ont pas accès aux outils de gestion de tâches (`TaskList`, `TaskGet`, `TaskUpdate`). Ces outils ne sont disponibles que dans la conversation principale. Les 4 workers ont immédiatement échoué avec un message du type : *"The instructions reference TaskList, TaskUpdate, and TaskGet functions that should be available in my toolkit, but they are not currently accessible."*

### Le pattern qui fonctionne : dispatch dynamique par l'agent principal

J'ai donc redéveloppé la commande avec un pattern différent. Au lieu de demander aux workers de se coordonner entre eux (ce qu'ils ne peuvent pas faire), c'est l'**agent principal qui orchestre tout**. Le principe : chaque worker n'exécute qu'**une seule commande** puis se termine. Quand l'agent principal reçoit la notification de fin d'un worker, il lance immédiatement un nouveau sous-agent avec la tâche suivante.

```markdown
1. Créer 10 tâches (TaskCreate)
2. Lancer 4 agents en background (1 tâche chacun)
3. Recevoir notification "worker-1 a fini"
4. Lancer un nouvel agent en background avec la prochaine tâche
5. Répéter jusqu'à plus de tâches pending
```

C'est du **push dynamique** plutôt que du pull par les workers, mais l'effet est le même : pas de temps mort, le worker le plus rapide libère son slot immédiatement et un nouveau part sur la tâche suivante.

La clé du succès : l'agent principal gère toute la coordination (`TaskList`, `TaskUpdate`) et chaque sous-agent reçoit directement sa commande dans le prompt, sans avoir besoin d'accéder au système de tâches.

Voici les instructions clés du prompt. D'abord, la contrainte documentée en tête de commande :

```markdown
**Key design constraint:** Subagents do NOT have access to Task
tools (TaskList, TaskGet, TaskUpdate). Only the main agent can
manage tasks. Each worker receives exactly **one command** in its
prompt, executes it, returns the result, and terminates.
The main agent then dispatches the next task.
```

Ensuite, la boucle de dispatch de l'agent principal :

```markdown
### Step 3: Dispatch loop — spawn workers dynamically

1. **Initial dispatch**: Take the first 4 pending tasks,
   mark each as `in_progress` via `TaskUpdate`,
   and spawn 4 background subagents in a **single message**.

2. **On worker completion**: When a background agent completes:
   - Extract the ERROR lines from the worker's returned output
   - Update the task via `TaskUpdate`: set status to "completed"
   - If remaining pending tasks: spawn a **new background agent**
   - If no more tasks: wait for remaining workers

3. **Repeat** until all tasks are completed.
```

Et le prompt de chaque worker, volontairement minimal :

```markdown
You are an import worker. Run the following command and report results.

## Command
{full Docker command}

## Instructions
1. Run the command using `Bash` with a timeout of 300000ms.
2. Extract ALL lines containing "ERROR" from the output.
3. Return: **Status**: SUCCESS | FAILURE | TIMEOUT
   **Error lines**: {all ERROR lines, or "None"}

## Important rules
- Do NOT edit any files. Only run the command and report results.
- Do NOT skip or reformat ERROR lines — copy them verbatim.
```

Chaque worker ne fait qu'une chose : exécuter et rapporter. Toute l'intelligence reste dans l'agent principal.

La vraie différence avec l'approche batch est maintenant claire :
- **Batch** : vagues de 4, on attend que toute la vague finisse → temps mort si un lot contient un import lent
- **Dynamic Worker Pool** : dès qu'un worker finit, un nouveau part → pas de temps mort, meilleur équilibrage

### Avantages

- Pas de dépendance à Agent Teams ni à un feature flag
- Vrai pattern worker pool avec auto-équilibrage dynamique
- L'agent principal n'est pas bloqué : il réagit aux notifications de complétion
- Coût tokens comparable au séquentiel : le nombre total de tokens est plus élevé (overhead de création de chaque worker), mais Haiku est significativement moins cher qu'Opus, ce qui compense
- Chaque worker exécute exactement 1 commande → prompt simple, pas de logique complexe

### Inconvénients

- Plus de workers créés au total (10 workers pour 10 tâches, contre 4 dans l'approche batch) avec l'overhead d'initialisation associé
- L'agent principal doit rester attentif aux notifications pour dispatcher rapidement

**Temps typique :** ~2 minutes pour 10 imports.

## Comparatif

| Critère              | Séquentiel | Batch        | Agent Teams               | Dynamic Worker Pool |
|----------------------|------------|--------------|---------------------------|---------------------|
| Concurrence          | 1          | 4 par lot    | 4 auto-assignés           | 4 dynamiques        |
| Temps total          | ~4 min     | ~3 min       | ~2-3 min                  | ~2 min              |
| Complexité du prompt | Faible     | Moyenne      | Élevée                    | Moyenne             |
| Modèles              | Opus       | Opus + Haiku | Opus + Haiku              | Opus + Haiku        |
| Coût tokens          | Moyen      | Comparable   | Très élevé (coordination) | Comparable          |
| Feature flag requis  | Non        | Non          | Oui                       | Non                 |
| Auto-équilibrage     | N/A        | Non          | Oui                       | Oui                 |

## Ce que j'ai appris

### Haiku pour les tâches triviales

Utiliser `model: "haiku"` pour les sous-agents qui ne font qu'exécuter une commande et extraire des lignes d'erreur est un excellent rapport qualité/prix. Le modèle principal (Opus ou Sonnet) n'intervient que pour les décisions complexes comme choisir le bon enum case pour un mapping ambigu.

### Le Dynamic Worker Pool est le meilleur compromis

La première tentative de worker pool avait échoué à cause des limitations d'accès aux outils Task dans les sous-agents. Mais en repensant le pattern — l'agent principal dispatche dynamiquement les tâches au lieu de laisser les workers se les attribuer — on obtient un vrai auto-équilibrage sans la complexité d'Agent Teams. C'est la preuve qu'une contrainte technique peut mener à une meilleure architecture quand on la contourne intelligemment plutôt que de la forcer.

### L'overhead du parallélisme n'est pas gratuit

Introduire du parallélisme avec Claude Code génère un overhead non négligeable : chaque sous-agent doit être initialisé, recevoir son prompt système, négocier son modèle, puis retourner ses résultats à l'agent principal. 10 agents n'iront pas 10 fois plus vite. Et plus les tâches sont courtes, moins il y aura de gain — l'overhead d'initialisation d'un agent peut facilement dépasser le temps d'exécution d'une tâche rapide. Dans notre cas, le temps total reste dominé par l'import le plus lent, quelle que soit l'approche. Le gain principal vient du passage de séquentiel à parallèle, pas de l'optimisation fine entre les approches parallèles.

### La simplicité gagne (souvent)

L'approche batch (v2) offre un bon compromis simplicité/performance et sera suffisante dans beaucoup de cas. Le Dynamic Worker Pool (v4) apporte l'auto-équilibrage sans la complexité d'Agent Teams, pour un prompt de complexité comparable au batch. Agent Teams (v3) reste intéressant à connaître mais surdimensionné pour des tâches indépendantes.

## Quelle approche choisir ?

Si je devais résumer en un arbre de décision :

- **Moins de 5 tâches courtes** → séquentiel, ne vous embêtez pas
- **5-20 tâches indépendantes, temps homogènes** → batch parallèle, le meilleur rapport simplicité/efficacité
- **Tâches aux durées très hétérogènes qui ont besoin de communiquer ensemble** → Agent Teams si vous acceptez la complexité et le feature flag
- **Besoin de ne pas bloquer l'agent principal** → Task Worker Pool avec `run_in_background`

## Pour aller plus loin

Les patterns explorés ici sont transposables à d'autres cas d'usage :

- **Tests en parallèle** : lancer N suites de tests sur des sous-agents
- **Migrations de données** : traiter des lots d'enregistrements en parallèle
- **Analyse de code** : faire auditer différents modules par des agents spécialisés
- **Développer plusieurs parties d'une application** : par exemple un frontend et un backend
- **Revue de code multi-fichiers** : faire analyser différentes PR ou fichiers modifiés par des sous-agents spécialisés (sécurité, performance, style)
- **Génération de fixtures** : générer des jeux de données de test pour différentes entités en parallèle
- **Refactoring à grande échelle** : appliquer la même transformation sur plusieurs modules indépendants (renommage, migration de patterns)
- **Traduction / i18n** : traduire plusieurs fichiers de traduction simultanément

La clé est toujours la même : identifier les tâches indépendantes, définir la contrainte de concurrence (mémoire, API rate limits...), et choisir le pattern de parallélisation adapté à la complexité du problème.
