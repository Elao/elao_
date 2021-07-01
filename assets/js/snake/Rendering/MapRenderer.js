export default class MapRenderer {
    constructor(size, border = 0.55) {
        this.size = size;
        this.border = border;
        this.element = document.createElementNS('http://www.w3.org/2000/svg', 'rect');

        this.element.setAttribute('fill', '#FFFFFF');
        this.element.setAttribute('stroke', 'none');
        this.element.setAttribute('x', -this.border);
        this.element.setAttribute('y', -this.border);
        this.element.setAttribute('width', this.size + 2 * this.border);
        this.element.setAttribute('height', this.size + 2 * this.border);
    }

    attach(container) {
        container.appendChild(this.element);
    }
}
