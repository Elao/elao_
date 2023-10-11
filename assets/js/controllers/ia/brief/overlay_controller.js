import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static values = {
        /** The selector of the element that will close the overlay on click */
        closeSelector: String,
    };

    connect() {
        const overlay = this.element;
        const closeOverlayButton = document.querySelector(this.closeSelectorValue);
        closeOverlayButton.addEventListener('click', () => overlay.style.display = 'none');
    }
}
