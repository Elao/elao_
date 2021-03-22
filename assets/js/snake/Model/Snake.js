export default class Snake {
    constructor() {
        this.alive = true;
        this.horizontal = true;
        this.forward = true;
        this.positions = [[6,0], [5,0], [4,0], [3,0], [2,0], [1,0], [0,0]];
        this.lastTail = null;
    }

    get head() {
        return this.positions[0];
    }

    eat() {
        if (this.lastTail) {
            this.positions.push(this.lastTail);
            this.lastTail = null;
        }
    }

    die() {
        this.alive = false;
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
                throw new Error(`Unsopported direction ${direction}`);
        }
    }

    setDirection(horizontal, forward) {
        if (horizontal !== this.isHorizontal()) {
            this.horizontal = horizontal;
            this.forward = forward;
        }
    }
}
