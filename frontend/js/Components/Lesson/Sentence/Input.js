class Input {
    constructor(lesson, sentence, $input) {
        this.lesson = lesson;
        this.sentence = sentence;
        this.$input = $input;

        this.$input.on('focus', () => {
            this.onFocusHandler();
        });

        this.$input.on('keydown', (e) => {
            this.onKeydownHandler(e);
        })
    }

    getVal() {
        return this.$input.val();
    }

    setVal(value) {
        this.$input.val(value);
    }

    focus() {
        this.$input.focus();
    }

    toArray() {
        return this.$input
            .val()
            .toLowerCase()
            .replace(/[^\w\s-]*/g, '')
            .replace(/ - /g, ' ')
            .split(' ');
    }

    reformat() {
        this.setVal(
            this.getVal().trim().replace(/\s\s+/g, ' ')
        );
    }

    selectWrongWord(wordIndex) {
        let inputVal = this.getVal() + ' ';

        let start = 0;
        let end = 0;
        for (let i = 0; i <= wordIndex; i++) {
            const newEnd = inputVal.indexOf(' ', end+1);
            start = end;

            if (newEnd > end) {
                end = newEnd;
            }
        }

        start += wordIndex === 0 ? 0 : 1;

        if (start > end) {
            this.$input.val(inputVal);
        } else {
            this.$input[0].setSelectionRange(start, end);
        }
    }

    onKeydownHandler(e) {
        const keyCode = e.keyCode;

        if (!this.sentence.isDone) {
            if (e.ctrlKey && (keyCode === 10 || keyCode === 13)) { // Ctrl + Enter
                this.sentence.answer.toggle();
            }

            else if (keyCode === 13) { // Enter
                this.sentence.checkUserInput();
            }
        }

        if (e.ctrlKey && keyCode === 32) { // Ctrl + Space
            this.sentence.audio.play();
        }

        else if (keyCode === 32 && this.sentence.result.isShown) {
            this.sentence.result.hide();
        }

        else if (keyCode === 40) { // Down
            this.sentence.focusToNext();
        }

        else if (keyCode === 38) { // Up
            this.sentence.focusToPrevious();
        }
    }

    onFocusHandler() {
        const distant = this.$input.position().top;

        if (distant > 340) {
            this.sentence.scrollSentence(distant);
        }
    }
}

export default Input;
