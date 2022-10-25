import { Controller } from '@hotwired/stimulus';

/**
 * Renders a cool 3D effect on hovering the contact card.
 *
 * @see https://twitter.com/akella/status/1584473504975446016
 */
export default class extends Controller {
    connect() {
        this.init();

        const listener = this.createListener();
        this.element.addEventListener('mousemove', listener);
    }

    init() {
        const el = this.element;
        const width = el.clientWidth;
        const height = el.clientHeight;
        const bounding = el.getBoundingClientRect();

        this.apply(bounding.width / 2, bounding.height / 2, width, height);
    }

    createListener() {
        return (e) => {
            const el = this.element;
            const width = el.clientWidth;
            const height = el.clientHeight;
            const bounding = el.getBoundingClientRect();

            this.apply(e.clientX - bounding.left, e.clientY - bounding.top, width, height);
        };
    }

    apply(positionX, positionY, width, height) {
        const X = positionX / width;
        const Y = positionY / height;

        const factor = 26;
        const rX = -(X - 0.5) * factor;
        const rY = (Y - 0.5) * factor;

        const bgX = 40 + 20 * X;
        const bgY = 40 + 20 * Y;

        const centerX = width / 2;
        const centerY = height / 2;
        // Position from enter in %:
        const fromCenterX = Math.abs(1 - positionX / centerX);
        const fromCenterY = Math.abs(1 - positionY / centerY);

        document.documentElement.style.setProperty('--x', 100 * X + '%');
        document.documentElement.style.setProperty('--y', 100 * Y + '%');

        document.documentElement.style.setProperty('--dist-center', String((fromCenterX + fromCenterY) / 2));

        document.documentElement.style.setProperty('--bg-x', bgX + '%');
        document.documentElement.style.setProperty('--bg-y', bgY + '%');

        document.documentElement.style.setProperty('--r-x', rX + 'deg');
        document.documentElement.style.setProperty('--r-y', rY + 'deg');
    }

    disconnect() {
        document.documentElement.style.removeProperty('--x');
        document.documentElement.style.removeProperty('--y');
        document.documentElement.style.removeProperty('--dist-center');

        document.documentElement.style.removeProperty('--bg-x');
        document.documentElement.style.removeProperty('--bg-y');

        document.documentElement.style.removeProperty('--r-x');
        document.documentElement.style.removeProperty('--r-y');
    }
}
