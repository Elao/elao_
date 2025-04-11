import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['category', 'item'];

    connect() {
        this.showItem(0); // Afficher le premier élément par défaut
    }

    select(event) {
        const index = this.categoryTargets.indexOf(event.currentTarget);
        this.showItem(index);
    }

    showItem(index) {
        this.itemTargets.forEach((item, i) => {
            item.classList.toggle('active', i === index);
            item.setAttribute('aria-hidden', i !== index);
        });

        this.categoryTargets.forEach((category, i) => {
            category.classList.toggle('active', i === index);
            category.setAttribute('aria-selected', i === index);
        });
    }
}
