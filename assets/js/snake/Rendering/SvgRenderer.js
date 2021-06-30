import MapRenderer from 'snake/Rendering/MapRenderer';
import SnakeRenderer from 'snake/Rendering/SnakeRenderer';
import PixelsRenderer from 'snake/Rendering/PixelsRenderer';
import CrashRenderer from 'snake/Rendering/CrashRenderer';
import ScoreRenderer from 'snake/Rendering/ScoreRenderer';
import Logo from 'snake/Assets/Logo';
import styles from 'snake/Rendering/styles';

export default class SvgRenderer {
    static createElement(size, margin = 1.5, marginTop = 1.5) {
        const element = document.createElementNS('http://www.w3.org/2000/svg', 'svg');

        // Attributes
        element.setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns', 'http://www.w3.org/2000/svg');
        element.setAttributeNS('http://www.w3.org/XML/1998/namespace', 'xml:space', 'preserve');
        element.setAttribute('xmlns:xlink', 'http://www.w3.org/1999/xlink');
        element.setAttribute('version', '1.1');
        element.setAttribute('viewBox', `${-margin} ${-margin - marginTop} ${size + margin * 2} ${size + margin * 2 + marginTop}`);
        element.setAttribute('id', 'snake');

        // Style
        const style = document.createElementNS('http://www.w3.org/2000/svg', 'style');

        style.innerHTML = styles;

        element.appendChild(style);

        document.body.style.overflow = 'hidden';

        return element;
    }

    constructor(game) {
        this.map = new MapRenderer(game.size);
        this.snake = new SnakeRenderer(game.snake);
        this.pixels = new PixelsRenderer(game.pixels);
        this.crash = new CrashRenderer(game.snake, this.container);
        this.score = new ScoreRenderer(game.snake);
        this.logo = new Logo();
        this.element = this.constructor.createElement(game.size);
        this.container = document.createElementNS('http://www.w3.org/2000/svg', 'g');

        this.element.appendChild(this.container);

        this.period = game.period;
        this.time = 0;
        this.width = null;
        this.height = null;

        this.update = this.update.bind(this);

        this.map.attach(this.container);
        this.snake.attach(this.container);
        this.pixels.attach(this.container);
        this.crash.attach(this.container);
        this.logo.attach(this.container, game.size);
        this.score.attach(this.container, game.size);

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
        this.score.update();
    }
}
