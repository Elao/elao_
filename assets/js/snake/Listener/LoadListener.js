export default class LoadListener {
    constructor(callback) {
        this.callback = callback;

        this.onLoad = this.onLoad.bind(this);

        window.addEventListener('load', this.onLoad);
    }

    onLoad() {
        this.callback();
    }
}
