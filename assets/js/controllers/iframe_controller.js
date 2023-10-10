import { Controller } from '@hotwired/stimulus';
export default class extends Controller {
    connect() {
        this.urlIframe();
    }

    urlIframe() {
        const config = {
            'expertize': {
                assistant: '1be9f212-3b72-44eb-8d15-fbda3015bd0a',
                task: '5178444f-806e-4030-b926-283ad193a11b'
            },
            'optimize': {
                assistant: 'e638c269-e7ad-4180-82df-6e67e86bf0f3',
                task: 'd67ba31e-8e55-4ce7-975d-8071c22625f7'
            },
            'accelerate': {
                assistant: '716d0aac-4d90-4090-919a-c05f0d485247',
                task: '5ca706fc-f057-462e-b91a-f1b2ff25b04d'
            },
            'default': {
                assistant: '76dbad6a-0088-48d0-923c-f100ee2fd412',
                task: '75089fc3-1bfc-4a98-b3a8-6368ea5d836a'
            }
        };

        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const param = urlParams.get('brief');

        const { assistant, task } = config[param] || config['default'];

        const iframe = `?workspace=83658863-c7ff-47c4-aee3-979800a53584&role=57c2a515-bd3a-4a54-b3a4-449d08b286ca&assistant=${assistant}&task=${task}`;

        this.element.setAttribute('src', `http://elao.amabla.com/${iframe}`);

        let overlay = document.querySelector('.overlay');
        const closeOverlayButton = document.querySelector('#closeOverlay');
        closeOverlayButton.addEventListener('click', () => {
            overlay.style.display = 'none';
        });
    }
}
