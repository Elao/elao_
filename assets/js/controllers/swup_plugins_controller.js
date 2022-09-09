import { Controller } from '@hotwired/stimulus';
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
