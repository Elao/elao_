import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['slide'];

    connect() {
        this.index = 0;
        this.createDots();
        this.showCurrentSlide();
    }

    next() {
        this.index = (this.index + 1) % this.slideTargets.length;
        this.showCurrentSlide();
    }

    prev() {
        this.index = (this.index - 1 + this.slideTargets.length) % this.slideTargets.length;
        this.showCurrentSlide();
    }

    showCurrentSlide() {
        this.slideTargets.forEach(slide => {
            slide.style.transform = `translateX(${-100 * this.index}%)`;
        });
        this.updateDots();
    }

    createDots() {
        this.dotsContainer = document.createElement('div');
        this.dotsContainer.classList.add('carousel__dots');

        this.slideTargets.forEach((_, i) => {
            const dot = document.createElement('button');
            dot.classList.add('carousel__dot');
            dot.dataset.index = i;
            dot.addEventListener('click', () => this.goToSlide(i));
            this.dotsContainer.appendChild(dot);
        });

        this.element.appendChild(this.dotsContainer);
        this.updateDots();
    }

    updateDots() {
        this.dotsContainer.querySelectorAll('.carousel__dot').forEach((dot, i) => {
            dot.classList.toggle('active', i === this.index);
        });
    }

    goToSlide(index) {
        this.index = index;
        this.showCurrentSlide();
    }
}
