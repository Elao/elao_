import AnchorJS from 'anchor-js';

function init() {
    // Init Anchor JS
    const anchors = new AnchorJS();
    anchors.add();

    console.log('elao_ initialized, enjoy!');
}
window.addEventListener('load', init);
