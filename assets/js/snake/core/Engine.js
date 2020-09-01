import SvgRenderer from 'snake/Rendering/SvgRenderer';
import Listener from 'snake/Listener/ClickListener';
import Background from 'snake/Model/Background';
import Logo from 'snake/Model/Logo';

export default class Engine {
    constructor() {
        this.renderer = new SvgRenderer();
        this.listener = new Listener(this.start);

        this.background = new Background();
        this.logo = new Logo();

        this.renderer.setBackground(this.background);
        this.renderer.setLogo(this.logo);
    }

    start() {
        console.log('🐍');
    }
}
