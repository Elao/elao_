export default class Background {
    constructor() {
        this.width = 1;
        this.height = 1;
        this.element = document.createElementNS('http://www.w3.org/2000/svg', 'rect');

        this.element.setAttribute('class', 'background');
    }
}
