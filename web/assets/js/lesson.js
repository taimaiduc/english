$(document).ready(function () {
    (function () {
        const $sentences        = $('.js-sentence');
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

        let sentenceIndex       = 0;
        let doneSentences       = $sentences.filter('.done').length;
        let userLoggedIn        = App.userLoggedIn;

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

        const focusToWrongWord = function (wrongWordPos) {
            let times = 0,
                pos = 0;

            while (times <= wrongWordPos && pos !== -1) {
                pos = $inputs[sentenceIndex].value.indexOf(' ', pos+1);
                times++;
            }

            $inputs[sentenceIndex].setSelectionRange(pos, pos);
        };

        const checkAnswer = function () {
            const $currentSentence = $($sentences[sentenceIndex]);
            const currentInput = $inputs[sentenceIndex];
            currentInput.value = currentInput.value.trim().replace(/\s\s+/g, ' ');
            const userInput = generalizeSentence(currentInput.value);
            const ourAnswer = JSON.parse($currentSentence.attr('data-sentence-json-answer'));
            let userInputIndex = 0;
            let wrongWordPos = 0;
            console.log(userInput);
            console.log(ourAnswer);

            // userInput = ["joe", "has", "2500", "in", "the", "bank"];
            // ourAnswer = ["joe", "has", Array(3), "in", "the", "bank"]
            // ourAnswer[2] = [[Array(3), Array(2), "2500"]]
            // ourAnswer[2][0] = ["twenty-five", "hundred", "dollars"]
            // ourAnswer[2][1] = ["2500", "dollars"]
            // ourAnswer[2][2] = "2500"

            for (let i = 0; i < ourAnswer.length; i++) { // loops 6 times
                // ourAnswer[0] and ourAnswer[1] === string, jsut compare 2 strings
                if (typeof ourAnswer[i] === 'string' && ourAnswer[i] !== userInput[i]) {
                    wrongWordPos = i; // if there's a wrong word, stop looping
                    break;
                } else if (Array.isArray(ourAnswer[i])) {
                    // ourAnswer[2] is an array, loop it (3 times).
                    for (let j = 0; j < ourAnswer[i].length; j++) {
                        if (Array.isArray(ourAnswer[i][j])) {
                            // ourAnswer[2][0] and ourAnswer[2][1] are arrays
                            const wordCount = ourAnswer[i][j].length;
                            let correctWord = 0;
                            for (let k = 0; k < wordCount; k++) {
                                if (userInput[i+k] !== ourAnswer[i][j][k]) {
                                    correctWord = 0;
                                    continue;
                                } else {
                                    correctWord++;
                                    if (wordCount === correctWord) {
                                        break;
                                    }
                                }
                                console.log(userInput[i+k], ourAnswer[i][j][k]);
                            }

                            console.log(wordCount === correctWord);

                        } else {
                            // ourAnswer[2][2] is a string
                        }
                    }
                }
            }

            return;
            const input = generalizeSentence($inputs[sentenceIndex].value);
            const answer = generalizeSentence($answers[sentenceIndex].innerHTML);
            let result = $answers[sentenceIndex].innerHTML.split(' ');
            let answerCorrect = true;

            for (let i = 0; i < answer.length; i++) {
                if (!answerCorrect) {
                    result[i] = '*'.repeat(result[i].length);
                    continue;
                }

                if (answer[i] !== input[i] && answerCorrect){
                    result[i] = "<span class='text-danger'>"+result[i]+"</span>";
                    answerCorrect = false;

                    focusToWrongWord(i);
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
            $updateUserBtns.addClass('in-progress');
            const args = {};
            
            if (isLessonDone()) {
                args.url = completeLessonUrl;
            } else {
                args.url = saveLessonUrl;
                args.data = {
                    'sentences': sentencesToSave
                };
            }

            return $.post(args)
                .done(function () {
                    sentencesToSave.length = 0;
                    $updateUserBtns.find('.success-sign')
                        .fadeIn().delay(2000).fadeOut(500);
                })
                .always(function () {
                    $updateUserBtns.removeClass('in-progress');
                    $updateUserBtns.on('click', updateUserBtnOnClickHandler);
                });
        };

        const audioBtnsOnClickHandler = function () {
            sentenceIndex = $audioBtns.index(this);
            playAudio();
        };

        const inputOnFocusHandler = function () {
            sentenceIndex = $inputs.index(this);

            const $self = $(this);
            const $parent = $self.closest('.js-sentences-wrapper');
            const distance = $self.position().top;
            const scrollTop = $parent.scrollTop();

            if (distance > 340) {
                $parent.animate({
                    scrollTop: scrollTop + 300
                }, 200);
            }
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
            checkAnswer();
            /*if (checkAnswer()) {
                $sentence.addClass('done');
                $(this).find('i')
                    .removeClass('glyphicon-send')
                    .addClass('glyphicon-check');
                doneSentences++;
            }*/

            if (isLessonDone()) {
                if (userLoggedIn) {
                    updateUserProgress();
                }

                $updateUserBtns.addClass('lesson-completed');
            }
        };

        const updateUserBtnOnClickHandler = function () {
            if (!userLoggedIn) {
                return;
            }

            if (sentencesToSave.length === 0) {
                return;
            }

            $updateUserBtns.off('click');
            
            updateUserProgress();
        };

        $audioBtns.on('click', audioBtnsOnClickHandler);
        $checkBtns.on('click', checkBtnsOnClickHander);
        $inputs.on('focus', inputOnFocusHandler)
            .on('keydown', inputOnKeydownHandler);
        $updateUserBtns.on('click', updateUserBtnOnClickHandler)
            .parent().tooltip();

        $(window).bind('beforeunload', function() {
            if (sentencesToSave.length > 0 && userLoggedIn) {
                return false;
            }
        });
    })();
});