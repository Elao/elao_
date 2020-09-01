export default class Loop {
    constructor(callback) {
        this.callback = callback;
        this.frame = null;
        this.time = Date.now();
        this.update = this.update.bind(this);
    }

    setCallback(callback) {
        this.callback = callback;
    }

    start() {
        if (this.frame) {
            return;
        }

        this.frame = window.requestAnimationFrame(this.update);
        this.time = Date.now();
    }

    stop() {
        if (!this.frame) {
            return;
        }

        window.cancelAnimationFrame(this.frame);

        this.frame = null;
    }

    update() {
        this.frame = window.requestAnimationFrame(this.update);

        const { time } = this;

        this.time = Date.now();

        this.callback(this.time - time);
    }
}
