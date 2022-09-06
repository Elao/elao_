import { Controller } from '@hotwired/stimulus';
import SwupMatomoPlugin from '@swup/matomo-plugin';

export default class extends Controller {
    static values = {
        enabled: false,
    };

    connect() {
        this.element.addEventListener('swup:pre-connect', this._onPreConnect);
    }

    disconnect() {
        this.element.removeEventListener('swup:pre-connect', this._onPreConnect);
    }

    _onPreConnect(event) {
        if (!this.enabledValue) {
            return;
        }

        event.detail.options.plugins.push(new SwupMatomoPlugin());
    }
}
