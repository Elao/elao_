const LEFT = 37;
const RIGHT = 39;
const UP = 38;
const DOWN = 40;
const A = 65;
const B = 66;
const KONAMICODE = [UP,UP,DOWN,DOWN,LEFT,RIGHT,LEFT,RIGHT,B,A];

export default class Launcher {
    constructor(files, styles, trigger) {
        this.files = files;
        this.styles = styles;
        this.trigger = trigger;
        this.sequence = new Array(KONAMICODE.length).fill(0);
        this.engine = null;

        this.onClick = this.onClick.bind(this);
        this.onReady = this.onReady.bind(this);
        this.onKey = this.onKey.bind(this);

        this.start();
    }

    start() {
        this.trigger.addEventListener('click', this.onClick);
        document.addEventListener('keyup', this.onKey);
    }

    stop() {
        this.trigger.removeEventListener('click', this.onClick);
        document.removeEventListener('keyup', this.onKey);
    }

    onKey(event) {
        const { keyCode } = event;

        this.sequence.push(keyCode);
        this.sequence.shift();

        if (this.sequence.join('') === KONAMICODE.join('')) {
            this.load();
        }
    }

    onClick(event) {
        event.preventDefault();
        this.load();
    }

    load() {
        if (this.files.length) {
            window.addEventListener('snake-ready', this.onReady);

            this.styles.forEach(url => document.head.appendChild(this.getStyle(url)));
            this.styles.length = 0;

            this.files.forEach(url => document.head.appendChild(this.getScript(url)));
            this.files.length = 0;
        } else {
            this.onReady();
        }
    }

    onReady(event) {
        if (!this.engine) {
            const { Engine } = event.detail;

            this.engine = new Engine();
        }

        this.engine.start();
    }

    getScript(url) {
        const element = document.createElement('script');

        element.src = url;
        element.defer = true;

        return element;
    }

    getStyle(url) {
        const element = document.createElement('link');

        element.rel = 'stylesheet';
        element.href = url;
        element.defer = true;

        return element;
    }
}
