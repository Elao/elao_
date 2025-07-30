---
title: 'Découvrez "Warp" : Le Terminal réinventé pour Mac'
date: '2023-07-26' # Au format YYYY-MM-DD
lastModified: ~ # Au format YYYY-MM-DD. Pour indiquer explicitement qu'un article à été mis à jour
description: 'À travers cet article, nous explorerons les avantages et les fonctionnalités les plus remarquables de "Warp", le terminal révolutionnaire pour Mac.'
authors: [lvilleneuve] # (multiple acceptés)
tableOfContent: false # `true` pour activer ou `3` pour lister les titres sur 3 niveaux.
tags: [warp, terminal, mac]
thumbnail: content/images/blog/thumbnails/warp.png
tweetId: '' # Ajouter l'id du Tweet après publication.
outdated: false # `true` pour marquer un article comme obsolète ou une chaîne de caractère pour un message spécifique à afficher
---

Il y a quelques semaines, je suis tombée sur Warp, un terminal de développement, qui m'a conquise.
Ce terminal offre des fonctionnalités avancées qui rendent la ligne de commande plus intuitive et puissante. 
Dans cet article, nous allons mettre en lumière les avantages les plus pertinents de Warp pour les développeurs, avec un accent particulier sur l'utilisation de l'IA.

## L'installation
Pour installer Warp sur votre Mac vous avez deux possibilités :
- lancer cette ligne de commande ```brew install --cask warp```
- cliquer sur le lien de téléchargement sur le [site internet](https://www.warp.dev/) et faire glisser Warp dans votre dossier d'application.

## Interface épurée et intuitive pour une utilisation immédiate
Dès le premier lancement, Warp m'a impressionné par son interface épurée et intuitive. La mise en page est soigneusement pensée pour une expérience utilisateur fluide.
Les commandes que j'utilise couramment sont mises en évidence, pour une meilleure accessibilité.

On a bien entendu la possibilité de personnaliser Warp à notre convenance, en piochant dans les [thèmes par défaut](https://docs.warp.dev/appearance/themes) ou en créant un thème custom qui correspond mieux à nos préférences.

Une fonctionnalité que j'apprécie beaucoup : lorsque l'on se trompe, pouvoir corriger et revenir quelques caractères précédents avec le curseur de la souris, sans avoir à utiliser les flèches du clavier.
On peut également selectionner du texte pour le supprimer.

### Warp Tab Completions
Warp Tab Completions vous proposera des commandes, des noms d'options et des paramètres de chemin. 
Si vous n'êtes pas sûr de la syntaxe ou de l'orthographe exacte, vous recevrez des suggestions basées sur votre entrée.
![Mode dark 1](content/images/blog/2023/warp-terminal/completion.png)
Pour activer la complétion il suffit de commencer à taper dans l'éditeur et de faire un ```TAB```  ➡️
Vous pourrez séléctionner la bonne proposition avec le curseur de la souris ou avec les flèches ⬆️⬇️

### Navigation Multi-Onglets
La navigation par onglets, nous permet d'ouvrir plusieurs sessions de terminal dans un même espace de travail.
Cela s'avère particulièrement précieux pour les développeurs qui jonglent avec plusieurs projets à la fois.

### Raccourcis Clavier Personnalisables
Warp sait que chaque développeur a ses propres préférences en matière de raccourcis clavier. C'est pourquoi il offre la possibilité de personnaliser ces raccourcis pour s'adapter à vos besoins spécifiques.

## L'IA dans le terminal Warp
Outre ses fonctionnalités puissantes, Warp se distingue également par l'intégration intelligente de l'Intelligence Artificielle (IA). 
Grâce à cette technologie avancée, Warp analyse les habitudes de chaque développeur pour personnaliser l'expérience de manière proactive. 
Cette approche centrée sur l'utilisateur permet à Warp de s'adapter à chaque développeur.

### L'assistant Warp IA
Warp AI est un assistant alimenté par l'IA intégré au terminal Warp.
On peut accéder à cet assistant :
- en tapant ce raccourci : ```SHIFT-CTRL-SPACE``` ou en séléctionnant du texte
- en cliquant sur l'icône ⚡️dans l'interface
![Mode dark 1](content/images/blog/2023/warp-terminal/warp-ia.png)

Vous pouvez par example demander à l'assistant : 
- d'expliquer la sortie du terminal
- de suggérer comment corriger une erreur
- de vous aider à travers une configuration

Lorsque vous obtenez une réponse de Warp AI, vous pouvez exécuter cette commande générée par l'IA sans copier/coller.
Vous pouvez également ajouter la réponse dans le Warp Drive ! (je vous explique en quoi ça consiste juste après 👇)

## Le Warp drive
Warp Drive est un endroit sécurisé pour enregistrer vos commandes en tant que workflow afin que vous puissiez les annoter, les partager et les exécuter à la demande.
Considérez les workflows comme des alias paramétrés que vous pouvez enregistrer pour vous-même et partager avec votre équipe, si chaque alias est accompagné d'un nom et d'une description claire.
![Mode dark 1](content/images/blog/2023/warp-terminal/warp-drive.png)


