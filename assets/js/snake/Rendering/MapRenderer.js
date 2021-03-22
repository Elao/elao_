export default class MapRenderer {
    constructor(map, border = 1) {
        this.map = map;
        this.element = document.createElementNS('http://www.w3.org/2000/svg', 'rect');

        this.element.setAttribute('fill', '#FF4344');
        this.element.setAttribute('stroke', '#FFFFFF');
        this.element.setAttribute('stroke-width', '0.9');
        this.element.setAttribute('stroke-linecap', 'square');
        this.element.setAttribute('x', -border);
        this.element.setAttribute('y', -border);
        this.element.setAttribute('width', this.map.size + 2 * border);
        this.element.setAttribute('height', this.map.size + 2 * border);
    }

    attach(container) {
        container.appendChild(this.element);
    }
}
