export default class SocialPostGenerator {
    constructor(form, target) {
        this.form = form;
        this.target = target;
        this.content = target.querySelector('.content');

        this.loadTheme = this.loadTheme.bind(this);
        this.loadTemplate = this.loadTemplate.bind(this);
        this.loadFormat = this.loadFormat.bind(this);

        this.form.children.theme.addEventListener('change', this.loadTheme);
        this.form.children.template.addEventListener('change', this.loadTemplate);
        this.form.children.format.addEventListener('change', this.loadFormat);

        this.loadTheme();
        this.loadFormat();
        this.loadTemplate();
    }

    loadTheme() {
        this.target.dataset.theme = this.form.children.theme.value;
    }

    loadFormat() {
        this.target.dataset.format = this.form.children.format.value;
    }

    loadTemplate() {
        this.content.innerHTML = this.form.children.template.value;
    }
}
