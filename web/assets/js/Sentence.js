class Sentence {
    constructor($wrapper, lesson, $sentence, position) {
        this.$wrapper   = $wrapper;
        this.lesson     = lesson;
        this.$sentence  = $sentence;
        this.position   = position;
        this.audio      = $sentence.find('.js-audio');
        this.$audioBtn  = $sentence.find('.js-audio-btn');
        this.$input     = $sentence.find('.js-input');
        this.wasSaved   = $sentence.hasClass('done');
        this.isDone     = this.wasSaved;

        this.initEvents();

        /* lazy-load getters for these
        this.id         = null;
        this.$checkBtn  = null;
        this.$answer    = null;
        this.niceAnswer = null;
        this.answer     = null;
        this.$result    = null;
        */
    }

    getResultEl() {
        if (undefined === this.$result) {
            this.$result = this.$sentence.find('.js-result');
        }

        return this.$result;
    }

    getCheckBtn() {
        if (undefined === this.$checkBtn) {
            this.$checkBtn = this.$sentence.find('.js-check-answer-btn');
        }

        return this.$checkBtn;
    }

    getId() {
        if (undefined === this.id) {
            this.id = this.$sentence.data('id');
        }

        return this.id;
    }

    getAnswerEl() {
        if (undefined === this.$answer) {
            this.$answer = this.$sentence.find('.js-answer');
        }

        return this.$answer;
    }

    getNiceAnswer() {
        if (undefined === this.niceAnswer) {
            this.niceAnswer = this.getAnswerEl().html();
        }

        return this.niceAnswer;
    }

    getAnswerArr() {
        if (undefined === this.answer) {
            this.answer = JSON.parse(this.$sentence.attr('data-json-answer'));
        }

        return this.answer;
    }

    hideAnswer() {
        if (!this.getAnswerEl().hasClass('hidden')) {
            this.getAnswerEl().addClass('hidden');
        }
    }

    checkUserInput() {
        const t0 = performance.now();
        this.reformatUserInput();
        this.hideAnswer();

        const result = this.isUserInputCorrect();
        if (result === true) {
            this.inputCorrectHandler();
        } else {
            this.inputIncorrectHandler(result);
        }

        const t1 = performance.now();
        console.log("Call to checkInput took " + (t1 - t0) + " milliseconds.")
    }

    inputIncorrectHandler(result) {
        this.selectWrongWord(result.inputWrongWordPos);
        this.highlightWrongWord(result.answerWrongWordPos);
    }

    selectWrongWord(wordIndex) {
        const $input = this.$input;
        let inputVal = $input.val() + ' ';

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

        $input[0].setSelectionRange(start, end)

    }

    highlightWrongWord(wordIndex) {
        const answer = this.getNiceAnswer()
                           .replace(/' - '/g, '')
                           .split(' ');

        for (let i = 0; i < answer.length; i++) {
            if (i === wordIndex) {
                answer[i] = '<span class="text-danger">'+answer[i]+'</span>';
            } else if (i > wordIndex) {
                answer[i] = '*'.repeat(4);
            }
        }

        this.getResultEl()
            .html(answer.join(' '));
    }

    inputCorrectHandler() {
        this.$input
            .val(this.getNiceAnswer());

        this.$sentence
            .addClass('done');

        this.getCheckBtn()
            .children()
            .removeClass('glyphicon-send')
            .addClass('glyphicon-ok');

        this.getResultEl()
            .remove();

        this.getAnswerEl()
            .remove();

        delete(this.getAnswerArr());
        delete(this.getNiceAnswer());

        this.isDone = true;
        if (this.lesson.user) {
            this.lesson.user.completeSentence(this.getId());
        }

    }

    reformatUserInput() {
        this.$input.val(
            this.$input.val().trim().replace(/\s\s+/g, ' ')
        );
    }

    getUserInputArray() {
        return this.$input
            .val()
            .toLowerCase()
            .replace(/[^\w\s-]*/g, '')
            .replace(/ - /g, ' ')
            .split(' ');
    }

    isUserInputCorrect() {
        const inputArr = this.getUserInputArray();
        const answerArr = this.getAnswerArr();

        let inputWrongWordPos = -1;
        let answerWrongWordPos = -1;
        let inputIndex = -1;

        for (let i = 0; i < answerArr.length; i++) {
            if (inputWrongWordPos > -1) {
                break;
            } else {
                inputIndex++;
            }

            if (typeof answerArr[i] === 'string') {
                if (answerArr[i] !== inputArr[inputIndex]) {
                    answerWrongWordPos = i;
                    inputWrongWordPos = inputIndex;
                }
            }

            else if (Array.isArray(answerArr[i])) {
                let isCorrect = false;
                let correctWordCount = 0;
                let lastCorrectWordPos = 0;

                for (let j = 0; j < answerArr[i].length; j++) {
                    if (isCorrect) {
                        break;
                    }

                    if (typeof answerArr[i][j] === 'string') {
                        if (answerArr[i][j] === inputArr[inputIndex]) {
                            isCorrect = true;
                        }
                    }

                    else if (Array.isArray(answerArr[i][j])) {
                        for (let k = 0; k < answerArr[i][j].length; k++) {
                            if (answerArr[i][j][k] === inputArr[inputIndex+k]) {
                                correctWordCount++;
                            } else {
                                if (correctWordCount > lastCorrectWordPos) {
                                    lastCorrectWordPos = correctWordCount;
                                }
                                correctWordCount = 0;
                                break;
                            }
                        }

                        if (correctWordCount === answerArr[i][j].length) {
                            isCorrect = true;
                            inputIndex += correctWordCount - 1;
                        }
                    }
                }

                if (!isCorrect) {
                    inputWrongWordPos = inputIndex + lastCorrectWordPos;
                    answerWrongWordPos = i;
                }
            }
        }

        if (inputWrongWordPos === -1) {
            return true;
        }

        return {
            'inputWrongWordPos': inputWrongWordPos,
            'answerWrongWordPos': answerWrongWordPos
        };
    };

    showAnswer() {
        this.getAnswerEl().toggleClass('hidden');
    }

    playAudio() {
        const audio = this.audio[0];

        audio.currentTime = 0;
        audio.paused ? audio.play() : audio.pause();
    }

    inputOnFocusHandler() {
        const wrapper = this.$wrapper;
        const distance = this.$input.position().top;
        const scrollTop = wrapper.scrollTop();

        if (distance > 340) {
            wrapper.animate({
                scrollTop: scrollTop + 300
            }, 200);
        }
    }

    inputOnKeydownHandler(e) {
        const keyCode = e.keyCode;

        if (!this.isDone) {
            if (e.ctrlKey) {
                if (keyCode === 32) { // Ctrl + Space
                    this.playAudio();
                }

                else if (keyCode === 10 || keyCode === 13) { // Ctrl + Enter
                    this.showAnswer();
                }
            }

            else if (keyCode === 13) { // Enter
                this.checkUserInput();
            }
        }

        if (keyCode === 40) { // Down
            this.lesson.focusToSentence(this.position + 1);
        }

        else if (keyCode === 38) { // Up
            this.lesson.focusToSentence(this.position - 1);
        }
    }

    initEvents() {
        this.$audioBtn.on('click', () => {
            this.playAudio();
        });

        this.$input.on('focus', () => {
            this.inputOnFocusHandler();
        });

        this.$input.on('keydown', (e) => {
            this.inputOnKeydownHandler(e);
        });

        if (!this.wasSaved) {
            this.getCheckBtn().on('click', () => {
                this.checkUserInput();
            });
        }
    }
}