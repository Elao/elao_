export default class SvgRenderer {
    static createElement() {
        const namespace = 'http://www.w3.org/2000/svg';
        const element = document.createElementNS(namespace, 'svg');

        // Attributes
        element.setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns', namespace);
        element.setAttributeNS('http://www.w3.org/XML/1998/namespace', 'xml:space', 'preserve');
        element.setAttribute('xmlns:xlink', 'http://www.w3.org/1999/xlink');
        element.setAttribute('version', '1.1');
        element.setAttribute('viewBox', '0 0 1000 1000');

        element.style.width = '100vw';
        element.style.height = '100vh';
        element.style.position = 'fixed';
        element.style.top = 0;
        element.style.left = 0;
        element.style.right = 0;
        element.style.bottom = 0;
        element.style.backgroundColor = '#FF4344';

        return element;
    }

    constructor() {
        this.element = this.constructor.createElement();

        this.background = null;
        this.width = null;
        this.height = null;

        this.onResize = this.onResize.bind(this);

        window.addEventListener('resize', this.onResize);

        this.onResize();

        document.body.appendChild(this.element);
    }

    onResize() {
        const { innerWidth, innerHeight } = window;

        this.width = innerWidth;
        this.height = innerHeight;

        this.element.setAttribute('viewBox', `0 0 ${this.width * 300/95} ${this.height * 300/95}`);
    }

    setBackground(background) {
        this.element.appendChild(background.element);
    }

    setLogo(logo) {
        this.element.appendChild(logo.element);
        logo.element.setAttribute(x, '50%');
        logo.element.setAttribute(y, '50%');
    }

    update() {
    }
}
