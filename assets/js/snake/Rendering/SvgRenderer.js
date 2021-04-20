import MapRenderer from 'snake/Rendering/MapRenderer';
import SnakeRenderer from 'snake/Rendering/SnakeRenderer';
import PixelsRenderer from 'snake/Rendering/PixelsRenderer';
import CrashRenderer from 'snake/Rendering/CrashRenderer';
import styles from 'snake/Rendering/styles';

export default class SvgRenderer {
    static createElement(size, margin = 3) {
        const element = document.createElementNS('http://www.w3.org/2000/svg', 'svg');

        // Attributes
        element.setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns', 'http://www.w3.org/2000/svg');
        element.setAttributeNS('http://www.w3.org/XML/1998/namespace', 'xml:space', 'preserve');
        element.setAttribute('xmlns:xlink', 'http://www.w3.org/1999/xlink');
        element.setAttribute('version', '1.1');
        element.setAttribute('viewBox', `${-margin} ${-margin} ${size + margin * 2} ${size + margin * 2}`);

        // Style
        element.style.width = '100vw';
        element.style.height = '100vh';
        element.style.position = 'fixed';
        element.style.zIndex = 1001;
        element.style.top = 0;
        element.style.left = 0;
        element.style.right = 0;
        element.style.bottom = 0;
        element.style.backgroundColor = '#FF4344';

        const style = document.createElementNS('http://www.w3.org/2000/svg', 'style');

        style.innerHTML = styles;

        element.appendChild(style);

        return element;
    }

    constructor(game) {
        this.map = new MapRenderer(game.size);
        this.snake = new SnakeRenderer(game.snake);
        this.pixels = new PixelsRenderer(game.pixels);
        this.crash = new CrashRenderer(game.snake);
        this.element = this.constructor.createElement(game.size);

        this.period = game.period;
        this.time = 0;
        this.width = null;
        this.height = null;

        this.update = this.update.bind(this);

        this.map.attach(this.element);
        this.snake.attach(this.element);
        this.pixels.attach(this.element);
        this.crash.attach(this.element);

        document.body.appendChild(this.element);
    }

    onGameFrame() {
        this.time = 0;
    }

    update(time) {
        this.time += time;
        this.snake.update((this.time / this.period) % this.period);
        this.pixels.update();
        this.crash.update();
    }
}
