export default class Toggle {
    constructor(triggerSelector, rules) {
        this.triggers = document.querySelectorAll(triggerSelector);
        this.rules = rules;
        this.targets = {};
        this.active = false;

        this.toggle = this.toggle.bind(this);

        for (const selector in this.rules) {
            this.targets[selector] = document.querySelectorAll(selector);
        }

        this.triggers.forEach(trigger => trigger.addEventListener('click', this.toggle));

        this.update();
    }

    toggle() {
        this.setActive(!this.active);
    }

    setActive(active) {
        this.active = active;
        this.update();
    }

    update() {
        for (const selector in this.rules) {
            const classname = this.rules[selector];
            this.targets[selector].forEach(target => {
                target.classList.toggle(classname, this.active);
            });
        }
    }
}
