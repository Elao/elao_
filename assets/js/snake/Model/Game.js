import GameMap from 'snake/Model/GameMap';
import Snake from 'snake/Model/Snake';

export default class Game {
    constructor(speed = 200) {
        this.speed = speed;
        this.map = new GameMap();
        this.snake = new Snake();
    }

    update() {
        this.snake.update();
    }

    onInput(type) {
        this.snake.onInput(type);
    }
}
