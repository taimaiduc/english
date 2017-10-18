'use strict';

import Audio from './Sentence/Audio';
import Input from './Sentence/Input';
import Result from './Sentence/Result';
import Answer from './Sentence/Answer';
import CheckButton from './Sentence/CheckButton';
import InputChecker from './Sentence/InputChecker';

class Sentence {
    constructor(lesson, $sentence, index) {
        this.lesson    = lesson;
        this.$sentence = $sentence;
        this.index     = index;
        this.wasSaved  = $sentence.hasClass('done');
        this.isDone    = this.wasSaved;
        this.audio     = new Audio($sentence.find('.js-audio'), $sentence.find('.js-audio-btn'));
        this.input     = new Input(lesson, this, $sentence.find('.js-input'));

        if (!this.wasSaved) {
            this.id       = this.$sentence.data('id');
            this.checkBtn = new CheckButton(this, $sentence.find('.js-check-answer-btn'));
            this.result   = new Result(this.$sentence.find('.js-result'));
            this.answer   = new Answer(this.$sentence.find('.js-answer'));
        }
    }

    checkUserInput() {
        const result = InputChecker.check(this.input, this.answer);

        if (result === true) {
            this.inputCorrectHandler();
        } else {
            this.inputIncorrectHandler(result);
        }
    }

    getPrev() {
        return this.lesson.sentences[this.index - 1];
    }

    getNext() {
        return this.lesson.sentences[this.index + 1];
    }

    focusToNext() {
        const sentence = this.getNext();

        if (sentence) {
            sentence.input.focus();
        }
    }

    focusToPrevious() {
        const sentence = this.getPrev();

        if (sentence) {
            sentence.input.focus();
        }
    }

    inputIncorrectHandler(result) {
        this.input.selectWrongWord(result.inputWrongWordPos);
        this.result.highlightWrongWord(this.answer.nice, result.answerWrongWordPos);
    }

    inputCorrectHandler() {
        this.$sentence.addClass('done');
        this.input.setVal(this.answer.nice);
        this.checkBtn.setDone();
        this.result.remove();
        this.answer.remove();
        this.isDone = true;

        if (this.lesson.user) {
            this.lesson.user.completeSentence(this.id);
        }

        delete(this.id);
        delete(this.checkBtn);
        delete(this.answer);
        delete(this.result);
    }

    scrollSentence() {
        const wrapper = this.lesson.$wrapper;
        const scrollTop = wrapper.scrollTop();

        wrapper.animate({
            scrollTop: scrollTop + 300
        }, 200);
    }
}

export default Sentence;
