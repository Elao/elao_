export default class CrashRenderer {
    constructor(snake) {
        this.snake = snake;
        this.score = null;
        this.element = document.createElementNS('http://www.w3.org/2000/svg', 'text');

        this.element.setAttribute('fill', 'white');
        this.element.setAttribute('font-size', '0.8');
        this.element.setAttribute('font-family', 'faktum bold');
        this.element.setAttribute('text-anchor', 'end');
    }

    attach(container, size) {
        this.element.setAttribute('x', size + 0.5);
        this.element.setAttribute('y', -1.4);

        container.appendChild(this.element);
    }

    update() {
        if (this.score !== this.snake.score) {
            this.score = this.snake.score;
            this.element.innerHTML = this.score.toString();
        }
    }
}
