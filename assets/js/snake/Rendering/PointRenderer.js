export default class PointRenderer {
    constructor(pixels, length = 3) {
        this.pixels = pixels;
        this.length = length;
        this.element = document.createElementNS('http://www.w3.org/2000/svg', 'g');
        this.symbols = new Map();

        this.element.setAttribute('class', 'point__container');

        this.renderPixel = this.renderPixel.bind(this);
        this.removePixel = this.removePixel.bind(this);
    }

    static createSymbol(x, y, color, value = 'o') {
        const element = document.createElementNS('http://www.w3.org/2000/svg', 'text');

        element.setAttribute('fill', color);
        element.setAttribute('x', x);
        element.setAttribute('y', y);
        element.setAttribute('class', `point color-${color}`);
        element.style.transformOrigin = `${x}px ${y - 0.3}px`;

        element.innerHTML = value;

        return element;
    }

    attach(container) {
        container.appendChild(this.element);
    }

    getRandomColor() {
        return Math.min(Math.floor(Math.random() * this.length), this.length);
    }

    renderPixel(pixel) {
        if (this.symbols.has(pixel)) {
            return;
        }

        const { createSymbol } = this.constructor;
        const symbol = createSymbol(pixel.x, pixel.y, this.getRandomColor());

        this.element.appendChild(symbol);
        this.symbols.set(pixel, symbol);
    }

    removePixel(pixel) {
        const symbol = this.symbols.get(pixel);

        this.element.removeChild(symbol);
        this.symbols.delete(pixel);
    }

    update() {
        this.pixels.forEach(this.renderPixel);

        Array.from(this.symbols.keys())
            .filter(pixel => !this.pixels.includes(pixel))
            .forEach(this.removePixel);
    }
}
