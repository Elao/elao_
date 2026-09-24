import { Controller } from '@hotwired/stimulus';

/**
 * Bascule entre les modes de lecture d'un article au format entretien.
 *
 * Contrôleur dédié plutôt que `tabs_controller` ou `category-switch_controller` : ces
 * deux-là servent ailleurs, et leur accessibilité ne tient pas — `aria-selected` sur des
 * éléments sans rôle, `aria-hidden` au lieu de `hidden`.
 *
 * Le mode par défaut est déjà actif dans le HTML servi : le contrôleur ne fait que
 * réconcilier cet état avec l'adresse, puis suivre les interactions. Sans script, les deux
 * modes restent lisibles l'un après l'autre — c'est la règle
 * `html:not(.no-js) .reading-mode:not(.is-active)` qui masque le mode inactif, et elle ne
 * s'applique qu'une fois `no-js` retiré, en tête de `<head>`, avant le premier rendu.
 */
export default class extends Controller {
    static targets = ['mode', 'panel', 'control'];

    static values = {
        defaultMode: String,
    };

    connect() {
        // Les contrôles sont servis inertes : sans script, ils ne basculeraient rien.
        this.controlTargets.forEach(control => { control.disabled = false; });

        this._onAddressChange = () => this.follow();

        // `popstate` couvre le retour arrière ; `hashchange`, les liens internes vers un
        // mode inactif — le clic sur un tel lien ne déclenche pas `popstate`, et la cible
        // étant masquée, le navigateur n'a rien fait. Les deux peuvent se suivre sur une
        // même navigation : `follow()` est idempotent.
        window.addEventListener('popstate', this._onAddressChange);
        window.addEventListener('hashchange', this._onAddressChange);

        this.follow();
    }

    disconnect() {
        window.removeEventListener('popstate', this._onAddressChange);
        window.removeEventListener('hashchange', this._onAddressChange);
    }

    /**
     * Aligne le mode affiché sur l'adresse, et rejoint l'élément visé s'il y en a un :
     * l'ancre native a échoué, sa cible était masquée.
     */
    follow() {
        const panel = this.apply(this.resolve());
        const target = this.fragment && document.getElementById(this.fragment);

        if (target && panel?.contains(target)) {
            target.scrollIntoView();
        }
    }

    select(event) {
        const mode = event.currentTarget.dataset.readingMode;

        if (!mode || mode === this.current) {
            return;
        }

        // Un contrôle posé dans une section de mode est celui du rappel de fin : le
        // lecteur est en bas du document, il faut l'amener au début du mode qu'il vient
        // d'activer. Depuis le bloc d'annonce, la page ne doit pas bouger.
        const fromEndOfMode = null !== event.currentTarget.closest('.reading-mode');
        const panel = this.apply(mode);

        // Le focus annonce le mode activé, sans déplacer la page de lui-même : sur une
        // section plus haute que la fenêtre, le navigateur s'arrête où bon lui semble.
        panel?.focus({ preventScroll: true });

        if (fromEndOfMode) {
            panel?.scrollIntoView({ block: 'start' });
        }

        // Chaque bascule empile une entrée : le retour arrière ramène au mode précédent.
        window.history.pushState(
            { readingMode: mode },
            '',
            `${window.location.pathname}${window.location.search}#${mode}`,
        );
    }

    get fragment() {
        return decodeURIComponent(window.location.hash.slice(1));
    }

    /**
     * Mode désigné par l'adresse : son slug, ou le mode qui contient l'élément visé.
     * À défaut, le mode par défaut de l'article.
     *
     * Chercher le conteneur plutôt que reconnaître le préfixe que le processor pose sur
     * les identifiants de titres évite d'écrire cette convention une seconde fois, ici,
     * dans un autre langage — et fait aussi aboutir un lien vers n'importe quel autre
     * élément d'un mode.
     */
    resolve() {
        const fragment = this.fragment;

        if (!fragment) {
            return this.defaultModeValue;
        }

        const target = document.getElementById(fragment);
        const panel = this.panelTargets.find(
            element => element.dataset.readingMode === fragment || element.contains(target),
        );

        return panel?.dataset.readingMode ?? this.defaultModeValue;
    }

    /**
     * Active un mode et rend sa section, pour que l'appelant décide quoi en faire.
     *
     * @return {HTMLElement|undefined}
     */
    apply(mode) {
        this.current = mode;

        this.modeTargets.forEach(element => {
            element.classList.toggle('is-active', element.dataset.readingMode === mode);
        });

        this.controlTargets.forEach(control => {
            control.setAttribute('aria-pressed', String(control.dataset.readingMode === mode));
        });

        return this.panelTargets.find(element => element.dataset.readingMode === mode);
    }
}
