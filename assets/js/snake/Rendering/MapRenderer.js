export default class MapRenderer {
    constructor(map) {
        this.map = map;
        this.element = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
        this.border = document.createElementNS('http://www.w3.org/2000/svg', 'rect');

        this.element.setAttribute('fill', '#FF4344');
        this.element.setAttribute('stroke', '#FF4344');
        this.element.setAttribute('stroke-width', '1');
        this.element.setAttribute('stroke-linecap', 'square');
        this.element.setAttribute('x', 0);
        this.element.setAttribute('y', 0);
        this.element.setAttribute('width', this.map.size);
        this.element.setAttribute('height', this.map.size);

        this.border.setAttribute('fill', '#fff');
        this.border.setAttribute('x', -1);
        this.border.setAttribute('y', -1);
        this.border.setAttribute('width', this.map.size + 2);
        this.border.setAttribute('height', this.map.size + 2);
    }

    attach(container) {
        container.appendChild(this.border);
        container.appendChild(this.element);
    }
}
