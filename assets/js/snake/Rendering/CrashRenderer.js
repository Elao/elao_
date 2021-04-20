import Symbol from 'snake/Rendering/Symbol';

export default class CrashRenderer {
    constructor(snake) {
        this.snake = snake;
        this.element = document.createElementNS('http://www.w3.org/2000/svg', 'g');
    }

    attach(container) {
        container.appendChild(this.element);
    }

    createSymbol(x, y, value, move = 1) {
        const symbol = document.createElementNS('http://www.w3.org/2000/svg', 'text');

        symbol.innerHTML = value;

        // Style
        symbol.setAttribute('x', x + (Math.random() - 0.5) * move);
        symbol.setAttribute('y', y + (Math.random() - 0.5) * move);
        symbol.setAttribute('class', 'crash__symbol');

        this.element.appendChild(symbol);
    }

    update() {
        if (!this.snake.crash) {
            return;
        }

        const [x, y] = this.snake.crash;

        if (!this.element.children.length) {
            this.createSymbol(x, y, '@');
            this.createSymbol(x, y, '#');
            this.createSymbol(x, y, '!!');
        }
    }
}
