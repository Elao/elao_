export default class Pixel {
    static getRandomPoint(size) {
        return Math.floor(Math.random() * (size - 1));
    }

    constructor(x, y) {
        this.x = x;
        this.y = y;
    }

    match(x, y) {
        return x === this.x && y === this.y;
    }
}
