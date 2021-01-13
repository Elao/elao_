export default class LoadListener {
    constructor(callback) {
        this.callback = callback;

        this.callback();
    }
}
