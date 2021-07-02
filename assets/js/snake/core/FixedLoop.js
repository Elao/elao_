export default class FixedLoop {
    constructor(frequency, callback = null) {
        this.frequency = frequency;
        this.callback = callback;
        this.interval = null;
        this.update = this.update.bind(this);
    }

    setCallback(callback) {
        this.callback = callback;
    }

    start() {
        if (this.interval) {
            return;
        }

        this.interval = setInterval(this.update, this.frequency);
    }

    stop() {
        if (!this.interval) {
            return;
        }

        clearInterval(this.interval);

        this.interval = null;
    }

    update() {
        this.callback();
    }
}
