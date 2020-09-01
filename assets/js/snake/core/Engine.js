import FixedLoop from 'snake/core/FixedLoop';
import Loop from 'snake/core/Loop';
import SvgRenderer from 'snake/Rendering/SvgRenderer';
import Game from 'snake/Model/Game';
import KeyboardControls from 'snake/control/KeyboardControls';
import TouchControls from 'snake/control/TouchControls';

export default class Engine {
    constructor() {
        this.start = this.start.bind(this);
        this.update = this.update.bind(this);
        this.onInput = this.onInput.bind(this);
        this.stop = this.stop.bind(this);

        this.game = new Game();
        this.keyboardControls = new KeyboardControls(this.onInput);
        this.touchControls = new TouchControls(this.onInput, this.game.snake);
        this.renderer = new SvgRenderer(this.game, this.stop, this.touchControls);
        this.gameLoop = new FixedLoop(this.game.period, this.update);
        this.renderLoop = new Loop(this.renderer.update);
    }

    start() {
        this.game.reset();
        this.renderer.attach();
        this.renderLoop.start();
        this.keyboardControls.start();
        this.touchControls.start();
        this.gameLoop.start();
    }

    stop() {
        this.renderLoop.stop();
        this.gameLoop.stop();
        this.keyboardControls.stop();
        this.touchControls.stop();
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
