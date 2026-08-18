import { Controller } from '@hotwired/stimulus';

const LABEL_PAUSE = 'Mettre l’animation en pause';
const LABEL_PLAY = 'Lancer l’animation';

/**
 * Commande de lecture / pause pour les images animées du contenu (WCAG 2.2.2).
 *
 * Le format GIF n'expose aucune API de lecture : on ne peut ni l'arrêter ni lire
 * sa position. La seule prise possible est de recopier l'image telle qu'elle est
 * affichée sur un canvas, puis de masquer le GIF derrière — visuellement, plus
 * rien ne bouge, ce que le critère demande.
 *
 * Conséquence assumée : la reprise repart de la première image. Le GIF est retiré
 * du rendu pendant la pause, le navigateur est donc libre de réinitialiser sa
 * boucle. Pour un contenu illustratif, mieux vaut ça qu'un décodage qui continue
 * en arrière-plan.
 */
export default class extends Controller {
    static targets = ['image', 'toggle', 'label'];

    connect() {
        this.canvas = null;

        // Une préférence système pour le mouvement réduit vaut demande explicite :
        // on démarre en pause plutôt que d'attendre un clic. La commande reste là
        // pour lancer l'animation à la demande.
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            this._whenReady(() => this.pause());
        }
    }

    disconnect() {
        this._removeCanvas();
    }

    toggle() {
        if (this.canvas) {
            this.play();
        } else {
            this.pause();
        }
    }

    pause() {
        const image = this.imageTarget;

        if (this.canvas || !image.naturalWidth) {
            return;
        }

        const canvas = document.createElement('canvas');
        canvas.width = image.naturalWidth;
        canvas.height = image.naturalHeight;
        canvas.className = 'animated-image__frame';
        // L'image porte déjà l'alternative textuelle : la dupliquer sur le canvas
        // la ferait annoncer deux fois pendant la pause.
        canvas.setAttribute('aria-hidden', 'true');
        canvas.getContext('2d').drawImage(image, 0, 0);

        image.after(canvas);
        image.hidden = true;

        this.canvas = canvas;
        this._setState(false);
    }

    play() {
        this._removeCanvas();
        this.imageTarget.hidden = false;
        this._setState(true);
    }

    _removeCanvas() {
        if (this.canvas) {
            this.canvas.remove();
            this.canvas = null;
        }
    }

    _setState(playing) {
        this.element.classList.toggle('animated-image--paused', !playing);

        if (this.hasLabelTarget) {
            this.labelTarget.textContent = playing ? LABEL_PAUSE : LABEL_PLAY;
        }
    }

    /**
     * `naturalWidth` vaut 0 tant que l'image n'est pas décodée : sans cette
     * attente, une mise en pause au chargement produirait un canvas vide.
     */
    _whenReady(callback) {
        if (this.imageTarget.complete && this.imageTarget.naturalWidth) {
            callback();
        } else {
            this.imageTarget.addEventListener('load', callback, { once: true });
        }
    }
}
