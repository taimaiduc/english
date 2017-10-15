'use strict';

class CheckButton {
    constructor(sentence, $checkBtn) {
        this.sentence = sentence;
        this.$checkBtn = $checkBtn;

        this.$checkBtn.on('click', () => {
            this.sentence.checkUserInput();
        });
    }

    setDone() {
        this.$checkBtn
            .children()
            .removeClass('glyphicon-send')
            .addClass('glyphicon-ok');

        this.$checkBtn.off('click');
    }
}

export default CheckButton;