---
type:               "post"
title:              "Meteor - Archi. multi-apps connectées"
date:               "2015-06-23"
lastModified:       ~

description:        "Architecture d'une application Meteor décomposée en plusieurs sous-applications connectées à la même base de données MongoDb."

thumbnail:          "images/posts/thumbnails/beer.jpg"
banner:             "images/posts/headers/elephpant_elao.jpg"
tags:               ["Meteor", "Tips", "Développement", "Bonnes pratiques"]
categories:         ["Web", "Meteor"]

authors:            ["jgaulupeau"]
---

Etant donnée que Meteor envoie tous les fichiers du projet aux clients web (hormis les dossiers spéciaux tels que `/server`, `/private`, `/public`), toute l'application est packagée et envoyée.<!--more--> Cela pose plusieurs problèmes dont :
- la difficulté d'alléger la masse de code envoyé au client (templates html + helpers & libs js + styles css) ;
- la difficulté de "protéger" son application par omission de code (typiquement le code de la partie `/admin` ne devrait être envoyé qu'aux utilisateurs de type admin).

Pour palier à ces problèmes, nous architecturons notre application en 2 sous-applications :
- le *front* pour les users standards (sur le domaine `http://project.dev` par exemple)
- le *back* pour les administrateurs (sur le sous-domaine `http://admin.project.dev` par exemple)

Ces deux applications utilisent les mêmes données et doivent donc accéder à la même base de données.

## Architecture des dossiers/fichiers

```
➜  my-project  tree -a
.
├── .gitignore
├── README.md
├── back
│   ├── .meteor
│   │   ├── ...
│   ├── back.css
│   ├── back.html
│   ├── back.js
│   └── packages
│       └── collections -> ../../packages/collections
├── front
│   ├── .meteor
│   │   ├── ...
│   ├── front.css
│   ├── front.html
│   ├── front.js
│   └── packages
│       └── collections -> ../../packages/collections
└── packages
    └── collections
        ├── package.js
        ├── players.js
        └── playgrounds.js
```

Nous avons deux applications Meteor (*front* et *back*). Ces deux applications portent des liens symboliques vers des packages du dossier `/packages` afin de faire de la réutilisation de code.

## Run des applications

En local (DEV), nous prenons le parti de lancer Mongo sur l'application *front* et de connecter le *back* sur le Mongo du *front*.

Par défaut, Meteor lance l'**application** sur le port **3000** et **Mongo** est ouvert sur le port **3001**.

```shell
➜  front  meteor
[...]
=> App running at: http://localhost:3000/
```

Il reste à lancer l'application *back* en spécifiant l'host Mongo sur lequel se connecter et le port sur lequel l'application sera accessible (3000 étant déjà occupé par le *front*).

```shell
➜  back  MONGO_URL=mongodb://localhost:3001/meteor MONGO_OPLOG_URL=mongodb://localhost:3001/local meteor -p=3080
[...]
=> App running at: http://localhost:3080/
```

## Conclusion

En conclusion, décomposer une application en plusieurs sous-applications permet de mieux gérer son code et d'intervenir sur les sources envoyées aux clients.

Cela légitimise la création de packages "applicatifs" (telles que les collections, règles métiers partagées, etc.), ce qui est une bonne pratique !
