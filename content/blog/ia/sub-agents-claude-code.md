---
title: "Les sub-agents Claude Code : isoler, déléguer, paralléliser"
date: "2026-04-24"
lastModified: ~
description: "Comprendre les sub-agents de Claude Code, leurs cas d'usage et comment ils permettent d'optimiser vos workflows : isolation de contexte, choix du modèle et parallélisation."
authors: [mcolin]
tableOfContent: 2
tags: ["claude code", "ia", "sub-agents", "productivité"]
thumbnail: content/images/blog/thumbnails/sub-agents-claude-code.jpg
banner: content/images/blog/thumbnails/sub-agents-claude-code.jpg
---

## Introduction : qu'est-ce qu'un sub-agent ?

Quand on utilise [Claude Code](https://docs.anthropic.com/en/docs/claude-code) au quotidien, on finit assez vite par se heurter aux limites d'une conversation unique. Le contexte se remplit, les sorties de commandes s'accumulent, et l'agent principal commence à perdre le fil — ou pire, le système compacte automatiquement les messages anciens et des informations importantes disparaissent.

C'est là qu'interviennent les **sub-agents**. Un sub-agent, c'est un agent enfant lancé par l'agent principal via l'outil `Agent`. Il s'exécute dans sa propre conversation, avec sa propre fenêtre de contexte, exécute une tâche de façon autonome, puis retourne un résultat à l'agent principal. Une fois terminé, seul ce résultat remonte — pas l'intégralité de ce qui s'est passé dans la conversation du sub-agent.

Dit autrement : c'est un peu comme déléguer une tâche à un collègue. Vous lui donnez un brief, il bosse de son côté, et il vous fait un compte-rendu. Vous n'avez pas besoin de suivre chaque étape de son travail, et lui n'a pas besoin de connaître tout le contexte de votre journée pour faire ce que vous lui demandez.

Dans la pratique, les sub-agents permettent de résoudre plusieurs problèmes qu'on rencontre fréquemment avec Claude Code : la **gestion du contexte**, le **choix du modèle**, la **parallélisation** et la **spécialisation**. C'est ce que nous allons voir dans cet article.

Dans une slash command ou un skill, on décrit simplement la délégation en langage naturel :

```markdown
### Step 1: Analyze the codebase
Launch a sub-agent (via the Agent tool) to search the codebase
for all files matching `src/**/*.ts` that import the deprecated
`legacy-auth` module. The sub-agent should return the list of
file paths and the line numbers of each import.

### Step 2: Fix each file
For each file returned by the sub-agent, replace the import [...]
```

## Isoler le contexte

La fenêtre de contexte d'un agent Claude Code n'est pas infinie. Plus la conversation avance, plus elle se remplit : sorties de commandes, contenus de fichiers lus, résultats de recherche, échanges avec l'utilisateur. Quand la limite approche, Claude Code **compacte** automatiquement les messages les plus anciens — un résumé remplace les échanges originaux. C'est transparent, mais ce n'est pas sans conséquence : des détails peuvent se perdre, et l'agent peut oublier des décisions prises plus tôt dans la conversation.

Les sub-agents permettent de gérer ce problème de deux façons complémentaires.

### Protéger le contexte principal

Certaines tâches produisent des sorties volumineuses. Exécuter une commande qui crache 500 lignes de logs, lire un fichier de 2000 lignes pour en extraire une information, lancer une recherche dans le codebase qui retourne des dizaines de résultats — tout ça vient gonfler le contexte de l'agent principal, même si 95% de cette sortie ne servira plus par la suite.

En déléguant ce type de tâche à un sub-agent, toute cette volumétrie reste **confinée dans le contexte du sub-agent**. L'agent principal ne reçoit que le résultat utile — quelques lignes, un résumé, une réponse à la question posée. Le contexte principal reste propre et concentré sur ce qui compte.

Concrètement, au lieu de demander à l'agent principal :

*"Lance les 10 commandes et analyse les erreurs"*

On lui demande de créer un sub-agent pour chaque commande :

*"Lance un sub-agent qui exécute cette commande et remonte uniquement les lignes d'erreur"*

L'agent principal récupère 3 lignes d'erreur au lieu de 500 lignes de logs. Sa fenêtre de contexte reste intacte pour la suite du travail.

Dans un prompt de slash command, cela se traduit par :

```markdown
For each command in the list, launch a sub-agent (via the Agent tool)
with the following instructions:

"Run this command using Bash: `{command}`
Read the full output and extract all lines containing ERROR.
Return ONLY the error lines, nothing else."

Collect the results from all sub-agents before proceeding.
```

### Travailler sans contexte superflu

L'isolation fonctionne aussi dans l'autre sens. Un sub-agent démarre avec une **page blanche** : il ne connaît que le prompt qu'on lui a donné. Il n'a pas accès à l'historique de la conversation principale, aux fichiers déjà lus, aux décisions prises avant lui.

Pour beaucoup de tâches, c'est un avantage. Un sub-agent chargé de linter un fichier n'a pas besoin de savoir que vous êtes en train de refactorer l'architecture de votre application. Un sub-agent qui exécute un test unitaire n'a pas besoin du contexte d'une discussion de 30 échanges sur le design d'une API.

Moins de contexte, c'est aussi **moins de bruit** pour le modèle. Le sub-agent se concentre sur sa tâche sans être distrait par des informations qui ne le concernent pas. Et accessoirement, moins de tokens en entrée signifie une exécution plus rapide — même si, comme on le verra plus loin, le coût global d'un sub-agent dépend aussi de son overhead d'initialisation.

## Utiliser un modèle différent

Dans une conversation Claude Code, on peut changer de modèle à la volée avec la commande `/model`. Mais ce choix reste **global** : à un instant T, toute la conversation tourne sur un seul modèle. Impossible, par exemple, de laisser Opus piloter le raisonnement pendant que des tâches plus simples tournent sur Haiku **en même temps**.

Les sub-agents permettent de contourner cette limitation. Chaque sub-agent peut être lancé avec un **modèle différent** de celui de l'agent principal, via le paramètre `model`. On peut donc orchestrer une conversation où Opus prend les décisions complexes pendant que des sub-agents Haiku ou Sonnet se chargent des tâches plus simples — simultanément.

### Adapter le modèle à la complexité de la tâche

Tous les travaux ne nécessitent pas la même puissance de raisonnement. Exécuter une commande et extraire des lignes d'erreur, chercher un fichier dans le codebase, reformater un bloc de code — ce sont des tâches mécaniques qu'un modèle léger comme Haiku gère parfaitement. À l'inverse, choisir la bonne abstraction pour un refactoring, analyser une faille de sécurité ou comprendre une logique métier complexe, c'est là qu'un modèle comme Opus fait la différence.

En combinant les deux, on obtient le meilleur des deux mondes :

- **L'agent principal** (Opus ou Sonnet) se concentre sur le raisonnement, la prise de décision et la coordination
- **Les sub-agents** (Haiku) se chargent de l'exécution mécanique et de la collecte d'informations

Dans un prompt, on précise le modèle souhaité pour le sub-agent :

```markdown
Launch one sub-agent per test suite (via the Agent tool)
with **model: "haiku"**. Each sub-agent runs its assigned
test command and returns the result (pass/fail + error details).

The main agent (Opus) then analyzes the failures and proposes fixes.
```

### Impact sur le coût et la rapidité

L'intérêt n'est pas qu'intellectuel — il est aussi économique. Haiku est **significativement moins cher par token** qu'Opus. Si votre workflow lance 10 sub-agents pour des tâches triviales, les faire tourner sur Haiku plutôt qu'Opus peut diviser le coût de ces sous-tâches par un facteur important.

Côté rapidité, les modèles plus légers ont aussi une **latence plus faible**. Un sub-agent Haiku qui exécute une commande et retourne 3 lignes de résultat sera plus réactif qu'un sub-agent Opus pour la même tâche — ce qui compte particulièrement quand on en lance plusieurs en parallèle.

## Paralléliser des tâches

Claude Code sait déjà paralléliser les appels d'outils simples : un `Read` et un `Grep` peuvent très bien être lancés dans le même message. En revanche, tout ce qui demande un véritable **raisonnement** — lire plusieurs fichiers pour en tirer une synthèse, analyser du code avant d'écrire un patch, enchaîner plusieurs décisions — passe par la boucle de l'agent principal, qui, elle, est séquentielle. Si vous avez 10 tâches indépendantes qui nécessitent chacune leur propre analyse, l'agent principal les traitera une par une. Quand chacune prend 30 secondes à une minute, l'addition devient douloureuse.

Les sub-agents permettent de **lancer plusieurs boucles d'agent en parallèle**. Le principe est simple : l'agent principal crée plusieurs sub-agents dans un même message, et Claude Code exécute leurs boucles simultanément. Chaque sub-agent raisonne de façon indépendante dans son propre contexte, et l'agent principal récupère les résultats au fur et à mesure.

Les cas d'usage sont nombreux :

- **Tests** : lancer plusieurs suites de tests en parallèle
- **Analyse de code** : faire auditer différents modules par des agents spécialisés
- **Refactoring** : appliquer la même transformation sur plusieurs fichiers indépendants
- **Développement** : travailler sur le frontend et le backend en même temps grâce aux worktrees
- **Revue de code** : analyser différents fichiers modifiés par des sub-agents spécialisés (sécurité, performance, style)

La clé technique : pour s'assurer que plusieurs sub-agents tournent **vraiment en parallèle**, il faut le demander explicitement à Claude Code. Sans formulation claire, il peut les enchaîner au lieu de les lancer simultanément :

```markdown
Launch one Agent per task, **in parallel using separate subagents**,
so they run concurrently.
```

On peut aussi ajouter `run_in_background: true` — mais attention, ce paramètre ne sert pas à paralléliser. Il permet à l'agent orchestrateur de **continuer son travail sans attendre** les résultats, et de réagir aux notifications de complétion au fur et à mesure. Utile quand les sub-agents sont longs et qu'on a d'autres actions à enchaîner en parallèle côté orchestrateur.

Il existe plusieurs approches pour orchestrer cette parallélisation — du simple batch à des patterns plus dynamiques avec auto-équilibrage. J'ai exploré ces différentes approches en détail dans un article dédié : [Paralléliser des tâches avec Claude Code : comparatif de 4 approches](./paralleliser-taches-claude-code.md).

## Autres bénéfices

Au-delà de l'isolation du contexte, du choix du modèle et de la parallélisation, les sub-agents apportent quelques avantages supplémentaires qui méritent d'être mentionnés.

### Tolérance aux erreurs

Un sub-agent qui échoue ne fait pas planter l'agent principal. Si un sub-agent rencontre une erreur — une commande qui timeout, un fichier introuvable, une tâche impossible à compléter — il retourne simplement un résultat d'échec. L'agent principal peut alors décider quoi faire : réessayer, ignorer, adapter sa stratégie. C'est beaucoup plus robuste que de tout exécuter dans la conversation principale où une erreur inattendue peut dérailler l'ensemble du workflow.

### Spécialisation

Quand on donne un prompt très ciblé à un sub-agent, on obtient souvent de meilleurs résultats que si on demande tout à un seul agent généraliste. C'est le même principe qu'en gestion d'équipe : un développeur à qui on confie une tâche précise avec un brief clair sera plus efficace qu'un développeur qui doit jongler entre 15 sujets en même temps.

On peut par exemple lancer des sub-agents avec des rôles différents :

- Un sub-agent "expert sécurité" qui audite un fichier en cherchant spécifiquement les failles OWASP
- Un sub-agent "reviewer" qui vérifie la cohérence d'une PR avec les conventions du projet
- Un sub-agent "testeur" qui écrit les tests unitaires pour une fonction donnée

Chaque sub-agent reçoit un prompt taillé pour son rôle, avec les bonnes instructions et le bon niveau de détail. L'agent principal orchestre et synthétise.

### Worktrees

Les sub-agents peuvent être lancés avec le paramètre `isolation: "worktree"`, qui crée un **worktree git temporaire** — une copie isolée du repository. Le sub-agent travaille sur cette copie sans affecter l'état du répertoire de travail principal.

C'est particulièrement utile quand un sub-agent doit modifier des fichiers en parallèle de l'agent principal, ou quand on veut explorer une piste sans risquer de casser l'état courant. Si le sub-agent ne fait aucune modification, le worktree est nettoyé automatiquement. S'il a produit des changements utiles, on peut les récupérer depuis la branche créée.

## Limites et pièges

Les sub-agents ne sont pas une solution miracle. Il y a quelques limitations et pièges à connaître avant de les intégrer dans vos workflows.

### Pas de liste de tâches partagée

Les listes de tâches ne sont **pas partagées** entre l'agent principal et ses sub-agents. Chaque agent tourne dans son propre contexte isolé, avec sa propre liste : un sub-agent ne peut ni consulter ni mettre à jour la liste de l'agent principal, et inversement.

C'est une contrainte qui impacte directement la façon dont on conçoit ses workflows. On ne peut pas créer un sub-agent autonome qui va piocher tout seul dans une file de tâches commune. C'est l'agent principal qui doit distribuer le travail et centraliser la coordination.

### L'overhead n'est pas gratuit

Chaque sub-agent a un coût d'initialisation : chargement du prompt système, négociation du modèle, mise en place du contexte. Pour une tâche qui prend 2 secondes à exécuter, l'overhead de création du sub-agent peut facilement représenter plusieurs fois le temps d'exécution réel. Autrement dit, **si la tâche est très courte, le sub-agent coûte plus cher que ce qu'il apporte**.

De la même façon, 10 sub-agents en parallèle n'iront pas 10 fois plus vite qu'un traitement séquentiel. L'overhead d'initialisation, la collecte des résultats et la coordination par l'agent principal grignotent une partie du gain.

### Pas d'interaction utilisateur

Un sub-agent ne peut pas poser de questions à l'utilisateur. S'il rencontre une ambiguïté ou a besoin d'une décision humaine, il ne peut que remonter le problème dans son résultat de retour. L'agent principal devra alors prendre le relais pour solliciter l'utilisateur si nécessaire. Il faut donc s'assurer que le brief donné au sub-agent est **suffisamment complet** pour qu'il puisse travailler de façon autonome.

### Quand ne pas les utiliser

Tous les problèmes ne méritent pas un sub-agent. Si la tâche est simple, rapide, et que sa sortie est légère, l'exécuter directement dans l'agent principal sera plus efficace. Créer un sub-agent pour lancer un `git status` ou lire un fichier de 20 lignes, c'est overkill.

En règle générale, un sub-agent se justifie quand la tâche est **suffisamment lourde** (en temps ou en volume de sortie) pour que l'overhead d'initialisation soit négligeable par rapport au bénéfice apporté.

## Et après : Agent Teams

Les sub-agents classiques ont une limite fondamentale : ils sont **isolés les uns des autres**. Chaque sub-agent travaille dans son coin, sans possibilité de communiquer avec les autres sub-agents ni de se coordonner. Toute la coordination passe par l'agent principal, qui doit distribuer le travail et centraliser les résultats.

Claude Code propose une fonctionnalité expérimentale appelée **Agent Teams** (activable via le feature flag `CLAUDE_CODE_EXPERIMENTAL_AGENT_TEAMS=1`) qui lève cette limitation. Agent Teams permet de créer une véritable **équipe d'agents** orchestrée par un agent leader. Les agents "teammates" partagent une **liste de tâches commune**, peuvent s'auto-attribuer les tâches disponibles et communiquer entre eux via un **système de messagerie intégré**.

Concrètement, cela ouvre la porte à des workflows plus sophistiqués :

- Des agents qui se **répartissent dynamiquement** le travail sans que l'agent principal ait besoin de tout dispatcher
- Des agents qui **partagent des découvertes intermédiaires** avec le reste de l'équipe
- Des agents qui **s'adaptent** au travail des autres en temps réel

La fonctionnalité est encore jeune et expérimentale — je l'ai testée brièvement dans le cadre de [mon article sur la parallélisation](./paralleliser-taches-claude-code.md) et le potentiel est réel, même si la stabilité et le coût en tokens restent à améliorer. Je reviendrai dessus en détail dans un futur article dédié.

## Conclusion

Les sub-agents sont un outil simple dans leur principe — déléguer une tâche à un agent enfant — mais qui résout élégamment plusieurs problèmes du quotidien avec Claude Code : un contexte qui déborde, un modèle qu'on ne peut pas changer, des tâches qu'on aimerait paralléliser.

Si je devais résumer en un arbre de décision :

- **La sortie de la tâche est volumineuse** et risque de polluer votre contexte → sub-agent
- **La tâche est triviale** et ne nécessite pas un modèle puissant → sub-agent avec Haiku
- **Vous avez plusieurs tâches indépendantes** à traiter → sub-agents en parallèle
- **La tâche nécessite un rôle spécialisé** (audit sécurité, review, tests) → sub-agent avec un prompt ciblé
- **La tâche est courte, légère et simple** → gardez l'agent principal, ne vous embêtez pas

Comme souvent avec les outils puissants, la clé est de savoir quand les utiliser — et quand s'en passer.
