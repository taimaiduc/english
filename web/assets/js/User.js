class User {
    constructor(totalSentence) {
        this.totalSentence     = totalSentence;
        this.totalCopmleted    = 0;
        this.$updateBtn        = $('.js-update-user-progress');
        this.saveLessonUrl     = App.saveLessonUrl;
        this.completeLessonUrl = App.completeLessonUrl;
        this.sentencesToSubmit = [];
        this.completedLesson   = false;

        this.initEvent();
    }

    completeSentence(sentenceId) {
        this.sentencesToSubmit.push(sentenceId);
        this.totalCopmleted++;
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
        return this.totalSentence === this.totalCopmleted;
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
            this.$updateBtn.html(`Đã lưu <i class="glyphicon glyphicon-check"></i>`);
            this.toggleUpdateBtn();
            this.initEvent();
        });
    }

    completeLesson() {
        this.updateProgress(
            this.completeLessonUrl,
            null
        ).done(() => {
            this.sentencesToSubmit.length = 0;
            this.$updateBtn.html(`Đã hoàn thành <i class="glyphicon glyphicon-check"></i>`);
            this.toggleUpdateBtn();
        });
    }

    updateProgress(url, data) {
        this.setIsUpdating(true);

        return $.post({url, data})
                .always(() => {
                    this.setIsUpdating(false);
                });
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