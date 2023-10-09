import { Controller } from '@hotwired/stimulus';
import Typewriter from '../feature/Typewriter';

export default class extends Controller {
    static values = {
        text: Array,
    };
    connect() {
        const speed = 50;
        const scrollAt = 20;
        const destinationId = 'typedtext';
        const typewriter = new Typewriter(this.textValue, speed, scrollAt, destinationId);
        typewriter.startTyping();
    }
}
