const LEFT = 37;
const RIGHT = 39;
const UP = 38;
const DOWN = 40;
const A = 65;
const B = 66;
const KONAMICODE = [UP,UP,DOWN,DOWN,LEFT,RIGHT,LEFT,RIGHT,B,A];

export default class Launcher {
    constructor(files, trigger) {
        this.files = files;
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

            this.files.forEach(file => document.head.appendChild(this.getScript(file)));
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

    getScript(src) {
        const element = document.createElement('script');

        element.src = src;
        element.defer = true;

        return element;
    }
}
