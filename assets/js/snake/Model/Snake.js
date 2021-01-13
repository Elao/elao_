export default class Snake {
    constructor() {
        this.horizontal = true;
        this.forward = true;
        this.positions = [[0,0],[1,0],[2,0],[3,0]];
        this.head = this.positions[this.positions.length - 1];
    }

    update() {
        this.positions.shift();
        this.head = this.getNextHead();
        this.positions.push(this.head);
    }

    getNextHead() {
        const value = this.forward ? 1 : -1;
        const [x, y] = this.head;

        return this.horizontal ? [x + value, y] : [x, y + value];
    }

    onInput(type) {
        console.log('onInput', type);
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
        if (horizontal !== this.horizontal) {
            this.horizontal = horizontal;
            this.forward = forward;
            console.log('setDirection', this.horizontal, this.forward);
        }
    }
}
