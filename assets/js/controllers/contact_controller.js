import { Controller } from 'stimulus';

/*
 * Any element with a data-controller="contact" attribute will cause
 * this controller to be executed. The name "contact" comes from the filename:
 * contact_controller.js -> "contact"
 *
 * https://stimulus.hotwire.dev/handbook/introduction
 */
export default class extends Controller {
    static targets = ['main', 'form', 'success', 'email', 'inputEmail', 'inputContent', 'inputSign', 'greeting'];

    connect() {
        // init with the form:
        this.mainTarget.replaceChildren(document.importNode(this.formTarget.content, true));
    }

    submit() {
        const email = this.inputEmailTarget.value;
        const content = this.inputContentTarget.innerText;
        const sign = this.inputSignTarget.value;
        const to = this.emailTarget.textContent;

        const body = encodeURIComponent(`${content}

${sign}

${email}
`
        );

        const mailto = `mailto:${to}?subject=Contact&body=${body}&reply-to=${email}`;
        // Generate a link with target _blank to force opening in a new page, in case the user mail client is in browser:
        const link = document.createElement('a');
        link.href = mailto;
        link.target = '_blank';
        link.click();

        this.mainTarget.replaceChildren(document.importNode(this.successTarget.content, true));

        this.greetingTarget.textContent = sign;
    }

    reset() {
        this.mainTarget.replaceChildren(document.importNode(this.formTarget.content, true));
    }
}
