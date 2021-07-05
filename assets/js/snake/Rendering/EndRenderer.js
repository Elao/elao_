export default class EndRenderer {
    constructor(snake) {
        this.snake = snake;
        this.alive = true;
        this.container = null;
        this.element = document.createElement('div');

        this.element.innerHTML = `
            <p>Score :</p>
            <p class="end__score"></p>
            <p class="message">Appuyez pour rejouer <span>_</span></p>
            <div class="arrows">
                <div class="icon icon--arrow up"></div>
                <div class="break"></div>
                <div class="icon icon--arrow left"></div>
                <div class="icon icon--arrow down"></div>
                <div class="icon icon--arrow right"></div>
            </div
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
