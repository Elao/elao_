---
title: "Pourquoi choisir Env-branching (version Elao) comme modèle de workflow git ?"
date: '2024-08-06'
lastModified: ~
description: | 
  Découvrez pourquoi adopter le modèle de workflow env-branching peut améliorer votre gestion des branches Git, 
  en offrant une flexibilité accrue et des déploiements plus rapides, tout en préservant l'intégrité de votre code.
authors: [rledru]
tableOfContent: 6
tags: [Git, Github, Web]
banner: content/images/blog/2024/env-branching-git-flow-model/git-illustration.png
thumbnail: content/images/blog/2024/env-branching-git-flow-model/git-illustration.png
outdated: false
---

Aujourd’hui, quand on demande quel workflow git utilise un développeur, on tombe très souvent sur l’un des plus connus : la méthode gitflow.  
ll existe néanmoins une autre gestion de ses branches git qui assure tout aussi bien une communication fluide entre les développeurs, entre les clients, et une livraison de code de haute qualité : la méthode env-branching.

Cet article explore ces deux modèles pas à pas pour vous en proposer une comparaison fluide qui vous permettra peut-être de choisir quelle est la meilleure option pour vos besoins.

!!! Note ""
    On adopte chez Elao une version personnalisée de la méthode env-branching, qui s’adapte à nos besoins et à notre manière de travailler. 
    C’est ce modèle que nous allons vous présenter ici.

## Au commencement, Dieu créa les cieux et la terre

Pour commencer, les deux méthodes se distinguent par leur structure : 

![Schéma 1](content/images/blog/2024/env-branching-git-flow-model/schema_1.svg)

- **gitflow** est centralisée autour d’une branche `Develop` qui sert de fil conducteur et de racine aux releases menant aux mises en production de l’application.

- **env-Branching** lui est découplé par environnement. `Staging` est une branche qui correspond à un environnement de pré-prod. `Main` est la branche pivot du workflow et correspond à l’environnement de production.

## Le cycle continue : développement de features

![Schéma 2](content/images/blog/2024/env-branching-git-flow-model/schema_2.svg)

Deux branches ont été créées : `Feature 1` et `Feature 2`.
- Côté **gitflow** on remarquera que les branches sont issues de la branche `Develop`.
- Contrairement à l'**env-branching** où les branches ont comme racine `Main`.

Une fois le développement de ces deux branches terminé, elles sont proposées pour une review technique et mises en recette pour le client.

!!! Note ""
Les reviews techniques se déroulent généralement directement sur la pull request, contrairement aux reviews clientes que l'on
appelle généralement recette. Pour que le client puisse tester les nouvelles fonctionnalités, il est nécessaire de déployer la feature sur un environnement de test.

![Schéma 3](content/images/blog/2024/env-branching-git-flow-model/schema_3.svg)

Pour ces reviews clientes :
- **gitflow** propose la chose suivante : on merge `Feature 1` et `Feature 2` en fermant leurs pull request respectives dans `Develop`. 
A partir de la branche `Develop`, on deploie sur un environnement de recette pour le client. Ainsi il pourra reviewer les nouvelles fonctionnalités.

- **env-branching** propose de pousser les modifications dans `Staging` **sans fermer les PR associées**. Elles sont toujours ouvertes et taggées "en recette", pour la review cliente.

## Les ennuis commencent 

Imaginons maintenant que `Feature 2` est validée (chouette :sparkles:) mais que `Feature 1` ne l’est pas (bigre :gremlin-ohno:).

![Schéma 4](content/images/blog/2024/env-branching-git-flow-model/schema_4.svg)

:info: Côté **gitflow**, la branche `Develop` est dans un état tel qu’elle embarque malgré elle un commit erroné à corriger. Côté **env-branching**, `Staging` embarque elle aussi le commit de la PR à corriger. 

Pour faire les corrections :
![Schéma 5](content/images/blog/2024/env-branching-git-flow-model/schema_5.svg)

- **gitflow** : On ouvre une nouvelle branche, on fix, puis on merge : ça implique la création d’une nouvelle pull request et une nouvelle itération de reviews. 
- **env-branching** : La pull request n’étant pas fermée, les développeurs peuvent directement ajouter le fix à celle-ci, conservant ainsi un seul et même contexte pour tout le cycle de vie de la feature.


## La lumière au bout du tunnel
Cette fois, c’est bon, les features sont bonnes, on est fin prêt à les mettre en production. On va créer un release pour le déploiement.

![Schéma 6](content/images/blog/2024/env-branching-git-flow-model/schema_6.svg)


**Gitflow** gère sa release en créant une nouvelle branche `Release` à qui nous associons un tag de version. 
Celle-ci est issue de la branche `Develop` une fois qu’elle est dans un état prêt à être mis en production. 

:info: ll n’est donc pas possible de mettre en production une seule feature si une autre est aussi présente sur Develop, sauf en effectuant de nombreuses manipulations afin de trouver un nouvel état que l’on souhaite mettre en production. 

Cette branche `Release` est ensuite mergée dans `Main` et dans `Develop` pour le déploiement.

**Env-branching** se considère comme une méthode se passant de release. 
Virtuellement, à tout moment, on peut constituer une "release" en choisissant les PR souhaitées et en les mergeant dans `Main`.

!!! Note ""
    Pour préparer la release chez elao, on incrémente le numéro de version avec un commit que l’on pousse sur `Main`.
    _Ce numéro de version est important pour le suivi de l’évolution de l’application et pour les rapports erreurs Sentry par exemple._ 
    On crée un objet release sur github avec le changelog qui décrit toutes les PR qui sont embarquées dans celle-ci. 

## Une petite comparaison pas à pas

### La gestion des pull request 

- **gitflow** :  Quand les PR sont mergées dans `Develop`, elles sont fermées. Elles sont validées en review technique mais la feature, elle, n’a pas encore sa validation cliente. 
    Si une modification doit être faite, une nouvelle PR doit être réouverte et ça déclenche tout un nouveau cycle de review (pour la même feature).
- **env-branching** : Les PR son mergées dans `Staging` mais sans être fermées. Elles portent la responsabilité de traiter l’issue de la feature de bout en bout. 
Une fois validées par review technique et cliente (sur l’environnement staging), elles sont taggées comme “ready to merge”, et attendent d’être choisies pour une mise en production.

### La gestion des états 

- **gitflow** : La branche `Develop` est racine de toutes les nouvelles features. Elle permet d’ajouter une protection accrue pour branche `Main` en décalant la gestion des conflits sur une branche dédiée.
Par contre, quand un bugfix est ajouté à une branche `Release`, celle-ci doit être mergée dans `Develop`, et ça crée des arbres git moins linéaires. 

- **env-branching** : La branche `Staging` correspond à un environnement de pré-prod, la branche `Main` correspond à un environnement de prod. 
Les pull request qui partent de `Main` n’étant pas fermées, on peut les merger à loisir sur `Staging` pour les tester et les corriger afin d’obtenir des PR-feature complètes et prêtes à être mise en production sans plus de manipulations. 
:info: Par contre, contrairement au gitflow qui maintient un tronc commun à partir duquel partent toutes les nouvelles features, env-branching lui nécessite une attention particulière sur les éléments techniques impactants (_changement de règles de linting, corrections de déprecations, issues critiques ou concernant la stack de développement_). 
Toutes ces PRs doivent être le plus rapidement possible mergées dans `Main` pour être mise à la disposition des développeurs dans le tronc commun.  

!!! Note ""
    Par contre, avantage considérable, chez elao l’env-branching nous permet de multiplier les environnements (Staging 1, Staging 2, etc).
    Ainsi nous pouvons tester plusieurs features en parallèle sans qu’elles ne se marchent dessus.

### Déploiement des fonctionnalités

- **gitflow** : Les fonctionnalités se stackent et doivent attendre la prochaine release, qui embarque tout, pour être déployées.
- **env-Branching** : Les fonctionnalités peuvent être deployées indépendamment dès qu’elles sont prêtes. Il est aussi possible d’en deployer plusieurs sur une seule release. Il est ainsi possible de revoir entièrement la priorité des fonctionnels et quand les déployer.


## Conclusion
Le modèle d’env-branching que l’on applique à Elao offre une flexibilité et une rapidité accrue par rapport au gitflow classique. Il permet de développer, valider et déployer des fonctionnalités indépendamment, réduisant les conflits et accélérant le cycle de développement.  
Pour les développements cherchant à améliorer le flux de travail Git et une livraison de fonctionnalités plus rapide, le env-branching constitue une excellente option.

Néanmoins, le gitflow reste un modèle robuste et largement utilisé dans l’industrie, qui s’adapte aux plus grandes équipes qui nécessitent une structure de branche plus rigide. Son cycle s’adapte bien aux développements planifiés et aux projets-produits plus conséquents. 

//insert schema bilan

#### Sources

- [Gitflow : a successful git branching model](https://nvie.com/posts/a-successful-git-branching-model/)
- [Env-Branching with git](https://www.wearefine.com/news/insights/env-branching-with-git/)
- [Atlassian gitflow workflow](https://www.atlassian.com/git/tutorials/comparing-workflows/gitflow-workflow)
- [Gitflow cheatsheet by Daniel Kummer](https://danielkummer.github.io/git-flow-cheatsheet/)

Schémas et illustrations réalisés avec [Excalidraw](https://excalidraw.com/).
