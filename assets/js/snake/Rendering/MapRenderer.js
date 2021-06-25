export default class MapRenderer {
    constructor(size, border = 0.55) {
        this.size = size;
        this.element = document.createElementNS('http://www.w3.org/2000/svg', 'rect');

        this.element.setAttribute('fill', '#FFFFFF');
        this.element.setAttribute('stroke', 'none');
        //this.element.setAttribute('stroke', '#FFFFFF');
        //this.element.setAttribute('stroke-width', '0.9');
        //this.element.setAttribute('stroke-linecap', 'square');
        this.element.setAttribute('x', -border);
        this.element.setAttribute('y', -border);
        this.element.setAttribute('width', this.size + 2 * border);
        this.element.setAttribute('height', this.size + 2 * border);
    }

    attach(container) {
        container.appendChild(this.element);
    }
}
