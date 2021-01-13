export default class Logo {
    static get paths() {
        return [
            "M61.6,61.6H19.7C21,69,24.8,72.2,32.6,72.2c5.9,0,8.8-1.9,11.7-7.8l16.1,6.9C56.2,82,46.9,87.8,32.1,87.8 c-19.1,0-32-12.6-32-31.6c0-19.5,12.6-32.6,31.4-32.6c18.1,0,30.3,12.6,30.3,31.6C61.7,57.2,61.7,58.9,61.6,61.6z M19.6,49.3h23.6 c-0.2-7.9-4.1-10.9-11.3-10.9S20.7,41.8,19.6,49.3z",
            "M71.2,0.1H90v86.5H71.2V0.1z",
            "M166.1,24.7v61.8h-18.8V72.7c-3.5,9.8-10.5,15.1-20.4,15.1c-17.7,0-27.4-12.9-27.4-32 c0-19.4,9.3-32.2,26.2-32.2c10.5,0,18,5.4,21.6,15.6V24.7H166.1z M147.9,55.7c0-10.4-4.8-15.2-15.1-15.2c-9.9,0-14.6,4.8-14.6,15.2 c0,10.1,4.7,14.9,14.6,14.9C143.1,70.7,147.9,65.9,147.9,55.7L147.9,55.7z",
            "M242.8,55.8c0,19.9-15.3,32-33.6,32s-33.6-12.1-33.6-32s15.3-32.2,33.6-32.2S242.8,35.8,242.8,55.8z M224,55.8 c0-10.4-4.7-15.2-14.8-15.2c-10.1,0-14.8,4.8-14.8,15.2c0,10.1,4.7,14.9,14.8,14.9S224,65.9,224,55.8L224,55.8z",
        ];
    }

    static createPath(content) {
        const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');

        path.setAttribute('d', content);
        path.setAttribute('fill', 'white');

        return path;
    }

    constructor() {
        this.element = document.createElementNS('http://www.w3.org/2000/svg', 'svg');

        this.element.setAttribute('viewBox', '0 0 300 100');

        const { createPath, paths } = this.constructor;

        paths.forEach(content => this.element.appendChild(createPath(content)));
    }
}

