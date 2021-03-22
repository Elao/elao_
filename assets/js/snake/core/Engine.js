import FixedLoop from 'snake/core/FixedLoop';
import Loop from 'snake/core/Loop';
import SvgRenderer from 'snake/Rendering/SvgRenderer';
import Listener from 'snake/Listener/LoadListener';
import Background from 'snake/Assets/Background';
import Logo from 'snake/Assets/Logo';
import Game from 'snake/Model/Game';
import Controls from 'snake/Model/Controls';

export default class Engine {
    constructor() {
        this.controls = new Controls(this.onInput.bind(this));
        this.game = new Game();
        this.renderer = new SvgRenderer(this.game);
        this.gameLoop = new FixedLoop(this.game.period, this.update.bind(this));
        this.renderLoop = new Loop(this.render.bind(this));
        this.listener = new Listener(this.start.bind(this));
    }

    start() {
        console.log('🐍');
        this.renderLoop.start();
        this.gameLoop.start();
        this.controls.start();
    }

    onInput(type, active) {
        if (active) {
            this.game.onInput(type);
        }
    }

    update() {
        this.game.update();
    }

    render(time) {
        this.renderer.update();
    }
}
