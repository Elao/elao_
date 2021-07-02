export default class TouchControls {
    constructor(onChange, snake) {
        this.onChange = onChange;
        this.snake = snake;
        this.available = 'ontouchstart' in window;
        this.element = document.createElement('div');
        this.element.className = 'touch-control';
        this.element.innerHTML = `
            <div class="pad">
                <div>
                    <button type="button" value="up">↖</button>
                    <button type="button" value="right">↗</button>
                </div>
                <div>
                    <button type="button" value="left">↙</button>
                    <button type="button" value="down">↘</button>
                </div>
            </div>
            <div class="pad">
                <div>
                    <button type="button" value="up">↖</button>
                    <button type="button" value="right">↗</button>
                </div>
                <div>
                    <button type="button" value="left">↙</button>
                    <button type="button" value="down">↘</button>
                </div>
            </div>
        `;

        this.onTouch = this.onTouch.bind(this);
    }

    attach(container) {
        if (!this.available) {
            return;
        }

        container.appendChild(this.element);
    }

    start() {
        if (!this.available) {
            return;
        }

        Array.from(this.element.querySelectorAll('button')).forEach(button => button.addEventListener('touchstart', this.onTouch, false));
    }

    stop() {
        if (!this.available) {
            return;
        }

        Array.from(this.element.querySelectorAll('button')).forEach(button => button.removeEventListener('touchstart', this.onTouch, false));
    }

    onTouch(event) {
        const { currentTarget } = event;

        this.onChange(currentTarget.value, true);
    }
}
