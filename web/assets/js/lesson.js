$(document).ready(function () {
    const lesson = (function () {
        let sentenceIndex      = 0;
        const $sentences       = $('.js-sentence');
        const $audios          = $sentences.find('audio');
        const $inputs          = $sentences.find('.js-input');
        const answers          = app.lesson.answers;
        const $audioBtns       = $sentences.find('.js-play-audio-btn');
        const $checkAnswerBtns = $sentences.find('.js-check-answer-btn');
        const $hideAnswerBtns  = $sentences.find('.js-hide-answer-btn');

        const playAudio = function () {
            const audio = $audios[sentenceIndex];

            if (audio) {
                audio.currentTime = 0;
                if (audio.paused) {
                    $audios.trigger('pause');
                    audio.play();
                } else {
                    audio.pause();
                }
            }
        };

        const checkAnswer = function () {
            const input = $inputs[sentenceIndex].value.split(' ');
            const answer = answers[sentenceIndex];
            console.log(input, answer);

            for (let i = 0; i < answer.length; i++) {

            }
        };

        const initAudioBtns = function () {
            $audioBtns.on('click', function () {
                sentenceIndex = $audioBtns.index(this);
                playAudio();
            });
        };

        const initInputs = function () {
            $inputs.on('focus', function () {
                sentenceIndex = $inputs.index(this);
            });

            $inputs.on('keydown', function (e) {
                if (e.ctrlKey) {                    
                    if (e.keyCode === 32) { // Ctrl + Space = toggleAudio()
                        playAudio();
                    } else if (e.keyCode === 10 || e.keyCode === 13) { // Ctrl + Enter = Hide result

                    }
                } else if (e.keyCode === 13) { // Enter = Compare input & answer
                    checkAnswer();
                } else if (e.keyCode === 40) { // Up arrow key = previous sentence
                    if ($inputs[sentenceIndex+1]) {
                        $inputs[sentenceIndex+1].focus();
                    }
                } else if (e.keyCode === 38) {
                    if ($inputs[sentenceIndex-1]) {
                        $inputs[sentenceIndex-1].focus();
                    }
                }
            })
        };

        const initCheckBtns = function () {
            $checkAnswerBtns.on('click', function () {
                sentenceIndex = $checkAnswerBtns.index(this);
                checkAnswer();
            });
        };

        initInputs();
        initAudioBtns();
        initCheckBtns();
    })();
});