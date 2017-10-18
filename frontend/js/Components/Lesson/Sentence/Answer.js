'use strict';

class Answer {
    constructor($answer) {
        this.$answer = $answer;
        this.array = JSON.parse($answer.attr('data-json-answer'));
        this.nice = $answer.html().trim();
    }

    toggle() {
        this.$answer.toggleClass('hidden');
    }

    remove() {
        this.$answer.remove();
    }
}

export default Answer;