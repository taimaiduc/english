$(document).ready(function () {
    (function () {
        let sentenceIndex       = 0;
        const $sentences        = $('.js-sentence');
        let   doneSentences     = $sentences.filter('.done').length;
        const $audios           = $sentences.find('audio');
        const $inputs           = $sentences.find('.js-input');
        const $answers          = $sentences.find('.js-answer');
        const $results          = $sentences.find('.js-result');
        const $audioBtns        = $sentences.find('.js-play-audio-btn');
        const $checkBtns        = $sentences.find('.js-check-answer-btn');
        const $updateUserBtns   = $('.js-update-user-progress');
        const saveLessonUrl     = App.saveLessonUrl;
        const completeLessonUrl = App.completeLessonUrl;
        const sentencesToSave   = [];

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

        const generalizeSentence = function (sentence) {
            return sentence
                .trim()
                .toLowerCase()
                .replace(/[^\w\s-]*/g, '')
                .replace(/ - /g, '')
                .replace(/  /g, ' ')
                .split(' ');
        };

        const isLessonDone = function () {
            return doneSentences === $sentences.length;
        };

        const checkAnswer = function () {
            const input = generalizeSentence($inputs[sentenceIndex].value);
            const answer = generalizeSentence($answers[sentenceIndex].innerHTML);
            let result = $answers[sentenceIndex].innerHTML.split(' ');
            let answerCorrect = true;
            
            for (let i = 0; i < answer.length; i++) {
                if (answer[i] !== input[i]){
                    result[i] = "<span class='text-danger'>"+result[i]+"</span>";
                    answerCorrect = false;
                }
            }

            if (input.length > answer.length) {
                result[result.length - 1] = "<span class='text-danger'>"+result[result.length - 1]+"</span>";
                answerCorrect = false;
            }

            if (!answerCorrect) {
                $results[sentenceIndex].innerHTML = result.join(' ');
                return false;
            } else {
                $inputs[sentenceIndex].value = $answers[sentenceIndex].innerHTML;

                $results[sentenceIndex].outerHTML = '';
                $answers[sentenceIndex].outerHTML = '';
                delete $results[sentenceIndex];
                delete $answers[sentenceIndex];

                sentencesToSave.push(
                    $sentences[sentenceIndex]
                        .getAttribute('data-sentence-id')
                );

                return true;
            }
        };

        const updateUserProgress = function () {
            const args = {};
            
            if (isLessonDone()) {
                args.url = completeLessonUrl;
            } else {
                args.url = saveLessonUrl;
                args.data = {
                    'sentences': sentencesToSave
                };
            }
            console.log(sentencesToSave);
            return $.post(args)
                .done(function () {
                    sentencesToSave.length = 0;
                    console.log(sentencesToSave);
                });
        };

        const audioBtnsOnClickHandler = function () {
            sentenceIndex = $audioBtns.index(this);
            playAudio();
        };

        const inputOnFocusHandler = function () {
            sentenceIndex = $inputs.index(this);
        };

        const inputOnKeydownHandler = function (e) {
            if (e.ctrlKey) {
                if (e.keyCode === 32) { // Ctrl + Space = toggleAudio()
                    playAudio();
                } else if (e.keyCode === 10 || e.keyCode === 13) { // Ctrl + Enter = Hide result
                    $results[sentenceIndex].innerHTML = '';
                }
            } else if (e.keyCode === 13) { // Enter = Compare input & answer
                $checkBtns[sentenceIndex].click();
            } else if (e.keyCode === 40) { // Up arrow key = previous sentence
                if ($inputs[sentenceIndex+1]) {
                    $inputs[sentenceIndex+1].focus();
                }
            } else if (e.keyCode === 38) {
                if ($inputs[sentenceIndex-1]) {
                    $inputs[sentenceIndex-1].focus();
                }
            }
        };

        const checkBtnsOnClickHander = function () {
            sentenceIndex = $checkBtns.index(this);
            const $sentence = $($sentences[sentenceIndex]);

            if ($sentence.hasClass('done')) {
                return;
            }

            if (checkAnswer()) {
                $sentence.addClass('done');
                $(this).find('i')
                    .removeClass('glyphicon-send')
                    .addClass('glyphicon-ok');
                doneSentences++;
            }

            if (isLessonDone()) {}
        };

        const updateUserBtnOnClickHandler = function () {
            $updateUserBtns.off('click');
            
            updateUserProgress()
                .always(function () {
                    $updateUserBtns.on('click', updateUserBtnOnClickHandler)
                });
        };



        $audioBtns.on('click', audioBtnsOnClickHandler);
        $checkBtns.on('click', checkBtnsOnClickHander);
        $inputs.on('focus', inputOnFocusHandler)
            .on('keydown', inputOnKeydownHandler);
        $updateUserBtns.on('click', updateUserBtnOnClickHandler);
    })();
});