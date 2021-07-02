import FixedLoop from 'snake/core/FixedLoop';
import Loop from 'snake/core/Loop';
import SvgRenderer from 'snake/Rendering/SvgRenderer';
import Game from 'snake/Model/Game';
import Controls from 'snake/Model/Controls';

export default class Engine {
    constructor() {
        this.start = this.start.bind(this);
        this.update = this.update.bind(this);
        this.onInput = this.onInput.bind(this);
        this.stop = this.stop.bind(this);

        this.game = new Game();
        this.controls = new Controls(this.onInput);
        this.renderer = new SvgRenderer(this.game, this.stop);
        this.gameLoop = new FixedLoop(this.game.period, this.update);
        this.renderLoop = new Loop(this.renderer.update);
    }

    start() {
        this.game.reset();
        this.renderer.attach();
        this.renderLoop.start();
        this.controls.start();
        this.gameLoop.start();
    }

    stop() {
        this.renderLoop.stop();
        this.gameLoop.stop();
        this.controls.stop();
        this.renderer.detach();
        this.game.reset();
    }

    onInput(type, active) {
        if (active) {
            this.game.onInput(type);
        }
    }

    update() {
        this.game.update();
        this.renderer.onGameFrame();
    }
}
