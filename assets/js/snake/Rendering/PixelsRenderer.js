export default class PixelsRenderer {
    constructor(pixels) {
        this.pixels = pixels;
        this.element = document.createElementNS('http://www.w3.org/2000/svg', 'path');

        // Style
        this.element.setAttribute('fill', 'none');
        this.element.setAttribute('stroke', '#ffffff');
        this.element.setAttribute('stroke-width', '0.9');
        this.element.setAttribute('stroke-linecap', 'square');
        this.element.setAttribute('d', this.getPath());
    }

    attach(container) {
        container.appendChild(this.element);
    }

    getPath() {
        return this.pixels.reduce((path, pixel) => {
            return `${path} M${pixel.x},${pixel.y} l0,0`;
        }, '');
    }

    update() {
        this.element.setAttribute('d', this.getPath());
    }
}
