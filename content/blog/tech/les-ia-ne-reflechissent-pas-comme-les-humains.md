---
title: 'Les IA génératives ne réfléchissent pas comme les humains'
date: '2024-03-28' # Au format YYYY-MM-DD
lastModified: ~ # Au format YYYY-MM-DD. Pour indiquer explicitement qu'un article a été mis à jour
description: "Découvrez à travers cet article le fonctionnement des IA et plus spécifiquement des modèles de langage."
authors: [equentin] # (multiple acceptés)
tableOfContent: false # `true` pour activer ou `3` pour lister les titres sur 3 niveaux.
tags: [ia, chatgpt, llm, prompt]
thumbnail: content/images/blog/2024/ia/image-cover-ia.jpg
tweetId: '' # Ajouter l'id du Tweet après publication.
outdated: false # `true` pour marquer un article comme obsolète ou une chaîne de caractère pour un message spécifique à afficher
---

À la sortie de [**ChatGPT**](https://chat.openai.com/), nous avons été nombreux·euses à tester cet outil qui est apparu comme une nouvelle attraction révolutionnaire. Nous avons commencé à lui poser des questions, plus ou moins précises, à échanger avec lui, à le tester et à tenter de découvrir ses limites. 

Au sein de l’agence, nous avons été plusieurs à nous intéresser de plus près à ChatGPT mais surtout au fonctionnement de ces IA génératives afin de mieux les appréhender. Comprendre le mécanisme de ces outils nous a ainsi permis d’aller plus loin dans leur utilisation et de mesurer l’intérêt qu’ils peuvent avoir dans notre quotidien professionnel.💡

À travers notre veille personnelle, notre participation à des conférences, à nos études sur des lectures spécialisées, nous avons réalisé une chose essentielle :  **les IA ne possèdent pas une capacité de réflexion similaire à celle des êtres humains . Ce sont avant tout des outils entraînés, capables d’assimiler des données et de compiler des informations.** En partant de ce postulat, le fonctionnement de ces assistants et la manière de créer des prompts nous est apparu plus clair, notamment pour la création de notre produit [**Amabla**](https://www.amabla.com).  

## De manière très simple, comment fonctionne une IA générative ?

L’univers de l’intelligence artificielle est présent depuis déjà de nombreuses années (dès les années 50). Avec le développement de l’informatique et son déploiement auprès du grand public, les IA ont commencé à connaître un succès important dans les années 2000, notamment avec l’une des avancées majeures de l’IA : le **machine learning** (ou l’art pour les machines d’apprendre par elles-mêmes en analysant des données et en améliorant leurs performances au fil du temps). 

C’est dans les années 2020 que nous allons voir apparaître la notion de **modèle de fondation** (capable de comprendre et de générer du contenu) connaissant une popularisation grandissante avec l’arrivée de l’outil ChatGPT.

## Comment résumer simplement le fonctionnement d’une IA générative basée sur les modèles de langage (ou LLM) ? 

Le principe des **LLM** (Large Language Models) repose sur l’utilisation de **réseaux de neurones** à grande échelle pour analyser, comprendre et générer du texte. Ces systèmes sont entraînés sur des grands corpus de texte leur permettant de comprendre des modèles linguistiques, des structures grammaticales, des associations de mots et des nuances de langage. 

Les LLM sont également dotés d’une certaine spécificité : la **prédiction**. En effet, les LLM fonctionnent en prédisant le mot suivant dans une séquence de mots, en se basant sur les mots précédents. Cela leur permet de concevoir des contenus cohérents et contextuellement pertinents. 

Pour comprendre un peu plus précisément le fonctionnement des IA génératives sans entrer dans des détails trop techniques, il est important de se focaliser sur plusieurs points d’attention👇

## Comprendre leur fonctionnement

### Les IA génératives ne nous répondent pas en langage naturel 

Lorsque nous formulons notre prompt, l’IA ne va pas prendre en considération les mots en tant que tel, mais va, via le processus de tokenisation, convertir le texte en une série de vecteurs numériques. Le texte va alors être transformé en **token**. Le principe des tokens est primordial pour les modèles d’IA basés sur le langage car ils permettent de convertir le texte en **données structurées que les algorithmes peuvent traiter**. 

Plus concrètement, les LLM ne vont pas travailler directement sur les mots en eux-mêmes, mais vont les décomposer. Généralement, un token correspond à ¾ d’un mot. 

<figure>
    <img src="content/images/blog/2024/ia/nb-tokens-openai.png" alt="Calculer le nombre de token sur une phrase" width="650">
    <figcaption>
      <span class="figure__legend">Calculer le nombre de token sur une phrase</span>
    </figcaption>
</figure>

!!! note "Pour info"
    Si vous souhaitez comprendre le fonctionnement de tokénisation et tester vos propres contenus, OpenAI propose un outil qui comptabilise votre phrase en tokens (en fonction du modèle - ChatGPT 3 OU 4) https://platform.openai.com/tokenizer

Chaque LLM possède son propre fonctionnement de tokenisation et est capable de générer plus ou moins de tokens. Par exemple, actuellement **ChatGPT 4** (OpenAI) peut générer en une seule fois (ce qu’on appelle la fenêtre de contexte) 8 000 tokens, soit 6 000 mots. À l’inverse, **Gemini** (Google), peut générer 32 000 tokens, soit 24 000 mots. 

### Les IA génératives adaptent les mots à leur propre langage 

Pour rappel, les modèles d’IA génératives, se reposant sur des principes mathématiques, ne sont pas capables de comprendre un mot en langage naturel. Elles vont donc devoir trouver un moyen de le transformer dans leur langage à elles. Pour cela, elles vont transformer un mot en un vecteur. 

Avec ce principe là, elles vont pouvoir traiter le terme et donner une suite logique à ce dernier. Bien qu’un autre élément va intervenir afin de produire un contenu encore plus précis. 

### Les IA génératives analysent le sens des mots

Afin de pouvoir anticiper le prochain mot et créer un contenu cohérent, les IA vont devoir positionner un terme dans son ensemble dans le but de lui donner du sens, via un système de coordonnées. C’est ce que l’on appelle l’**embedding**. 

Prenons l’exemple développé par **Science Étonnante**. Nous souhaitons positionner le mot “chat”. Nous prenons deux dimensions en compte : sa domestication et sa taille. 
Ainsi, nous positionnons le chat (bien domestiqué et plutôt petit) sur un graphique. Le chien va apparaître à côté (car encore plus domestiqué et un peu plus grand). Si nous souhaitons positionner le lynx, ce dernier va apparaître au-dessus du chat (car plus sauvage et plus grand), de même pour le loup. Ainsi, on va attribuer à chacun de ces termes deux nombres, comprenant donc deux dimensions. Cela va nous permettre de positionner un terme dans son ensemble.⛓️ 

<figure>
    <img src="content/images/blog/2024/ia/embedding.png" alt="exemple du système d'embedding" width="650">
    <figcaption>
      <span class="figure__legend">Exemple du système d'embedding</span>
    </figcaption>
</figure>

Dans cet exemple, nous prenons en compte 2 nombres pour 2 dimensions, mais il faut bien comprendre que pour des textes plus conséquents, cela ne sera pas suffisant. Il faudra alors plus de dimension, généralement 300, afin de placer le mot dans “l’espace des significations”.

Avec ce principe, les IA génératives sont capables de prévoir la suite des mots de manière plus probable et donc de concevoir une réponse plus poussée, plus cohérente. 

## L'essentiel à retenir 

Comme l’indique Flavien Chervet dans son ouvrage *Hyperprompt - Maîtriser l’art du prompt engineering*, il faut “*comprendre que les LLM ne répondent pas aux demandes de l’utilisateur en “réfléchissant*” à la réponse pertinente. Ils prennent la demande de l’utilisateur comme une nouvelle donnée, et tentent de **prédire** les prochains mots les plus **probables** par rapport à cette demande.”

Autrement dit, le modèle ne cherche pas à dire quelque chose de vrai, il n’a pas cette dimension de vérité. Il cherche à proposer quelque chose de **plausible** par rapport à ce qu’il a pu “apprendre” durant ses phases d’entraînement. 

Il est important de saisir ce fonctionnement afin de pouvoir réaliser des prompts percutants qui vous permettront de maximiser l’apport des IA génératives dans votre contexte métier. Maîtriser l’art du **prompting** vous permettra d’être plus efficace dans vos tâches quotidiennes. 

Chez Elao, nous avons mesuré l’impact que les IA génératives pouvaient avoir dans nos métiers et nous avons souhaité travailler sur une solution adaptée au contexte de chacun : regrouper dans un seul et même outil tous les assistants dont vous avez besoin. Vous pouvez ainsi **créer vos assistants sur-mesure ou utiliser des modèles pré-établis**, afin de vous accompagner dans **vos tâches hebdomadaires** et vous dégager du temps sur les sujets pour lesquels vous avez une véritable valeur ajoutée. Mais pour cela, il nous semblait essentiel de vous partager le fonctionnement des IA, afin que vous puissiez profiter pleinement de cette solution. 

Si vous souhaitez en savoir plus sur Amabla, rendez-vous sur **https://www.amabla.com** ou [contactez nous](https://www.elao.com/contact). 

# Sources

- Hyperprompt - Maîtriser l'art du prompt engineering de Flavien CHERVET
- [Ce qui se cache derrière ChatGPT](https://www.youtube.com/watch?v=CsQNF9s78Nc ) - Science Étonnante 
- [Comment les IA font-elles pour comprendre notre langue ?](https://www.youtube.com/watch?v=7ell8KEbhJo ) - Science Étonnante

