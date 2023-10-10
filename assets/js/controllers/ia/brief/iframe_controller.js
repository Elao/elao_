import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static values = {
        /** The URL of the Amabla Briefs demo */
        host: String,
        /** The configuration of the Amabla Briefs demo */
        briefs: Object,
    };

    connect() {
        const briefs = this.briefsValue;
        const queryParams = new URLSearchParams(window.location.search);
        const selectedBrief = queryParams.get('brief');
        const brief = { ...briefs['default'], ...(briefs[selectedBrief] ?? briefs['default'] ?? {}) };

        this.element.setAttribute('src', this.buildUrl(brief));
    }

    buildUrl(brief) {
        const { assistant, task, workspace, role } = brief;

        const url = new URL(this.hostValue);
        url.searchParams.append('assistant', assistant);
        url.searchParams.append('task', task);
        url.searchParams.append('workspace', workspace);
        url.searchParams.append('role', role);

        return url.toString();
    }
}
