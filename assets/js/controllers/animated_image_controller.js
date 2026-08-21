import { Controller } from '@hotwired/stimulus';

const LABEL_PAUSE = 'Mettre l’animation en pause';
const LABEL_PLAY = 'Lancer l’animation';
// Sans décodeur, on ne sait pas revenir sur l'image affichée : la commande arrête
// l'animation au lieu de la suspendre, et le dit.
const LABEL_STOP = 'Arrêter l’animation';

/**
 * Commande de lecture / pause pour les images animées du contenu (WCAG 2.2.2).
 *
 * Le format GIF n'expose aucune API de lecture, et `drawImage()` sur un `<img>`
 * animé ne rend pas l'image affichée : Chrome y recopie systématiquement la
 * première. Geler le visuel en l'état est donc impossible tant qu'on laisse le
 * navigateur jouer l'animation.
 *
 * On reprend donc la lecture à notre compte : le GIF est décodé image par image
 * via `ImageDecoder`, et rendu sur un canvas dont on pilote l'avancement. La
 * pause consiste alors simplement à ne pas programmer l'image suivante — ce qui
 * s'arrête est bien ce qui est affiché.
 *
 * `ImageDecoder` manque encore à certains navigateurs, Safari en tête, et n'existe
 * que dans un contexte sécurisé. On y conserve le `<img>` natif et la commande fige
 * la première image : le mouvement cesse, ce que le critère demande, mais sans
 * reprise au point d'arrêt. Le nom du bouton change en conséquence, pour ne pas
 * promettre une pause qui n'en est pas une.
 *
 * Le bouton est construit ici et non par `HtmlAnimatedImagesProcessor`, parce que
 * son existence même dépend du décodage : un GIF d'une seule image n'a rien à
 * contrôler, et le libellé n'est connu qu'une fois su ce que le navigateur permet.
 * Rendu au build, il aurait affirmé un contrôle avant de savoir s'il aura un
 * effet — et serait resté visible mais inerte si le script échouait à se charger.
 */
export default class extends Controller {
    static targets = ['image'];

    connect() {
        this.frames = null;
        this.index = 0;
        this.timer = null;
        this.playing = false;
        // Marque la boucle de lecture courante : le décodage étant asynchrone, une
        // boucle abandonnée peut reprendre après qu'une autre a été lancée.
        this.generation = 0;
        this.canvas = null;
        this.decoder = null;
        this.toggleButton = null;
        this.label = null;
        this.destroyed = false;
        this.reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        this.supported = typeof window.ImageDecoder !== 'undefined';

        if (!this.supported) {
            // Ici la capacité est connue d'emblée : la commande peut être posée tout
            // de suite, dans le seul état qu'elle pourra tenir.
            this._mountToggle();
            this._setState(true);

            if (this.reduced) {
                this._whenLoaded(() => this._freeze());
            }

            return;
        }

        // Le décodage n'a de sens que si l'image est susceptible d'être vue :
        // décoder d'emblée les images d'un article entier coûterait cher pour rien.
        this.observer = new IntersectionObserver(entries => {
            if (entries.some(e => e.isIntersecting)) {
                this.observer.disconnect();
                this.observer = null;
                this._takeOver();
            }
        }, { rootMargin: '200px' });

        this.observer.observe(this.element);
    }

    disconnect() {
        this.destroyed = true;
        this._stopTimer();
        this.observer?.disconnect();
        this.decoder?.close();
        this.decoder = null;
        this.canvas?.remove();
        this.canvas = null;
        this.toggleButton?.remove();
        this.toggleButton = null;
        this.label = null;
    }

    toggle() {
        if (!this.frames) {
            // Mode dégradé : on fige, ou on relâche le `<img>`.
            this.canvas ? this._unfreeze() : this._freeze();

            return;
        }

        this.playing ? this.pause() : this.play();
    }

    pause() {
        this._stopTimer();
        // Abandonne la boucle courante : sans quoi celle qui attend un décodage
        // reprendrait la main après la pause.
        ++this.generation;
        this._setState(false);
    }

    play() {
        if (!this.frames || this.playing) {
            return;
        }

        this._setState(true);
        this._advance(++this.generation);
    }

    // — Lecture pilotée ————————————————————————————————————————————————

    async _takeOver() {
        try {
            const response = await fetch(this.imageTarget.currentSrc, { cache: 'force-cache' });
            // eslint-disable-next-line no-undef -- WebCodecs, présence testée dans connect()
            const decoder = new ImageDecoder({
                data: await response.arrayBuffer(),
                type: 'image/gif',
            });

            // Les deux attentes sont nécessaires et distinctes : `tracks.ready`
            // renseigne la piste — sans elle `selectedTrack` reste indéfini — et
            // `completed` garantit que `frameCount` est définitif et non le compte
            // partiel des images déjà reçues.
            await decoder.tracks.ready;
            await decoder.completed;

            const track = decoder.tracks.selectedTrack;

            // Un GIF d'une seule image n'a rien à contrôler : on repart sans poser
            // de commande, plutôt que d'en afficher une sans effet.
            if (!track || track.frameCount < 2) {
                decoder.close();

                return;
            }

            if (this.destroyed) {
                decoder.close();

                return;
            }

            this.decoder = decoder;
            this.frames = track.frameCount;

            // Le canvas est rendu hors document, puis substitué à l'image une fois la
            // première image dessinée : posé avant, il resterait vide le temps du
            // décodage, à la place d'une image déjà visible.
            this._createCanvas();

            await this._render(0);

            if (this.destroyed) {
                this._abandonTakeOver();

                return;
            }

            this._showCanvas();
            this._mountToggle();

            if (this.reduced) {
                this._setState(false);
            } else {
                this.play();
            }
        } catch {
            // Une navigation Swup pendant le `fetch` fait rejeter la requête : rien à
            // remonter, le contrôleur est déjà démonté.
            if (this.destroyed) {
                return;
            }

            // Décodage impossible (réseau, format inattendu) : on défait ce qui a pu
            // être posé avant l'échec — sans quoi le bouton promettrait une pause
            // dont on n'est plus capable, par-dessus un canvas resté vide — puis le
            // `<img>` reprend la main et la commande retombe sur le gel.
            this._abandonTakeOver();

            this._mountToggle();
            this._setState(true);

            // Le mode dégradé doit honorer `prefers-reduced-motion` comme le fait
            // l'absence de décodeur : l'échec du décodage n'est pas une raison de
            // laisser tourner l'animation.
            if (this.reduced) {
                this._whenLoaded(() => this._freeze());
            }
        }
    }

    _abandonTakeOver() {
        this.decoder?.close();
        this.decoder = null;
        this.frames = null;
        this.canvas?.remove();
        this.canvas = null;
        this.imageTarget.hidden = false;
    }

    _createCanvas() {
        const image = this.imageTarget;
        const canvas = document.createElement('canvas');

        canvas.className = 'animated-image__frame';

        // Le canvas devient le rendu visible : il reprend l'alternative textuelle
        // de l'image qu'il remplace, sans quoi celle-ci disparaîtrait de l'arbre
        // d'accessibilité en même temps que le `<img>`.
        const alt = image.getAttribute('alt') ?? '';

        if (alt.trim()) {
            canvas.setAttribute('role', 'img');
            canvas.setAttribute('aria-label', alt);
        } else {
            canvas.setAttribute('aria-hidden', 'true');
        }

        this.canvas = canvas;
        // Surtout pas `this.context` : Stimulus s'en sert pour son propre contexte,
        // et l'écraser casse `this.element` — donc tout le contrôleur, en silence.
        this.ctx = canvas.getContext('2d');
    }

    _showCanvas() {
        this.imageTarget.after(this.canvas);
        this.imageTarget.hidden = true;
    }

    async _render(index) {
        if (!this.decoder || this.destroyed) {
            return 100;
        }

        // L'échec du décodage n'est pas rattrapé ici : `_takeOver` en a besoin pour
        // rendre la main au `<img>`, et `_advance` pour arrêter la boucle.
        const { image: frame } = await this.decoder.decode({ frameIndex: index });

        // Les dimensions viennent de l'image décodée et non du `<img>` : celui-ci peut
        // n'être pas encore chargé quand on prend la main, ses dimensions intrinsèques
        // valent alors 0, et le canvas serait invisible.
        if (this.canvas.width !== frame.displayWidth || this.canvas.height !== frame.displayHeight) {
            this.canvas.width = frame.displayWidth;
            this.canvas.height = frame.displayHeight;
        }

        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
        this.ctx.drawImage(frame, 0, 0);

        // `duration` est en microsecondes, et peut manquer. Les navigateurs
        // imposent un plancher de 20 ms aux GIF, on s'aligne dessus.
        const duration = frame.duration ? frame.duration / 1000 : 100;

        frame.close();

        return Math.max(duration, 20);
    }

    /**
     * Rend l'image courante puis programme la suivante. Le décodage étant
     * asynchrone, on revérifie l'état de lecture après l'attente : une pause
     * demandée entre-temps ne doit pas relancer la boucle. `generation` distingue
     * les boucles entre elles — sans quoi une pause puis une reprise pendant un
     * décodage en laisseraient deux tourner de front, avançant l'animation deux
     * fois plus vite et n'en programmant plus qu'une seule à couper.
     */
    async _advance(generation) {
        if (generation !== this.generation) {
            return;
        }

        let delay;

        try {
            delay = await this._render(this.index);
        } catch {
            // `close()` sur le décodeur — donc un démontage pendant l'attente — rejette
            // le décodage en cours. Rien à signaler, il n'y a plus rien à rendre. Et si
            // le décodage échoue pour une autre raison, la boucle s'arrête au lieu de
            // relancer indéfiniment un décodage voué à échouer.
            return;
        }

        if (this.destroyed || !this.playing || generation !== this.generation) {
            return;
        }

        this.timer = window.setTimeout(() => {
            this.timer = null;
            this.index = (this.index + 1) % this.frames;
            this._advance(generation);
        }, delay);
    }

    _stopTimer() {
        if (this.timer) {
            window.clearTimeout(this.timer);
            this.timer = null;
        }
    }

    // — Mode dégradé ———————————————————————————————————————————————————

    _freeze() {
        // L'attente du chargement de l'image survit au démontage du contrôleur : une
        // navigation Swup avant la fin du chargement ne doit pas geler une image qui
        // n'est plus dans le document.
        if (this.destroyed) {
            return;
        }

        const image = this.imageTarget;

        if (this.canvas || !image.naturalWidth) {
            return;
        }

        const canvas = document.createElement('canvas');

        canvas.width = image.naturalWidth;
        canvas.height = image.naturalHeight;
        canvas.className = 'animated-image__frame';
        canvas.setAttribute('aria-hidden', 'true');
        canvas.getContext('2d').drawImage(image, 0, 0);

        image.after(canvas);
        image.hidden = true;

        this.canvas = canvas;
        this._setState(false);
    }

    _unfreeze() {
        this.canvas?.remove();
        this.canvas = null;
        this.imageTarget.hidden = false;
        this._setState(true);
    }

    // — Commun ——————————————————————————————————————————————————————————

    /**
     * Le nom accessible change avec l'état plutôt que d'être fixe et doublé d'un
     * `aria-pressed` : les deux mécanismes ensemble se recouvrent, et « Lancer
     * l'animation » dit à lui seul ce que fera l'activation.
     *
     * Les deux pictogrammes sont posés ensemble et permutés par la feuille de style
     * selon la classe d'état, pour que l'état visuel ne soit décrit qu'à un endroit.
     */
    _mountToggle() {
        if (this.toggleButton) {
            return;
        }

        const button = document.createElement('button');

        button.type = 'button';
        button.className = 'animated-image__toggle';
        button.innerHTML = `
            <span class="animated-image__icons" aria-hidden="true">
                <svg class="animated-image__icon animated-image__icon--pause" viewBox="0 0 24 24" width="18" height="18" focusable="false" aria-hidden="true"><path fill="currentColor" d="M8 5h3v14H8zm5 0h3v14h-3z"/></svg>
                <svg class="animated-image__icon animated-image__icon--play" viewBox="0 0 24 24" width="18" height="18" focusable="false" aria-hidden="true"><path fill="currentColor" d="M8 5l11 7-11 7z"/></svg>
            </span>
            <span class="screen-reader"></span>
        `;

        button.addEventListener('click', () => this.toggle());

        this.element.appendChild(button);

        this.toggleButton = button;
        this.label = button.querySelector('.screen-reader');
    }

    _setState(playing) {
        this.playing = playing;
        this.element.classList.toggle('animated-image--paused', !playing);
        this._setLabel(playing ? (this.frames ? LABEL_PAUSE : LABEL_STOP) : LABEL_PLAY);
    }

    _setLabel(text) {
        if (this.label) {
            this.label.textContent = text;
        }
    }

    _whenLoaded(callback) {
        if (this.imageTarget.complete && this.imageTarget.naturalWidth) {
            callback();
        } else {
            this.imageTarget.addEventListener('load', callback, { once: true });
        }
    }
}
