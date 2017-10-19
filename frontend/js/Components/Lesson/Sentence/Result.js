'use strict';

class Result {
    constructor($result) {
        this.$result = $result;
        this.isShown = false;
    }

    hide() {
        if (this.isShown === true) {
            this.isShown = false;
            this.$result.addClass('hidden');
        }
    }

    show() {
        if (this.isShown === false) {
            this.isShown = true;
            this.$result.removeClass('hidden');
        }
    }

    highlightWrongWord(niceAnswer, wordIndex) {
        const answer = niceAnswer.replace(/' - '/g, '').split(' ');

        for (let i = 0; i < answer.length; i++) {
            if (i === wordIndex) {
                answer[i] = '<span class="text-danger">'+answer[i]+'</span>';
            } else if (i > wordIndex) {
                answer[i] = '*'.repeat(4);
            }
        }

        this.$result.html(answer.join(' '));
        this.show();
    }

    remove() {
        this.$result.remove();
    }
}

export default Result;
