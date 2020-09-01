export default class ClickListener {
    constructor(callback) {
        this.callback = callback;

        this.onLoad = this.onLoad.bind(this);

        window.addEventListener('click', this.onLoad, { once: true });
    }

    onLoad() {
        this.callback();
    }
}
