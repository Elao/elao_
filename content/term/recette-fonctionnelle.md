---
name: "Recette fonctionnelle"
title: 
    - "Recette" 
    - "fonctionnelle"
title-seo: "Recette fonctionnelle : définition"
metaDescription : "La recette fonctionnelle est une étape indispensable dans le développement d'applications web et mobiles. Découvrez comment ça marche."
---

Le développement d'une fonctionnalité comprend plusieurs étapes :
- Définition du besoin
- Identification d'une solution
- Spécifications fonctionnelles
- Maquettes graphiques
- Développement
- Recette
- Mise en production

La recette est la dernière étape de développement de fonctionnalité : c'est celle où l'on vérifie que tout fonctionne correctement avant de livrer à l'utilisateur final. Elle se déroule généralement dans un environnement de "staging" ou "pré-production", au plus proche du serveur de production mais avec ses propres bases de données, ce qui nous permet de maltraiter un peu l'application lors des tests 😈.
L'étape de recette permet de s'assurer que le développement réalisé correspond au périmètre défini dans les [spécifications fonctionnelles](./specifications-fonctionnelles.md). 

## Qui doit réaliser la recette fonctionnelle ? 

Idéalement, le plus de personnes possible afin de multiplier les chances de débusquer d'éventuels dysfonctionnements. En général :
- Le développeur : la personne qui a développé la fonctionnalité, d'abord, est la première personne qui la teste. 
- Généralement, un regard externe est plus que favorable. Chez Elao, une tierce personne de l'équipe réalise la recette avant de proposer la fonctionnalité au client. 
- Le PO (Product Owner) ou porteur de projet fait la dernière passe et si tout est conforme, donne le _go_ pour sa mise en production. 

## Comment être sûr·e de ne rien oublier pendant la recette ? 

L'idéal pour ne rien oublier est d'avoir prévu les différents cas lors de la rédaction des spécifications fonctionnelles, dans un scénario "How to test" ou "Critères d'acceptation" de la fonctionnalité, permettant de valider sa mise en production.

### La checklist de base

- En web, tester la fonctionnalité sur différents navigateurs ainsi que sur mobile pour valider que tout s'affiche correctement dans tous les contextes ;
- Formulaire : tester des éléments valides et non valides, un remplissage partiel, pour valider que la gestion des erreurs a bien été réalisée ;
- Gestion des droits : si une fonctionnalité n'est accessible qu'à un groupe d'utilisateurs (par exemple, les administrateurs d'une solution), s'assurer que les autres groupes (utilisateurs simples) n'y ont pas accès ;
— Tester une liste ou une page vide, sans les items prévus. 

### Préparez votre propre checklist de recette

Vous avez sûrement des cas d'usage répétitifs, des groupes d'utilisateurs spécifiques à votre application. 
Systématiser une checklist personnalisée dans le modèle de vos spécification vous permettra de ne pas passer à côté ! 

### Amusez-vous à faire du "monkey testing"

Le "monkey testing" c'est s'amuser à rentrer n'importe quoi dans les différents champs d'une application pour tenter de trouver les failles du système. Il n'y a pas de règle, juste votre créativité ! 

![](images/terms/monkeytesting.gif)

## Les limites de la recette 

Étant donné que la recette se passe sur des serveurs qui ne sont pas ceux de production, il est possible que certaines anomalies n'émergent que quand la fonctionnalité est mise en ligne. Cela arrive notamment pour tout ce qui concerne de gros volumes de visites (et donc le fait que les serveurs soient suffisamment dimensionnés pour tenir la charge) qui n'arrivent en réalité que sur le serveur de production.

Il est alors de la responsabilité de l'équipe technique d'anticiper les problèmes de performances qui pourraient se présenter et de réaliser d'éventuels tests de charge, par exemple, pour s'assurer la bonne disponibilité de l'application à sa mise en production. 

Il est dans tous les cas nécessaire de suivre de près le bon fonctionnement d'une application après sa mise en production, à travers une recette post-production et un monitoring des performances dans les minutes et jours qui suivent la dernière mise en production.