'use strict';

import $ from 'jquery';

class User {
    constructor(App, $updateBtn, totalSentence) {
        this.totalSentence     = totalSentence;
        this.totalCompleted    = 0;
        this.$updateBtn        = $updateBtn;
        this.saveLessonUrl     = App.saveLessonUrl;
        this.completeLessonUrl = App.completeLessonUrl;
        this.sentencesToSubmit = [];

        this.initEvent();
    }

    completeSentence(sentenceId) {
        this.sentencesToSubmit.push(sentenceId);
        this.totalCompleted++;
        this.toggleUpdateBtn();

        if (this.completedAllSentences()) {
            this.completeLesson();
        }
    }

    toggleUpdateBtn() {
        const $updateBtn = this.$updateBtn;

        if (this.sentencesToSubmit.length > 0 &&
            $updateBtn.is(':disabled')) {
            $updateBtn.removeAttr('disabled');
            $updateBtn.html(`Lưu kết quả`);
        } else if (this.sentencesToSubmit.length === 0 &&
            !$updateBtn.is(':disabled')) {
            $updateBtn.attr('disabled', 'disabled');
        }
    }

    updateBtnOnClickHandler() {
        if (this.completedAllSentences()) {
            this.completeLesson();
        } else {
            this.saveLesson();
        }
    }

    completedAllSentences() {
        return this.totalSentence === this.totalCompleted;
    }

    saveLesson() {
        if (this.sentencesToSubmit.length === 0) {
            return;
        }

        this.updateProgress(
            this.saveLessonUrl,
            {'sentences': this.sentencesToSubmit}
        ).done(() => {
            this.sentencesToSubmit.length = 0;
            setTimeout(() => {
                this.$updateBtn.html(`Đã lưu <i class="glyphicon glyphicon-check"></i>`);
                this.toggleUpdateBtn();
                this.initEvent();
            }, 500);
        });
    }

    completeLesson() {
        this.updateProgress(this.completeLessonUrl)
            .done(() => {
                this.sentencesToSubmit.length = 0;
                setTimeout(() => {
                    this.$updateBtn.html(`Đã hoàn thành <i class="glyphicon glyphicon-check"></i>`);
                    this.toggleUpdateBtn();
                }, 500);
            });
    }

    updateProgress(url, data = null) {
        this.setIsUpdating(true);

        return $.post({url, data});
    }

    setIsUpdating(isUpdating) {
        const $updateBtn = this.$updateBtn;

        if (isUpdating) {
            $updateBtn.off('click');
            $updateBtn.html(`Đang xử lý <i class='glyphicon glyphicon-refresh glyphicon-spin'></i>`);
        }
    }

    initEvent() {
        this.$updateBtn.on('click', () => {
            this.updateBtnOnClickHandler()
        });
    }
}

export default User;
