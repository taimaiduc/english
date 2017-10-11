$(document).ready(function () {
    (function (window, $) {
        const $sentences        = $('.js-sentence');
        const $audios           = $sentences.find('audio');
        const $inputs           = $sentences.find('.js-input');
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
                .replace(/ - /g, ' ')
                .replace(/\s\s+/g, ' ')
                .split(' ');
        };

        const isLessonDone = function () {
            return doneSentences === $sentences.length;
        };

        const focusToWrongWord = function (wordPosition) {
            if (wordPosition === 0) {
                return;
            }
            $inputs[sentenceIndex].value += ' ';

            // find the position of the space after the wrong word
            let spacePosStart = 0;
            let spacePosEnd = 0;
            for (let i = 0; i <= wordPosition; i++) {
                const newSpacePos = $inputs[sentenceIndex].value.indexOf(' ', spacePosEnd+1);
                if (newSpacePos > spacePosEnd) {
                    spacePosStart = spacePosEnd;
                    spacePosEnd = newSpacePos;
                }
            }


            // and move the cursor to that position
            $inputs[sentenceIndex].value  = $inputs[sentenceIndex].value.trim();
            $inputs[sentenceIndex].setSelectionRange(spacePosStart+1, spacePosEnd);
        };

        const highlightWrongWord = function (wordPosition) {
            const answer = $sentences[sentenceIndex]
                .getAttribute('data-sentence-nice-answer')
                .replace(/' - '/g, '').split(' ');

            for (let i = 0; i < answer.length; i++) {
                if (i === wordPosition) {
                    answer[i] = '<span class="text-danger">'+answer[i]+'</span>';
                } else if (i > wordPosition) {
                    answer[i] = '*'.repeat(4);
                }
            }

            $results[sentenceIndex].innerHTML = answer.join(' ');
        };

        const isUserInputCorrect = function () {
            $inputs[sentenceIndex].value = $inputs[sentenceIndex].value.replace(/\s\s+/g, ' ');

            const inputArr = generalizeSentence($inputs[sentenceIndex].value);
            const answerArr = JSON.parse($sentences[sentenceIndex].getAttribute('data-sentence-json-answer'));

            let inputWrongWordPos = -1;
            let answerWrongWordPos = -1;
            let inputIndex = -1;

            for (let i = 0; i < answerArr.length; i++) {
                if (inputWrongWordPos > -1) {
                    break;
                } else {
                    inputIndex++;
                }

                if (typeof answerArr[i] === 'string') {
                    if (answerArr[i] !== inputArr[inputIndex]) {
                        answerWrongWordPos = i;
                        inputWrongWordPos = inputIndex;
                    }
                }

                else if (Array.isArray(answerArr[i])) {
                    let isCorrect = false;
                    let correctWordCount = 0;
                    let lastCorrectWordPos = 0;

                    for (let j = 0; j < answerArr[i].length; j++) {
                        if (isCorrect) {
                            break;
                        }

                        if (typeof answerArr[i][j] === 'string') {
                            if (answerArr[i][j] === inputArr[inputIndex]) {
                                isCorrect = true;
                            }
                        }

                        else if (Array.isArray(answerArr[i][j])) {
                            for (let k = 0; k < answerArr[i][j].length; k++) {
                                if (answerArr[i][j][k] === inputArr[inputIndex+k]) {
                                    correctWordCount++;
                                } else {
                                    if (correctWordCount > lastCorrectWordPos) {
                                        lastCorrectWordPos = correctWordCount;
                                    }
                                    correctWordCount = 0;
                                    break;
                                }
                            }

                            if (correctWordCount === answerArr[i][j].length) {
                                isCorrect = true;
                                inputIndex += correctWordCount - 1;
                            }
                        }
                    }

                    if (!isCorrect) {
                        inputWrongWordPos = inputIndex + correctWordCount + lastCorrectWordPos;
                        answerWrongWordPos = inputIndex + correctWordCount;
                    }
                }
            }

            if (inputWrongWordPos === -1) {
                return true;
            }

            return {
                'inputWrongWordPos': inputWrongWordPos,
                'answerWrongWordPos': answerWrongWordPos
            };
        };

        const inputCorrectHandler = function () {
            const currentSentence = $sentences[sentenceIndex];

            $inputs[sentenceIndex].value = currentSentence.getAttribute('data-sentence-nice-answer');
            sentencesToSave.push(currentSentence.getAttribute('data-sentence-id'));
            $results[sentenceIndex].innerHTML = '';
            currentSentence.className = currentSentence.className + ' done';
            $checkBtns[sentenceIndex].innerHTML = '<i class="glyphicon glyphicon-ok"></i>';
            doneSentences++;
        };

        const inputIncorrectHandler = function (result) {
            focusToWrongWord(result.inputWrongWordPos);
            highlightWrongWord(result.answerWrongWordPos);
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
                .done(function (data) {
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

            const result = isUserInputCorrect();
            if (result === true) {
                inputCorrectHandler();
            } else {
                inputIncorrectHandler(result);
            }

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
    })(window, $);
});
