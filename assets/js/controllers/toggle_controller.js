import { Controller } from '@hotwired/stimulus';
import Toggle from '../feature/Toggle';

export default class extends Controller {
    connect() {
        new Toggle('.nav-toggle', { '.nav-mobile': 'nav-mobile--open', 'body': 'no-scroll' });
    }
}
