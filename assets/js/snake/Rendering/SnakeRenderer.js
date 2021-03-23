export default class SnakeRenderer {
    constructor(snake) {
        this.snake = snake;
        this.element = document.createElementNS('http://www.w3.org/2000/svg', 'path');

        // Style
        this.element.setAttribute('fill', 'none');
        this.element.setAttribute('stroke', '#ffffff');
        this.element.setAttribute('stroke-width', '0.9');
        this.element.setAttribute('stroke-linecap', 'square');
        this.element.setAttribute('d', this.getPath());
    }

    attach(container) {
        container.appendChild(this.element);
    }

    getPath(progress) {
        const { positions } = this.snake;
        const last = positions.length - 1;

        return positions.reduce((path, point, index) => {
            let method = 'L';
            let [x, y] = point;

            if (index === 0) {
                const [nx, ny] = positions[index + 1];

                method = 'M';
                x = x + (1 - progress) * (nx - x);
                y = y + (1 - progress) * (ny - y);
            }

            if (index === last) {
                const [nx, ny] = positions[index - 1];

                x = x + progress * (nx - x);
                y = y + progress * (ny - y);
            }

            return `${path} ${method}${x},${y}`;
        }, '');
    }

    update(progress) {
        this.element.setAttribute('d', this.getPath(this.snake.alive ? progress : 1));
    }
}
