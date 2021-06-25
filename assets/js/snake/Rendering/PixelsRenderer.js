export default class PixelsRenderer {
    constructor(pixels) {
        this.pixels = pixels;
        this.element = document.createElementNS('http://www.w3.org/2000/svg', 'path');

        // Style
        this.element.setAttribute('fill', '#7f1A55');
        this.element.setAttribute('d', this.getPath());
    }

    attach(container) {
        container.appendChild(this.element);
    }

    getPath() {
        return this.pixels.reduce((path, pixel) => {
            return `${path} M${pixel.x - 0.5},${pixel.y - 0.5} m0.994719,0.501799 c0,0.291053 -0.225493,0.468204 -0.494719,0.468204 c-0.269226,0 -0.494719,-0.177151 -0.494719,-0.468204 c0,-0.292853 0.225632,-0.471803 0.494719,-0.471803 c0.269226,0.000139 0.494719,0.17895 0.494719,0.471803z m-0.276469,0c0,-0.151824 -0.069082,-0.222269 -0.21825,-0.222269 c-0.149168,0 -0.21825,0.070446 -0.21825,0.222269c0,0.148225 0.069082,0.218671 0.21825,0.218671 c0.149168,0 0.21825,-0.070446 0.21825,-0.218671z`;
        }, '');
    }

    update() {
        this.element.setAttribute('d', this.getPath());
    }
}

