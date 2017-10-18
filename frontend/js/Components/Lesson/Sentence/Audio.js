'use strict';

class Audio {
    constructor($audio, $audioBtn) {
        this.$audio = $audio;
        this.$audioBtn = $audioBtn;

        this.$audioBtn.on('click', () => {
            this.play();
        });
    }

    play() {
        const audio = this.$audio[0];

        audio.currentTime = 0;
        audio.paused ? audio.play() : audio.pause();
    }
}

export default Audio;