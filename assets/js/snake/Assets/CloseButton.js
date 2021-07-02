export default class CloseButton {
    constructor(onClick) {
        this.element = document.createElement('button');

        this.element.innerHTML = '&times;';
        this.element.className = 'close-button';
        this.element.type = 'button';

        this.element.addEventListener('click', onClick);
    }

    attach(container) {
        container.appendChild(this.element);
    }
}
