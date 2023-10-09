export default class Typewriter {
    constructor(text, speed, scrollAt, destinationId) {
        this.text = text;
        this.speed = speed;
        this.scrollAt = scrollAt;
        this.destination = document.getElementById(destinationId);
        this.index = 0;
        this.arrayLength = this.text[0].length;
        this.textPosition = 0;
        this.contents = '';
        this.row = 0;
    }

    startTyping() {
        this.type();
    }

    type() {
        this.contents = ' ';
        this.row = Math.max(0, this.index - this.scrollAt);

        while (this.row < this.index) {
            this.contents += this.text[this.row++] + '<br />';
        }

        this.destination.innerHTML =
            this.contents + this.text[this.index].substring(0, this.textPosition) + '_';

        if (this.textPosition++ == this.arrayLength) {
            this.textPosition = 0;
            this.index++;

            if (this.index != this.text.length) {
                this.arrayLength = this.text[this.index].length;
                setTimeout(() => this.type(), 500);
            }
        } else {
            setTimeout(() => this.type(), this.speed);
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {



});

