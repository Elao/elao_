export default class CrashRenderer {
    constructor(snake) {
        this.snake = snake;
        this.container = null;
        this.element = document.createElementNS('http://www.w3.org/2000/svg', 'g');

        this.element.setAttribute('class', 'crash__symbol_container');
    }

    attach(container) {
        this.container = container;
        this.container.appendChild(this.element);
    }

    createSymbol(value) {
        const symbol = document.createElementNS('http://www.w3.org/2000/svg', 'text');

        symbol.innerHTML = value;

        // Style
        symbol.setAttribute('x', 0 + (Math.random() - 0.5) * 0.1);
        symbol.setAttribute('y', 0 + (Math.random() - 0.5) * 0.1);
        symbol.setAttribute('class', 'crash__symbol');
        //symbol.style.transformOrigin = `0px 0px`;

        this.element.appendChild(symbol);
    }

    update() {
        if (!this.snake.crash) {
            if (this.element.children.length) {
                this.element.innerHTML = '';
                this.container.classList.remove('shake');
            }

            return;
        }

        if (!this.element.children.length) {
            const { crash, head } = this.snake;
            const x = (crash[0] + head[0]) / 2 - 1;
            const y = (crash[1] + head[1]) / 2 - 1;

            this.element.setAttribute('style', `transform: translate(${x}px, ${y}px);`);

            this.createSymbol('@');
            this.createSymbol('#');
            this.createSymbol('$');
            this.createSymbol(';');
            this.createSymbol('?');

            this.container.classList.add('shake');
        }
    }
}
