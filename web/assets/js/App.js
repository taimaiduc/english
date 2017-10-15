class Lesson {
    constructor() {
        this.user = null;
        this.sentences = [];
    }

    focusToSentence(index) {
        const sentence = this.sentences[index];

        if (sentence) {
            sentence.$input.focus();
        }
    }

    static init() {
        const lesson = new Lesson();
        const $sentencesWrapper = $('.js-sentences-wrapper');
        const $sentences = $sentencesWrapper.find('.js-sentence');

        for (let i = 0; i < $sentences.length; i++) {
            lesson.sentences.push(
                new Sentence($sentencesWrapper, lesson, $sentences.eq(i), i)
            );
        }

        if (App.userLoggedIn) {
            const totalSentence = $sentences.length - $sentences.filter('.done').length;

            lesson.user = new User(totalSentence);
        }
    }
}

$(() => {
    Lesson.init();
});