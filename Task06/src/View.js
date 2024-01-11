import {
    blockGreeting,
    blockInformation,
    blockShowGame,
    blockMessage,
    blockResult,
    blockResultText,
    fieldName,
    textInfo,
    numAttempt,
    maxNum,
    makeNumber,
    hiddenNumber,
    var_numAttempt
} from './Model.js';

export var userName;

export function startGame() {
    blockGreeting.style.display = 'flex';
    blockInformation.style.display = 'none';
    blockShowGame.style.display = 'none';
    blockMessage.style.display = 'none';
    blockResult.style.display = 'none';
}

export function informationOutput() {
    if (fieldName.value === "") {
        alert("Enter your name")
    } else {
        userName = fieldName.value;

        blockGreeting.style.display = 'none';
        blockInformation.style.display = 'flex';
        blockShowGame.style.display = 'none';

        textInfo.innerHTML = "Hello, <b>" + userName + "</b>! Let's play the game \"Guess Number\"." +
            "  I guess the number<b> from 1 to " + maxNum + "." +
            "</b> Try to guess this number for <b>" + numAttempt + "</b> attempts.";
    }
}

export function showGameOutput() {
    blockGreeting.style.display = 'none';
    blockInformation.style.display = 'none';
    blockShowGame.style.display = 'flex';
    blockMessage.style.display = 'none';
    blockResult.style.display = 'none';

    makeNumber();
}

export function messageOutput(type_message) {
    if (type_message === "less") {
        blockMessage.style.display = 'flex';
        blockMessage.innerHTML = "Your number is less than the right one. Attempts left: " + var_numAttempt;
    }
    if (type_message === "more") {
        blockMessage.style.display = 'flex';
        blockMessage.innerHTML = "Your number is higher than the right one. Attempts left: " + var_numAttempt;
    }
    if (type_message === "win") {
        blockMessage.style.display = 'none';
        blockShowGame.style.display = 'none';
        blockResult.style.display = 'flex';
        blockResultText.innerHTML = "Victory! You guessed the number " + hiddenNumber +
            " for " + (numAttempt - var_numAttempt + 1) + "th attempt";
    }
    if (type_message === "loose") {
        blockMessage.style.display = 'none';
        blockShowGame.style.display = 'none';
        blockResult.style.display = 'flex';
        blockResultText.innerHTML = "Lose! You didn't guess the number " + hiddenNumber;
    }
}
