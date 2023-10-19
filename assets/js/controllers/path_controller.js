import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        this.setupCharacterMovement();
    }

    setupCharacterMovement() {
        const sections = [
            {
                element: document.querySelector('.before-project__content'),
                character: document.querySelector('.before-project__content .character'),
                path: document.querySelector('.before-project__content .path'),
            },
            {
                element: document.querySelector('.build'),
                character: document.querySelector('.build .character'),
                path: document.querySelector('.build .path'),
            },
            {
                element: document.querySelector('.deployment'),
                character: document.querySelector('.deployment .character'),
                character2: document.querySelector('.deployment .character2'),
                path: document.querySelector('.deployment .path'),
                path2: document.querySelector('.deployment .path2'),
            },
        ];

        window.addEventListener('scroll', () => {
            const scrollHeight = window.scrollY;
            const viewportHeight = window.innerHeight;

            sections.forEach((section, index) => {
                const { element, character, character2, path, path2} = section;
                const sectionTop = element.offsetTop;
                const sectionBottom = sectionTop + element.clientHeight;

                if (scrollHeight + 0.5 * viewportHeight >= sectionTop && scrollHeight + 0.5 * viewportHeight <= sectionBottom) {
                    const sectionHeight = element.clientHeight;

                    if (element.classList.contains('deployment')) {
                        this.animateCharacterInSection3(character, character2, path, path2, sectionTop, sectionHeight, scrollHeight, viewportHeight);
                    } else {
                        this.animateCharacter(character, path, sectionTop, sectionHeight, scrollHeight, viewportHeight, index);
                    }
                } else {
                    this.hideCharacter(character, character2, sectionTop, scrollHeight, viewportHeight);
                }
            });
        });
    }

    animateCharacter(character, path, sectionTop, sectionHeight, scrollHeight, viewportHeight, index) {
        const characterHeight = character.clientHeight;
        const pathHeight = path.clientHeight + 100;
        let characterPosition = ((scrollHeight + 0.5 * viewportHeight - sectionTop) / sectionHeight) * (pathHeight - characterHeight);
        if (index === 0) {
            characterPosition -= 50;
        }
        if (index === 1) {
            characterPosition -= 100;
        }
        character.style.top = characterPosition + 'px';
        character.style.display = 'block';
    }

    animateCharacterInSection3(character, character2, path, path2, sectionTop, sectionHeight, scrollHeight, viewportHeight) {
        const characterHeight = character.clientHeight;
        const character2Height = character2.clientHeight;
        const pathWidth = path.clientWidth + 100;
        const path2Height = path2.clientHeight;

        if (scrollHeight + 0.5 * viewportHeight - sectionTop < sectionHeight / 2) {
            const characterPosition = pathWidth - ((scrollHeight + 0.5 * viewportHeight - sectionTop) / (sectionHeight / 2)) * (pathWidth - characterHeight);
            character.style.left = characterPosition - 240 + 'px';
            character2.style.top = '0';
            character.style.display = 'block';
            character2.style.display = 'none';
        } else {
            const characterPosition = ((scrollHeight + 0.5 * viewportHeight - sectionTop - (sectionHeight / 2)) / (sectionHeight / 2)) * (path2Height - character2Height);
            character2.style.top = characterPosition + 'px';
            character.style.left = '0';
            character.style.display = 'none';
            character2.style.display = 'block';
        }
    }

    hideCharacter(character, character2, sectionTop, scrollHeight, viewportHeight) {
        character.style.display = 'none';
        if (character2) {
            if (scrollHeight + 0.5 * viewportHeight >= sectionTop) {
                character2.style.display = 'block';
            } else {
                character2.style.display = 'none';
            }
        }
    }
}


