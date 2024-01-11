import {
    block_greeting,
    block_information,
    block_showGame,
    block_message,
    block_result,
    block_resultText,
    block_menuGame,
    field_name,
    text_info,
    num_attempt,
    max_num,
    makeNumber,
    hidden_number,
    addNewGame,
    var_num_attempt, block_list, block_text_list
} from './Model.js';

export var userName;

export function informationOutput() {
    if (field_name.value === "") {
        alert("Enter your name")
    } else {
        userName = field_name.value;

        hideAll();

        block_information.style.display = 'flex';

        textInfo.innerHTML = "Hello, <b>" + userName + "</b>! Let's play the game \"Guess Number\"." +
            "  I guess the number<b> from 1 to " + maxNum + "." +
            "</b> Try to guess this number for <b>" + numAttempt + "</b> attempts.";
    }
}
export function startGame() {
    hideAll();

    block_greeting.style.display = 'flex';
}

export function menuGame(){
    if (field_name.value === "") {
        alert("Enter your name")
    } else {
    hideAll();
    block_menuGame.style.display = 'flex';
    }
}

export function showGameOutput() {
    hideAll();

    makeNumber();

    addNewGame(userName, max_num, num_attempt, hidden_number);

    block_showGame.style.display = 'flex';
}

export function messageOutput(type_message) {
    block_resultText.style.display = "flex";

    if (type_message === "less") {
        block_message.style.display = 'flex';
        block_message.innerHTML = "Your number is less than the right one. Attempts left: " + var_num_attempt;
    }
    if (type_message === "more") {
        block_message.style.display = 'flex';
        block_message.innerHTML = "Your number is higher than the right one. Attempts left: " + var_num_attempt;
    }
    if (type_message === "win") {
        block_message.style.display = 'none';
        block_showGame.style.display = 'none';
        block_result.style.display = 'flex';
        block_resultText.innerHTML = "Victory! You guessed the number " + hidden_number +
            " for " + (num_attempt - var_num_attempt + 1) + "th attempt";
    }
    if (type_message === "loose") {
        block_message.style.display = 'none';
        block_showGame.style.display = 'none';
        block_result.style.display = 'flex';
        block_resultText.innerHTML = "Lose! You didn't guess the number " + hidden_number;
    }
}

export function writeAllGame(result){
    hideAll();

    block_list.style.display = 'flex';
    block_text_list.innerHTML = result;
}

function hideAll(){
    block_greeting.style.display = 'none';
    block_information.style.display = 'none';
    block_showGame.style.display = 'none';
    block_message.style.display = 'none';
    block_result.style.display = 'none';
    block_menuGame.style.display = 'none';
    block_list.style.display = 'none';
}
