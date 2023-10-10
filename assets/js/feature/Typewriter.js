export default class Typewriter {
    text;
    speed;
    scrollAt;
    destination;
    index = 0;
    arrayLength = 0;
    textPosition = 0;
    contents = '';
    row = 0;

    constructor(text, speed, scrollAt, destinationId) {
        this.text = text;
        this.speed = speed;
        this.scrollAt = scrollAt;
        this.destination = document.getElementById(destinationId);
        this.arrayLength = this.text[0].length;
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

        if (this.textPosition++ === this.arrayLength) {
            this.textPosition = 0;
            this.index++;

            if (this.index !== this.text.length) {
                this.arrayLength = this.text[this.index].length;
                setTimeout(() => this.type(), 500);
            }
        } else {
            setTimeout(() => this.type(), this.speed);
        }
    }
}

