export default class ScoreRenderer {
    constructor(snake) {
        this.snake = snake;
        this.score = null;
        this.element = document.createElementNS('http://www.w3.org/2000/svg', 'text');

        this.element.setAttribute('class', 'score');
    }

    attach(container, size, border, marginTop) {
        this.element.setAttribute('x', size + border);
        this.element.setAttribute('y', - marginTop + 0.1);

        container.appendChild(this.element);
    }

    update() {
        if (this.score !== this.snake.score) {
            this.score = this.snake.score;
            this.element.innerHTML = this.score.toString();
        }
    }
}
