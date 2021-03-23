const Encore = require('@symfony/webpack-encore');
const path = require('path');
const chokidar = require('chokidar');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath(process.env.WEBPACK_PUBLIC_PATH || '/build')
    // only needed for CDN's or sub-directory deploy
    .setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Add 1 entry for each "page" of your app
     * (including one that's included on every page - e.g. "app")
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
     */
    .addEntry('app', './assets/js/app.js')
    .addEntry('see', './assets/js/snake/index.js') /* Snake Easter Egg */
    .addStyleEntry('style', './assets/scss/style.scss')

    // enables the Symfony UX Stimulus bridge (used in assets/bootstrap.js)
    .enableStimulusBridge('./assets/controllers.json')

    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    .configureBabel((config) => {
        config.plugins.push('@babel/plugin-proposal-class-properties');
    })

    // enables @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })

    // enables Sass/SCSS support
    .enableSassLoader()
    .enablePostCssLoader()

    // Alias
    .addAliases({
        snake: `${__dirname}/assets/js/snake/`,
    })

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment to get integrity="..." attributes on your script & link tags
    // requires WebpackEncoreBundle 1.4 or higher
    //.enableIntegrityHashes(Encore.isProduction())

    .copyFiles({
      from: './assets/images',
      to: 'images/[path][name].[hash:8].[ext]',
    })

    // uncomment if you're having problems with a jQuery plugin
    //.autoProvidejQuery()

    // uncomment if you use API Platform Admin (composer req api-admin)
    //.enableReactPreset()
    //.addEntry('admin', './assets/js/admin.js')

    // Required for styles hot reloading with Webpack dev server
    .disableCssExtraction(Encore.isDevServer())
    .configureDevServerOptions(options => {
        // Watch Twig files to force reload the browser on changes:

        // Supposed to work as of https://github.com/webpack/webpack-dev-server/pull/3136, but does not:
        // options.watchFiles = [path.join(__dirname, '/templates/**/*.twig')]

        options.onBeforeSetupMiddleware = (devServer) => {
            const files = [path.resolve(__dirname, 'templates/**/*.html.twig')]

            chokidar.watch(files).on('all', () => {
                devServer.sockWrite(devServer.sockets, 'content-changed')
            })
        }
    })
;

module.exports = Encore.getWebpackConfig();
