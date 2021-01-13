export default class Controls {
    constructor(onChange) {
        this.onChange = onChange;
        this.x = [false, false];
        this.y = [false, false];

        this.left = false;
        this.right = false;
        this.up = false;
        this.down = false;

        this.onKey = this.onKey.bind(this);
    }

    start() {
        document.addEventListener('keydown', this.onKey);
        document.addEventListener('keyup', this.onKey);
    }

    stop() {
        document.removeEventListener('keydown', this.onKey);
        document.removeEventListener('keyup', this.onKey);
    }

    onKey(event) {
        const { DOWN, UP, LEFT, RIGHT } = this.constructor;
        const { keyCode, type } = event;
        const active = type === 'keydown';

        switch (keyCode) {
            case LEFT:
                this.x[0] = active;
                break;
            case RIGHT:
                this.x[1] = active;
                break;
            case UP:
                this.y[0] = active;
                break;
            case DOWN:
                this.y[1] = active;
                break;

            default:
                console.log(type, keyCode);
                break;
        }

        this.resolve();
    }

    resolve() {
        let changed = false;

        if (this.setLeft(this.x[0] && this.x[0] !== this.x[1])) {
            return this.onChange('left', this.left);
        }

        if (this.setRight(this.x[1] && this.x[0] !== this.x[1])) {
            return this.onChange('right', this.right);
        }

        if (this.setUp(this.y[0] && this.y[0] !== this.y[1])) {
            return this.onChange('up', this.up);
        }

        if (this.setDown(this.y[1] && this.y[0] !== this.y[1])) {
            return this.onChange('down', this.down);
        }
    }

    setLeft(left) {
        if (this.left !== left) {
            this.left = left;

            return true;
        }

        return false;
    }

    setRight(right) {
        if (this.right !== right) {
            this.right = right;

            return true;
        }

        return false;
    }

    setUp(up) {
        if (this.up !== up) {
            this.up = up;

            return true;
        }

        return false;
    }

    setDown(down) {
        if (this.down !== down) {
            this.down = down;

            return true;
        }

        return false;
    }
}

Controls.LEFT = 37;
Controls.RIGHT = 39;
Controls.UP = 38;
Controls.DOWN = 40;
