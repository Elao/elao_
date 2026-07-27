import { Controller } from '@hotwired/stimulus';
import SwupA11yPlugin from '@swup/a11y-plugin';
import SwupScrollPlugin from '@swup/scroll-plugin';
import SwupProgressPlugin from '@swup/progress-plugin';

export default class extends Controller {
    connect() {
        this.element.addEventListener('swup:pre-connect', this._onPreConnect);
    }

    disconnect() {
        this.element.removeEventListener('swup:pre-connect', this._onPreConnect);
    }

    _onPreConnect(event) {
        // Lu une seule fois, à la configuration : un changement de préférence en cours
        // de session n'est pas répercuté côté Swup (la partie CSS, elle, réagit).
        const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        event.detail.options.plugins.push(
            // Swup remplace `#main` via `outerHTML` : sans ce plugin, le focus retombe
            // sur `<body>` et aucun lecteur d'écran n'est informé du changement de page.
            // Il se branche sur `contentReplaced` (et non `pageView`, aussi déclenché par
            // `enable()`, ce qui volerait le focus au chargement de chaque page) et fait
            // un `focus({ preventScroll: true })`, ce qui évite le double saut avec
            // SwupScrollPlugin.
            new SwupA11yPlugin({
                contentSelector: '#main',
                // `h1` seul, et non le défaut `h1, h2, [role=heading]` : 280 des 544 pages
                // de contenu n'ont pas de `h1`, et le plugin y annoncerait le premier `h2`
                // venu — sur /blog, le titre du premier article au lieu de celui de la page.
                // Restreint à `h1`, il retombe sur `document.title`, toujours renseigné.
                headingSelector: 'h1',
                announcementTemplate: 'Navigation vers : {title}',
                urlTemplate: 'Nouvelle page à l\'adresse {url}',
            }),
            new SwupScrollPlugin(
                {
                    doScrollingRightAway: true,
                    animateScroll: reducedMotion ? false : {
                        betweenPages: true,
                    }
                }
            ),
            new SwupProgressPlugin({
                transition: 300,
                delay: 0,
                initialValue: 0.25,
                hideImmediately: true
            }),
        );
    }
}
