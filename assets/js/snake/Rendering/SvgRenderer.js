import MapRenderer from 'snake/Rendering/MapRenderer';
import SnakeRenderer from 'snake/Rendering/SnakeRenderer';

export default class SvgRenderer {
    static createElement(size) {
        const namespace = 'http://www.w3.org/2000/svg';
        const element = document.createElementNS(namespace, 'svg');

        // Attributes
        element.setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns', namespace);
        element.setAttributeNS('http://www.w3.org/XML/1998/namespace', 'xml:space', 'preserve');
        element.setAttribute('xmlns:xlink', 'http://www.w3.org/1999/xlink');
        element.setAttribute('version', '1.1');
        element.setAttribute('viewBox', `-1 -1 ${size + 2} ${size + 2}`);

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

        return element;
    }

    constructor(game) {
        this.map = new MapRenderer(game.map);
        this.snake = new SnakeRenderer(game.snake);
        this.element = this.constructor.createElement(game.map.size);
        //this.loop = new Loop(this.update);

        this.width = null;
        this.height = null;

        this.onResize = this.onResize.bind(this);

        window.addEventListener('resize', this.onResize);

        this.onResize();

        this.map.attach(this.element);
        this.snake.attach(this.element);

        document.body.appendChild(this.element);
    }

    onResize() {
        const { innerWidth, innerHeight } = window;

        this.width = innerWidth;
        this.height = innerHeight;

        //this.element.setAttribute('viewBox', `0 0 ${this.width * 300/95} ${this.height * 300/95}`);
    }

    setLogo(logo) {
        this.element.appendChild(logo.element);
        logo.element.setAttribute('width', '300');
        logo.element.setAttribute('height', '100');
        logo.element.setAttribute('x', '0');
        logo.element.setAttribute('y', '40');
    }

    update() {
        this.snake.update();
    }
}
