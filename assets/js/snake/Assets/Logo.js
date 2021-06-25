export default class Logo {
    static get paths() {
        return [
            'm65.04,65.04l-44.27,0c1.31,7.84 5.35,11.23 13.58,11.23c6.27,0 9.27,-1.96 12.41,-8.23l16.98,7.31c-4.44,11.23 -14.24,17.37 -29.91,17.37c-20.24,0 -33.83,-13.32 -33.83,-33.43c0,-20.63 13.32,-34.48 33.17,-34.48c19.2,0 32,13.32 32,33.43c0,2.23 0,3.93 -0.13,6.8zm-44.4,-12.93l24.94,0c-0.26,-8.36 -4.31,-11.49 -12.02,-11.49c-7.83,0 -11.75,3.52 -12.92,11.49z',
            'm75.23,0l19.85,0l0,91.42l-19.85,0l0,-91.42z',
            'm175.53,26.12l0,65.3l-19.85,0l0,-14.63c-3.66,10.32 -11.1,15.93 -21.55,15.93c-18.68,0 -28.99,-13.58 -28.99,-33.83c0,-20.5 9.79,-34.09 27.69,-34.09c11.1,0 19.07,5.75 22.85,16.46l0,-15.14l19.85,0zm-19.2,32.78c0,-10.97 -5.09,-16.06 -15.93,-16.06c-10.45,0 -15.41,5.09 -15.41,16.06c0,10.71 4.96,15.8 15.41,15.8c10.84,0 15.93,-5.09 15.93,-15.8z',
            'm256.63,58.9c0,21.03 -16.19,33.83 -35.52,33.83c-19.33,0 -35.52,-12.8 -35.52,-33.83c0,-21.16 16.2,-34.09 35.52,-34.09c19.33,0.01 35.52,12.93 35.52,34.09zm-19.85,0c0,-10.97 -4.96,-16.06 -15.67,-16.06c-10.71,0 -15.67,5.09 -15.67,16.06c0,10.71 4.96,15.8 15.67,15.8c10.71,0 15.67,-5.09 15.67,-15.8z',
        ];
    }

    static createPath(content) {
        const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');

        path.setAttribute('d', content);
        path.setAttribute('fill', 'white');

        return path;
    }

    static createRect(x, y, width, height) {
        const element = document.createElementNS('http://www.w3.org/2000/svg', 'rect');

        // Style
        element.setAttribute('x', x);
        element.setAttribute('y', y);
        element.setAttribute('width', width);
        element.setAttribute('height', height);
        element.setAttribute('fill', '#ffffff');

        return element;
    }

    constructor() {
        this.element = document.createElementNS('http://www.w3.org/2000/svg', 'svg');

        this.element.setAttribute('viewBox', '0 0 300 100');
        this.element.setAttribute('width', 2);

        const { createPath, createRect, paths } = this.constructor;

        paths.forEach(content => this.element.appendChild(createPath(content)));

        this.element.appendChild(createRect(256.98, 91.44, 59.96, 15.85));
    }

    attach(container, size) {
        this.element.setAttribute('x', -0.5);
        this.element.setAttribute('y', 4 - size);

        container.appendChild(this.element);
    }
}

