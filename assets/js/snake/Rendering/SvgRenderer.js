import MapRenderer from 'snake/Rendering/MapRenderer';
import SnakeRenderer from 'snake/Rendering/SnakeRenderer';
import PointRenderer from 'snake/Rendering/PointRenderer';
import CrashRenderer from 'snake/Rendering/CrashRenderer';
import ScoreRenderer from 'snake/Rendering/ScoreRenderer';
import EndRenderer from 'snake/Rendering/EndRenderer';
import Logo from 'snake/Assets/Logo';
import CloseButton from 'snake/Assets/CloseButton';

export default class SvgRenderer {
    static createElement(size, margin) {
        const element = document.createElementNS('http://www.w3.org/2000/svg', 'svg');

        // Attributes
        element.setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns', 'http://www.w3.org/2000/svg');
        element.setAttributeNS('http://www.w3.org/XML/1998/namespace', 'xml:space', 'preserve');
        element.setAttribute('xmlns:xlink', 'http://www.w3.org/1999/xlink');
        element.setAttribute('version', '1.1');
        element.setAttribute('viewBox', `${-margin} ${-margin} ${size + margin * 2} ${size + margin * 2}`);
        element.setAttribute('class', 'snake-renderer');

        return element;
    }

    constructor(game, onClose, touchControls, margin = 3.5) {
        this.map = new MapRenderer(game.size);
        this.snake = new SnakeRenderer(game.snake);
        this.pixels = new PointRenderer(game.pixels);
        this.crash = new CrashRenderer(game.snake, this.container);
        this.score = new ScoreRenderer(game.snake);
        this.end = new EndRenderer(game.snake);
        this.logo = new Logo();
        this.close = new CloseButton(onClose);
        this.touchControls = touchControls;
        this.margin = margin;
        this.element = document.createElement('div');
        this.svg = this.constructor.createElement(game.size, this.margin);
        this.container = document.createElementNS('http://www.w3.org/2000/svg', 'g');

        this.element.appendChild(this.svg);
        this.svg.appendChild(this.container);

        this.element.className = 'snake-container';

        this.period = game.period;
        this.time = 0;
        this.width = null;
        this.height = null;

        this.update = this.update.bind(this);

        this.map.attach(this.container);
        this.snake.attach(this.container);
        this.pixels.attach(this.container);
        this.score.attach(this.container, game.size, this.map.border);
        this.logo.attach(this.container, game.size, this.map.border);
        this.crash.attach(this.container);
        this.end.attach(this.element);
        this.close.attach(this.element);
        this.touchControls.attach(this.element);
    }

    attach() {
        document.body.classList.add('snake');
        document.body.appendChild(this.element);
    }

    detach() {
        document.body.classList.remove('snake');
        document.body.removeChild(this.element);
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
        this.end.update();
    }
}
