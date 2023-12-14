---
type:               "post"
title:              "Observer les changements de fichiers arbitraires avec Webpack"
date:               "2023-04-20"
lastModified:       ~
tableOfContent:     3

description: >
    Une astuce de confort pour booster votre productivité lorsque vous utilisez Webpack, en rechargeant automatiquement 
    votre navigateur lors de la modification de fichiers arbitraires (Twig, PHP, Yaml, …).

thumbnail:          "content/images/blog/thumbnails/webpack-tips-reload-arbitrary-file-changed.jpg"
tags:               ["Webpack", "JavaScript", "Tips"]
authors:            ["msteinhausser"]
credits:            { name: "Olena Bohovyk", url: "https://www.pexels.com/fr-fr/photo/tranches-de-fruits-orange-dans-un-verre-a-boire-transparent-3323682/" }
tweetId:            "1648967238009995266"
---

## Description

Si vous utilisez Webpack, vous êtes probablement déjà habitués à son serveur de dev et
au [_Hot Module Replacement_](https://webpack.js.org/guides/hot-module-replacement/) permettant de mettre à jour le
CSS et/ou JavaScript interprété dans votre navigateur, à chaque changement dans votre code source.  
Le bénéfice principal étant que cela ne nécessite aucun rafraîchissement de votre page. Cela permet de conserver
l'état de votre application et constater les changements en temps réel sans avoir à reproduire les actions effectuées.

Le parent pauvre de ce fonctionnel existe également sous le terme de _« Live Reload »_ et permet uniquement
d'automatiser le rechargement de la page lorsque vous modifiez un fichier.  
Ce mécanisme est moins optimal, car ne conserve pas l'état courant de l'application, mais permet tout de même de gagner
du temps lors de vos développements selon les situations.

Le _live reload_ ne s'applique par défaut qu'aux fichiers JavaScript et CSS, mais il est possible
depuis [**Webpack DevServer 4**](https://github.com/webpack/webpack-dev-server/releases/tag/v4.0.0-beta.2) de l'étendre
de façon arbitraire à n'importe quel(s) fichier(s) de votre application au travers
de l'option [`devServer.watchFiles`](https://webpack.js.org/configuration/dev-server/#devserverwatchfiles).

### Avec Webpack

Par exemple, vous pourriez vouloir recharger automatiquement la page dans votre navigateur lorsque vous modifiez
un fichier Twig ou de l'un des contenus rendus dans votre application [Symfony](../../term/symfony.md) :

```js
// webpack.config.js
module.exports = {
  // …
  devServer: {
    liveReload: true,
    // Watch Twig, YAML and Markdown files to force reload the browser on changes:
    watchFiles: [
      'templates/**/*.twig',
      'content/**/*.{yaml,md}',
    ],
  },
};
```

Ainsi, lorsque vous lancerez votre serveur de dev avec `npx webpack serve`, votre navigateur rechargera automatiquement
la page dès lors que vous modifierez un fichier correspondant à l'un des patterns définis.

### Avec Webpack Encore

Si vous développez une application [Symfony](../../term/symfony.md), il y a toutes les chances que vous utilisiez
[Webpack Encore](https://symfony.com/doc/current/frontend.html).

Auquel cas, la configuration est similaire :

```js
// webpack.config.js
const Encore = require('@symfony/webpack-encore');

Encore
  // …
  .configureDevServerOptions(options => {
    // Watch Twig, YAML and Markdown files to force reload the browser on changes:
    options.liveReload = true;
    options.watchFiles = [
      'templates/**/*.twig',
      'content/**/*.{yaml,md}',
    ];

    // Disable watching the static `public` folder since it would force a live reload on any change,
    // as the manifest.json file is always re-computed (but not required by the dev server):
    options.static.watch = false;
  })
```

### Avec une version antérieure à Webpack 5 / Webpack DevServer 4

Si vous utilisez une version antérieure à Webpack 5 / Webpack DevServer 4, vous pouvez toujours utiliser
[`chokidar`](https://github.com/paulmillr/chokidar) et un middleware pour simuler le comportement
de `devServer.watchFiles` :

```js
// webpack.config.js
const Encore = require('@symfony/webpack-encore');
const path = require('path');
const chokidar = require('chokidar');

Encore
  // …
  .configureDevServerOptions(options => {
    // Watch Twig, YAML and Markdown files to force reload the browser on changes:
    options.onBeforeSetupMiddleware = (devServer) => {
      const files = [
        path.resolve(__dirname, 'templates/**/*.html.twig'),
        path.resolve(__dirname, 'content/**/*.{yaml,md}'),
      ];

      chokidar.watch(files).on('all', () => {
        devServer.sockWrite(devServer.sockets, 'content-changed');
      });
    }
  })
```

## Conclusion

Cette astuce est particulièrement utile lorsque vous développez des contenus statiques ou travaillez sur la structure de
vos pages HTML. Les applications de type "pages de contenus", telles que celles utilisant 
[Stenope](../../term/stenope.md), font généralement de bonnes candidates.  
C'est d'ailleurs le cas du site sur lequel vous vous trouvez actuellement, généré avec Stenope à partir de fichiers
Twig, Yaml et Markdown (comme en atteste
son [code source](https://github.com/Elao/elao_/blob/cca48190d21277b39b682c461054ce739cfe452e/webpack.config.js#L88-L99)).

## Sources

- [feat: add watchFiles option](https://github.com/webpack/webpack-dev-server/pull/3136)
- [devServer.watchFiles](https://webpack.js.org/configuration/dev-server/#devserverwatchfiles)
- [devServer.liveReload](https://webpack.js.org/configuration/dev-server/#devserverlivereload)
- [What is exactly Hot Module Replacement n Webpack?](https://stackoverflow.com/questions/24581873/what-exactly-is-hot-module-replacement-in-webpack)
