export default class Snake {
    static get length() { return 5; }

    static defaultPositions(length = this.length, x = 0, y = 0) {
        return new Array(length).fill(null).map((v, i) => [x + length - i, y]);
    }

    constructor() {
        this.alive = false;
        this.horizontal = true;
        this.forward = true;
        this.positions = [];
        this.lastTail = null;
        this.crash = null;
        this.died = null;

        this.reset();
    }

    get head() {
        return this.positions[0];
    }

    get score() {
        return this.positions.length - this.constructor.length;
    }

    eat() {
        if (this.lastTail) {
            this.positions.push(this.lastTail);
            this.lastTail = null;
        }
    }

    die(crash) {
        this.alive = false;
        this.crash = crash;
        this.died = Date.now();
    }

    reset() {
        this.positions.length = 0;
        this.positions.push(...this.constructor.defaultPositions());
        this.horizontal = true;
        this.forward = true;
        this.alive = true;
        this.crash = null;
        this.died = null;
    }

    isHorizontal() {
        return this.positions[0][1] === this.positions[1][1];
    }

    update(nextHead) {
        this.lastTail = this.positions.pop();
        this.positions.unshift(nextHead);
    }

    getNextHead() {
        const value = this.forward ? 1 : -1;
        const [x, y] = this.head;

        return this.horizontal ? [x + value, y] : [x, y + value];
    }

    getNextTail() {
        return this.positions.slice(0, -1);
    }

    onInput(type) {
        switch (type) {
            case 'left':
                this.setDirection(true, false);
                break;
            case 'right':
                this.setDirection(true, true);
                break;
            case 'up':
                this.setDirection(false, false);
                break;
            case 'down':
                this.setDirection(false, true);
                break;
            default:
                throw new Error(`Unsopported direction ${type}`);
        }
    }

    setDirection(horizontal, forward) {
        if (horizontal !== this.isHorizontal()) {
            this.horizontal = horizontal;
            this.forward = forward;
        }
    }
}
