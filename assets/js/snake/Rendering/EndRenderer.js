export default class EndRenderer {
    constructor(snake) {
        this.snake = snake;
        this.alive = true;
        this.container = null;
        this.element = document.createElement('div');

        this.element.innerHTML = `
            <p>Score :</p>
            <p class="end__score"></p>
            <p class="message">Appuyez sur une fleche pour rejouer</p>
        `;

        this.element.setAttribute('class', 'snake__end-panel');

        this.score = this.element.querySelector('.end__score');
    }

    attach(container) {
        this.container = container;
    }

    update() {
        if (this.alive !== this.snake.alive) {
            this.score.innerHTML = this.snake.score;
            this.alive = this.snake.alive;

            if (this.alive) {
                this.container.removeChild(this.element);
            } else {
                this.container.appendChild(this.element);
            }
        }
    }
}
