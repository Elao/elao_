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
        event.detail.options.plugins.push(
            // Swup remplace `#main` via `outerHTML` : sans ce plugin, le focus retombe
            // sur `<body>` et aucun lecteur d'écran n'est informé du changement de page.
            // Il se branche sur `contentReplaced` (et non `pageView`, aussi déclenché par
            // `enable()`, ce qui volerait le focus au chargement de chaque page) et fait
            // un `focus({ preventScroll: true })`, ce qui évite le double saut avec
            // SwupScrollPlugin.
            new SwupA11yPlugin({
                contentSelector: '#main',
                announcementTemplate: 'Navigation vers : {title}',
                urlTemplate: 'Nouvelle page à l\'adresse {url}',
            }),
            new SwupScrollPlugin(
                {
                    doScrollingRightAway: true,
                    animateScroll: {
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
