'use strict';

class Result {
    constructor($result) {
        this.$result = $result;
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

        this.$result
            .html(answer.join(' '));
    }

    remove() {
        this.$result.remove();
    }
}

export default Result;