'use strict';

import Sentence from './Lesson/Sentence';
import User from './Lesson/User';

class Lesson {
    constructor($wrapper) {
        this.$wrapper = $wrapper;
        this.user = null;
        this.sentences = [];
    }

    static init(App, $wrapper, $updateBtn) {
        const lesson     = new Lesson($wrapper);
        const $sentences = lesson.$wrapper.find('.js-sentence');

        for (let i = 0; i < $sentences.length; i++) {
            lesson.sentences.push(
                new Sentence(lesson, $sentences.eq(i), i)
            );
        }

        if (App.userLoggedIn) {
            const totalSentence = $sentences.length - $sentences.filter('.done').length;

            lesson.user = new User(App, $updateBtn, totalSentence);
        }
    }
}

export default Lesson;
