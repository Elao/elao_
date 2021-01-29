import GameMap from 'snake/Model/GameMap';
import Snake from 'snake/Model/Snake';

export default class Game {
    constructor(speed = 200) {
        this.speed = speed;
        this.map = new GameMap();
        this.snake = new Snake();
    }

    update() {
        if (this.snake.alive) {
            const nextHead = this.snake.getNextHead();

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

        return x < 0 || x >= size || y < 0 || y >= size || tail.some(([tx, ty]) => tx === x && ty === y);
    }

    end() {
        console.log('💀');
        this.snake.die();
    }
}
