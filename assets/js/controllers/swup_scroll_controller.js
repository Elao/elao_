import { Controller } from '@hotwired/stimulus';
import SwupScrollPlugin from '@swup/scroll-plugin';

export default class extends Controller {
    connect() {
        this.element.addEventListener('swup:pre-connect', this._onPreConnect);
    }

    disconnect() {
        this.element.removeEventListener('swup:pre-connect', this._onPreConnect);
    }

    _onPreConnect(event) {
        event.detail.options.plugins.push(new SwupScrollPlugin(
            {
                doScrollingRightAway: true,
                animateScroll: {
                    betweenPages: false,
                }
            }
        ));
    }
}
