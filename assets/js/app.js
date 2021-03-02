// start the Stimulus application
import './bootstrap';

import Toggle from './feature/Toggle';

function init() {
    // Mobile navigation
    new Toggle('.nav-toggle', { '.nav-mobile': 'nav-mobile--open', 'body': 'no-scroll' });
}

window.addEventListener('load', init);
