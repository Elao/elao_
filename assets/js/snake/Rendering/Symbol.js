export default class Symbol {
    constructor(x, y, value, color = '#7f1A55', size = '0.05em') {
        this.element = document.createElementNS('http://www.w3.org/2000/svg', 'path');
        //this.element.innerHTML = value;
        this.centerX = x;
        this.centerY = y;

        // Style
        //this.element.setAttribute('font-size', size);
        //this.element.setAttribute('font-family', 'faktum bold');
        this.element.setAttribute('fill', color);
        this.element.setAttribute('x', x);
        this.element.setAttribute('y', y);
        this.element.setAttribute('class', 'crash__symbol');
        this.element.setAttribute('d', '');
    }

    attach(container) {
        container.appendChild(this.element);
    }
}
