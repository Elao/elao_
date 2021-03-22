import GameMap from 'snake/Model/GameMap';
import Snake from 'snake/Model/Snake';
import Pixel from 'snake/Model/Pixel';

export default class Game {
    constructor(period = 160, size = 16) {
        this.period = period;
        this.map = new GameMap(size);
        this.snake = new Snake();
        this.pixels = [];

        this.pixels.push(this.generatePixel());
        this.pixels.push(this.generatePixel());
        this.pixels.push(this.generatePixel());
    }

    generatePixel() {
        let x, y;

        while (this.getPixelAt(x, y) !== null || this.hasCollision(x, y)) {
            x = Pixel.getRandomPoint(this.map.size);
            y = Pixel.getRandomPoint(this.map.size);
        }

        return new Pixel(x, y);
    }

    update() {
        if (this.snake.alive) {
            const nextHead = this.snake.getNextHead();
            const pixel = this.getPixelAt(...nextHead);

            if (pixel) {
                this.pixels.splice(this.pixels.indexOf(pixel), 1, this.generatePixel());
                this.snake.eat();
            }

            if (this.hasCollision(...nextHead)) {
                this.end();
            } else {
                this.snake.update(nextHead);
            }
        }
    }

    onInput(type) {
        this.snake.onInput(type);
    }

    hasCollision(x, y) {
        const { size } = this.map;
        const tail = this.snake.getNextTail();

        return x < 0 || x > size || y < 0 || y > size || tail.some(([tx, ty]) => tx === x && ty === y);
    }

    getPixelAt(x, y) {
        if (x === undefined || y === undefined) {
            return true;
        }

        return this.pixels.find(pixel => pixel.match(x, y)) || null;
    }

    end() {
        console.info('💀');
        this.snake.die();
    }
}
