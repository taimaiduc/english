'use strict';

class InputChecker {
    static check(input, answer) {
        input.reformat();
        const inputArr = input.toArray();
        const answerArr = answer.array;

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
                    inputWrongWordPos = inputIndex + lastCorrectWordPos;
                    answerWrongWordPos = i;
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
}

export default InputChecker;