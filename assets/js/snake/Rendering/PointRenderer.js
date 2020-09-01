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

    static createSymbol(x, y, color) {
        const element = document.createElementNS('http://www.w3.org/2000/svg', 'path');

        element.setAttribute('fill', color);
        element.setAttribute('d', `M${x-0.5},${y-0.5} m0.92148,0.50153c0,0.24796 -0.19211,0.39889 -0.42148,0.39889c-0.22937,0 -0.42148,-0.15092 -0.42148,-0.39889c0,-0.2495 0.19223,-0.40196 0.42148,-0.40196c0.22937,0.00012 0.42148,0.15246 0.42148,0.40196zm-0.23554,0c0,-0.12934 -0.05885,-0.18937 -0.18594,-0.18937c-0.12709,0 -0.18594,0.06002 -0.18594,0.18937c0,0.12628 0.05885,0.1863 0.18594,0.1863c0.12709,0 0.18594,-0.06002 0.18594,-0.1863z`);
        element.setAttribute('class', `point color-${color}`);
        element.style.transformOrigin = `${x}px ${y}px`;

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
