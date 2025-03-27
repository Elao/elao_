import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['slide'];
    static values = {
        slidesToShow: { type: Number, default: 1 },
        slidesToScroll: { type: Number, default: 1 },
        gap: { type: Number, default: 0 }
    };

    connect() {
        this.index = 0;
        this.totalSlides = this.slideTargets.length;
        this.setupSlides();
        this.createDots();
        this.showCurrentSlide();
        this.adjustForMobile();

        window.addEventListener('resize', this.onResize.bind(this));
    }

    adjustForMobile() {
        const isMobile = window.innerWidth <= 768;
        this.element.setAttribute('data-carousel-slides-to-show-value', isMobile ? '1' : this.slidesToShowValue);
        this.element.setAttribute('data-carousel-slides-to-scroll-value', isMobile ? '1' : this.slidesToScrollValue);
        this.setupSlides();
        this.showCurrentSlide();
    }

    onResize() {
        this.adjustForMobile();
    }

    setupSlides() {
        const containerWidth = this.element.offsetWidth;
        const track = this.element.querySelector('.carousel-track');
        const gap = this.gapValue;
        const slideWidth = (containerWidth - (this.slidesToShowValue - 1) * gap) / this.slidesToShowValue;

        if (!track) {
            return;
        }

        track.style.display = 'flex';
        track.style.gap = `${gap}px`;

        this.slideTargets.forEach(slide => slide.style.flex = `0 0 ${slideWidth}px`);
        this.showCurrentSlide();
        this.createDots();
    }

    showCurrentSlide() {
        const track = this.element.querySelector('.carousel-track');
        if (!track) return;

        const containerWidth = this.element.offsetWidth;
        const gap = this.gapValue;
        const slideWidth = (containerWidth - (this.slidesToShowValue - 1) * gap) / this.slidesToShowValue;
        const offset = -(this.index * (slideWidth + gap));

        track.style.transform = `translateX(${offset}px)`;
    }

    createDots() {
        if (this.dotsContainer) this.dotsContainer.remove();

        this.dotsContainer = document.createElement('div');
        this.dotsContainer.classList.add('carousel__dots');
        const totalDots = Math.ceil((this.totalSlides - this.slidesToShowValue) / this.slidesToScrollValue) + 1;

        for (let i = 0; i < totalDots; i++) {
            const dot = document.createElement('button');
            dot.classList.add('carousel__dot');
            dot.dataset.index = i * this.slidesToScrollValue;
            dot.addEventListener('click', () => this.goToSlide(i * this.slidesToScrollValue));
            this.dotsContainer.appendChild(dot);
        }

        this.element.appendChild(this.dotsContainer);
        this.updateDots();
    }

    updateDots() {
        this.dotsContainer.querySelectorAll('.carousel__dot').forEach((dot, i) => {
            dot.classList.toggle('active', i * this.slidesToScrollValue === this.index);
        });
    }

    goToSlide(index) {
        this.index = index;
        this.showCurrentSlide();
        this.updateDots();
    }
}
