---
type:           "post"
title:          "Gérer un projet AGILE avec GitHub"
date:           "2017-04-24"
publishdate:    "2017-04-26"
draft:          false

description:    "Retour d'expérience sur la gestion d'un projet avec GitHub."

thumbnail:      "images/posts/thumbnails/github-agile.jpg"
header_img:     "images/posts/headers/github-agile.jpg"
tags:           ["agile", "scrum", "kanban", "gestion de projet", "github"]
categories:     ["methodo"]

author: "mcolin"

---

Nombreux sont les outils de gestion de projets auxquels un développeur peut se frotter durant sa carrière. J'ai moi même eu affaire à plusieurs d'entre eux : des solutions de ticketing (Mantis, BugZilla, Redmine, ...), des boards Kanban (Trello, Taiga), des solutions tout-en-un complexes (Jira), des forges (GitHub, GitLab) et même des solutions manuelles (carnet de note, post-it, noeud à mon mouchoir).

Certaines solutions étant trop simples pour adresser tous mes besoins (Trello par exemple) ou trop complexes pour être agréables à utiliser (Jira), je me retrouve souvent à jongler entre plusieurs outils qui ne sont en plus pas toujours les mêmes d'un projet à l'autre.

Mon envie était donc de trouver un moyen d'adresser tous mes besoins à l'aide d'un seul outil, simple et efficace. Et là, GitHub annonce de [nouvelles fonctionnalités](https://github.com/blog/2256-a-whole-new-github-universe-announcing-new-tools-forums-and-features) dont GitHub Project (oui je sais, GitLab a sorti une fonctionnalité similaire auparavant, mais je suis moins fan de l'ergonomie GitLab ; d'ailleurs, tout ce que je décris dans cet article est réalisable avec GitLab).

## Besoins

Les besoins que j'ai au quotidien sur un projet sont :

* Système de story/feature (AGILE)
* Notion de sprint
* Qualification (priorités, tags/categories, estimation)
* Kanban board
* Rédaction de spécifications
* Ticketing

### Les stories

J'ai représenté chaque **story** par une *issue* avec un titre et une description. Dans la description j'ai ajouté les tâches à réaliser avec les petites checkbox mardown de GitHub (`- [ ] TODO`) permettant d'afficher un pourcentage de complétion de la story.

Pour chaque **sprint** j'ai créé une *milestone* à laquelle j'ai assigné les *issues*. Il est possible de renseigner une *due date* pouvant correspondre à la date de fin du sprint ainsi qu'une description parfaite pour le *sprint goal*. A l'intérieur d'une *milestone* il est possible de réordonner les *issues* par *drag'n'drop*. Un pourcentage de complétion indique le ratio d'*issues* clôturées. Le filtre `no:milestone` dans la liste des *issues* permet d'afficher le backlog.

![GitHub Project](/images/posts/2017/github-milestones.jpg)

Pour la **qualification**, j'ai utilisé les *labels*. J'ai créé trois labels de priorité (prio haute rouge, prio normale jaune et prio faible vert), des labels *question*, *bug*, *feature*, *enhancement* pour indiquer la nature du ticket, des labels *dev*, *inte* et *infra* pour identifier les corps de métier impliqués ainsi que des labels de qualification métiers.

L'onglet conversation des *issues* est très pratique pour discuter de la *story* avec le *product owner*. L'interface permet simplement d'ajouter texte, liens, documents et images. L'historique permet de visualiser la vie de la *story* (fermeture, réouverture, changement de priorité, commentaires, assignations, ...).

Concernant l'**estimation**, je n'ai rien trouvé dans l'interface de GitHub permettant d'indiquer la valeur des tickets. J'ai commencé à noter la complexité dans le titre sous le format suivant juste pour me repérer :

> [5] Ma super story

Puis j'ai finalement opté pour la méthode [no estimate](https://blog.goood.pro/2014/07/25/developper-sans-faire-destimation-le-mouvement-noestimates/) estimant qu'en découpant correctement les *stories*, le pourcentage de *stories* complétées suffisait pour estimer l'avancement du *sprint*.

### Le board

Fonctionalité arrivée récemment, [GitHub Project](https://help.github.com/articles/about-projects/) est un *card board* de type Kanban. Vous pouvez créer plusieurs *project* créant ainsi un *board* pour chacun d'eux. Si vous avez déjà utilisé Trello ou Jira, vous ne serez pas dépaysés. L'outil est plutôt simple, vous créez des colonnes et vous y ajoutez des *cards* que vous pouvez déplacer par *drag'n'drop*. Par défaut les *cards* ne contiennent qu'un champ texte. Mais il est possible de les convertir en *issues*. Vous pouvez également directement ajouter vos *issues* ou *pull requests* existantes grâce au bouton *Add cards*.

Une fois vos *issues* ajoutées au *board*, vous voyez leur titre, leur numéro, leurs labels, leur état (ouvert/fermé) et les personnes affectées à la tâche.

Cela manque encore un peu de fonctionnalités, comme le fait de pouvoir masquer les *issues* fermées ou bien de pouvoir assigner ou clore les *issues* directement depuis le *board*, mais je suis sûr que l'équipe de GitHub ajoutera ces fonctionnalités dans l'avenir.

![GitHub Project](/images/posts/2017/github-project.jpg)

### Les spécifications

Concernant les spécifications, las d'avoir des documents Word lourds dont on ne sait jamais où est la dernière version, et toujours dans l'optique de tout centraliser sur GitHub, j'ai proposé de les écrire en *markdown* directement dans les sources du projet dans un répertoire `specs`. Ainsi elles seraient versionnées au même titre que les sources et il serait possible de conserver l'historique des modifications.

La création d'une *pull request* pour les soumettre permet d'ouvrir une discussion avant de les *merger*. On peut même pousser l'idée en soumettant le code correspondant aux spécifications ajoutées ou modifiées dans la même *pull request*.

Bien sûr, une telle pratique nécessite un minimum de connaissances techniques (markdown, git, ...) mais en utilisant l'interface de GitHub pour créer les fichiers, les connaissances requises sont minimes.

Alternativement vous pouvez utiliser la fonction wiki de GitHub, plus simple, et qui dispose également d'une fonctionnalité de versioning. GitHub crée un *repository* supplémentaire pour ce wiki. Vous pouvez récupérer les sources sous forme de fichier markdown en ajoutant `.wiki` dans l'url de votre repository. En l'ajoutant aux *submodules* de votre *repository* principal vous pourrez le récupérer en même temps que vos sources.

> `git@github.com:Elao/blog.wiki.git`

Vous pouvez même ajouter un [webhook github](https://developer.github.com/v3/activity/events/types/#gollumevent) afin d'être averti à chaque modification du wiki.

## Conclusion

J'ai utilisé cette méthode sur un projet de 3 mois avec un développeur (moi), une intégratrice et un <abbr title="Product Owner">PO</abbr> client déjà familier avec GitHub. Le PO s'est bien pris au jeu et a lui-même ouvert de nombreuses discussions sur ses fonctionnalités, qualifié et priorisé ses stories et a ainsi complètement géré la direction de son projet. De mon côté je pouvais donner un avis technique sur la faisabilité et la complexité de la fonctionnalité.

Il n'y a pas une façon unique de faire de l'**AGILE**, de nombreuses solutions et méthodes existent.
Pour être efficaces choisissez vos outils avec soin et adaptez vos méthodes à votre client et à votre équipe.
