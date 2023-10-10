import { Controller } from '@hotwired/stimulus';
import Typewriter from '../feature/Typewriter';

export default class extends Controller {
    static values = {
        text: Array,
        speed: { type: Number, default: 50 },
        scrollAt: { type: Number, default: 20 },
        destinationId: { type: String, default: 'typedtext' },
    };

    connect() {
        const typewriter = new Typewriter(
            this.textValue,
            this.speedValue,
            this.scrollAtValue,
            this.destinationIdValue,
        );

        typewriter.startTyping();
    }
}
