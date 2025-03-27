import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['tab', 'item', 'category', 'content'];

    connect() {
    }

    changeTab(event) {
        this.tabTargets.forEach(tab => tab.classList.remove('active'));
        event.currentTarget.classList.add('active');

        const index = this.tabTargets.indexOf(event.currentTarget);
        this.itemTargets.forEach((item, i) => {
            item.classList.toggle('active', i === index);
        });

        this.activateFirstCategory(index);
    }

    changeCategory(event) {
        const activeItem = this.itemTargets.find(item => item.classList.contains('active'));
        const categories = activeItem.querySelectorAll('[data-tabs-target=\'category\']');
        const contents = activeItem.querySelectorAll('[data-tabs-target=\'content\']');

        categories.forEach(category => category.classList.remove('active'));
        event.currentTarget.classList.add('active');

        const index = Array.from(categories).indexOf(event.currentTarget);
        contents.forEach((content, i) => {
            content.classList.toggle('active', i === index);
        });
    }

    activateFirstCategory(tabIndex) {
        const activeItem = this.itemTargets[tabIndex];
        if (!activeItem) return;

        const categories = activeItem.querySelectorAll('[data-tabs-target=\'category\']');
        const contents = activeItem.querySelectorAll('[data-tabs-target=\'content\']');

        if (categories.length > 0) {
            categories.forEach(category => category.classList.remove('active'));
            categories[0].classList.add('active');
        }

        if (contents.length > 0) {
            contents.forEach(content => content.classList.remove('active'));
            contents[0].classList.add('active');
        }
    }
}
