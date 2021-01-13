export default class SnakeRenderer {
    constructor(snake) {
        this.snake = snake;
        this.element = document.createElementNS('http://www.w3.org/2000/svg', 'path');

        // Style
        this.element.setAttribute('fill', 'none');
        this.element.setAttribute('stroke', '#ffffff');
        this.element.setAttribute('stroke-width', '1');
        this.element.setAttribute('stroke-linecap', 'square');
        this.element.setAttribute('d', this.getPath());
    }

    attach(container) {
        container.appendChild(this.element);
    }

    getPath() {
        return this.snake.positions.reduce((path, point, index) => {
            if (index === 0) {
                return path + ` M ${point.join(' ')}`;
            }

            return path + ` L ${point.join(' ')}`;
        }, '');
    }

    update() {
        this.element.setAttribute('d', this.getPath());
    }
}
