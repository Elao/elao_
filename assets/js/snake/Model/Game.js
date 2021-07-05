import Snake from 'snake/Model/Snake';
import Pixel from 'snake/Model/Pixel';

export default class Game {
    constructor(period = 200, size = 16) {
        this.period = period;
        this.size = size;
        this.snake = new Snake();
        this.pixels = [];
        this.timeouts = [];

        this.addPixel = this.addPixel.bind(this);
    }

    reset() {
        this.snake.reset();

        this.timeouts.forEach(timeout => clearTimeout(timeout));
        this.timeouts.length = 0;

        this.pixels.length = 0;

        this.timeouts = new Array(5).fill(null).map((v, i) => setTimeout(this.addPixel, i * 1500));
    }

    addPixel() {
        this.pixels.push(this.generatePixel());
    }

    update() {
        if (!this.snake.alive) {
            return;
        }

        const nextHead = this.snake.getNextHead();
        const pixel = this.getPixelAt(...nextHead);

        if (pixel) {
            this.pixels.splice(this.pixels.indexOf(pixel), 1, this.generatePixel());
            this.snake.eat();
        }

        if (this.hasCollision(...nextHead)) {
            this.end(nextHead);
        } else {
            this.snake.update(nextHead);
        }
    }

    onInput(type) {
        if (!this.snake.alive) {
            if (Date.now() - this.snake.died < 3000) {
                return;
            }

            return this.reset();
        }

        this.snake.onInput(type);
    }

    hasCollision(x, y) {
        const tail = this.snake.getNextTail();

        return x < 0 || x > this.size || y < 0 || y > this.size || tail.some(([tx, ty]) => tx === x && ty === y);
    }

    generatePixel() {
        let x, y;

        while (this.getPixelAt(x, y) !== null || this.hasCollision(x, y)) {
            x = Pixel.getRandomPoint(this.size);
            y = Pixel.getRandomPoint(this.size);
        }

        return new Pixel(x, y);
    }

    getPixelAt(x, y) {
        if (x === undefined || y === undefined) {
            return true;
        }

        return this.pixels.find(pixel => pixel.match(x, y)) || null;
    }

    end(crash) {
        console.info('💀');
        this.snake.die(crash);
    }
}
