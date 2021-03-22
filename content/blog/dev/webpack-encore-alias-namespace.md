---
type:               "post"
title:              "Des namespaces en JavaScript ? C’est possible avec les alias Webpack Encore !"
date:               "2020-07-30"
publishdate:        "2020-07-31"
draft:              false

description:        "Avec les alias Webpack Encore, adoptez la souplesse des namespaces PHP dans vos modules JavaScript avec des chemins absolus pour un code plus lisible et plus facile à refactorer."

thumbnail:          "images/posts/thumbnails/webpack-encore-alias.jpg"
header_img:         "images/posts/headers/webpack-encore-alias.jpg"
credits:            { name: "Jannes Glas" , url: "https://unsplash.com/@jannesglas" }

tags:               ["Symfony", "JavaScript", "Webpack"]
categories:         ["JavaScript", "Dev", "Symfony"]
tweetId: "1289132577694121985"
author:    "tjarrand"
---

Si vous travaillez avec Symfony et gérez votre base de code javascript avec Webpack Encore, j'ai aujourd'hui une petite astuce méconnue qui pourrait vous simplifier la vie : les alias Webpack.

<!--more-->

_Note: Si vous n'utilisez pas Webpack Encore dans vos projets Symfony, vous devriez peut-être y jeter un oeil ..._

## Contexte

Par défaut, les imports de modules ES6 utilisent un chemin __relatif__ à l'emplacement du fichier.

Prenons l'arborescence suivante :

```
assets/
    js/
        core/
            Loader.js
        media/
            audio/
                AudioPlayer.js
```

Si on souhaite charger la classe `Loader` depuis `AudioPlayer`, on s'y prend comme ça :

```javascript
import Loader from '../../core/Loader.js';
```

Ce n'est pas toujours plaisant à écrire de tête et encore moins à refactorer.

## Côté back

En PHP, on est habitué aux __namespaces__, qui permettent de référencer une classe par un chemin __absolu__ qui ne dépend donc pas de l'endroit où l'on se trouve.

On commence par définir un namespace pour un chemin donné dans notre `composer.json` :

```json
"autoload": {
    "psr-4": {
        "App\\": "src/"
    }
},
```

Et on peut maintenant utiliser la classe `src/Kernel.php` via le namespace `App/Kernel` partout dans notre application.

C'est ce qu'on souhaite mettre en place côté client grâce aux alias webpack !

## Les alias Webpack

Comme on configure l'autoload côté back, on peut configurer des alias côté front dans notre configuration Webpack Encore :

```javascript
//webpack.config.js
Encore
    // ...

    // Alias
    .addAliases({
        'App': `${__dirname}/assets/js`,
    })
```

On peut maintenant, depuis n'importe quel fichier, importer la classe `Loader` via un chemin absolu utilisant l'alias :

```javascript
import Loader from 'App/core/Loader.js';
```

Et voila, on a des "namespaces" pour nos modules javascript ! 🎉

## Bonus

Vous intégrez votre CSS et vos images au build directement depuis votre code JS ?

Déclarez des alias pour ceux-ci :

```javascript
//webpack.config.js
Encore
    // ...

    // Alias
    .addAliases({
        'App': `${__dirname}/assets/js`,
        'Style': `${__dirname}/assets/scss`,
        'Images': `${__dirname}/assets/images`,
    })
```

Et simplifiez-vous la vie :

```javascript
import 'Style/app.scss';
import logoPath from 'Images/logo.png';
```

---
