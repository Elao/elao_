import Engine from 'snake/core/Engine';

window.dispatchEvent(new CustomEvent('snake-ready', { detail: { Engine } }));
