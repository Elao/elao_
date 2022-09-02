import '../scss/style.scss';

// start the Stimulus application
import './bootstrap';
import AOS from 'aos';

// import Toggle from './feature/Toggle';
import Launcher from './feature/Launcher';
import SocialPostGenerator from './feature/SocialPostGenerator';

function init() {
    // Mobile navigation
    // new Toggle('.nav-toggle', { '.nav-mobile': 'nav-mobile--open', 'body': 'no-scroll' });

    // S.E.E
    try {
        new Launcher(
            JSON.parse(document.head.querySelector('meta[name="see"]').content),
            JSON.parse(document.head.querySelector('meta[name="see_style"]').content),
            document.getElementById('see')
        );
    } catch {
        // Fail silently.
    }
}

// Social Post Generator
window.SocialPostGenerator = SocialPostGenerator;

window.addEventListener('load', init);

// https://github.com/michalsnik/aos#1-initialize-aos
AOS.init({
    disable: () => matchMedia('(max-width: 760px)').matches,
    useClassNames: true, // if true, will add content of `data-aos` as classes on scroll
    // Settings that can be overridden on per-element basis, by `data-aos-*` attributes:
    offset: 50, // offset (in px) from the original trigger point
    delay: 0, // values from 0 to 3000, with step 50ms
    duration: 400, // values from 0 to 3000, with step 50ms
    easing: 'ease', // default easing for AOS animations
    once: true, // whether animation should happen only once - while scrolling down
    mirror: false, // whether elements should animate out while scrolling past them
    anchorPlacement: 'top-bottom', // defines which position of the element regarding to window should trigger the animation
});
