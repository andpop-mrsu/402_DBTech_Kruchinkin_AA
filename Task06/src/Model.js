import {
    messageOutput
} from "./View.js";

export const
    blockGreeting = document.getElementById("greeting"),
    blockInformation = document.getElementById("information"),
    blockShowGame = document.getElementById("show-game"),
    blockMessage = document.getElementById("message"),
    blockResult = document.getElementById("result"),
    blockResultText = document.getElementById("resultText");

export const
    btnNextInfo = document.getElementById("nextInfo"),
    btnNextShowGame = document.getElementById("nextShowGame"),
    btnCheckNumber = document.getElementById("checkNumber"),
    btnReplayGame = document.getElementById("replayGame");

export const
    fieldName = document.getElementById("name"),
    field_getNumber = document.getElementById("getNumber");

export const
    textInfo = document.getElementById("info");

export const
    numAttempt = 3,
    maxNum = 10;

export let
    var_numAttempt = numAttempt,
    hiddenNumber;

let getNumber;

export function checkNumber() {
    if (field_getNumber.value === "") {
        alert("Enter the number");
    } else {
        getNumber = Number(field_getNumber.value);
        if (var_numAttempt !== 0 ) {
            if (getNumber < hiddenNumber) {
                var_numAttempt--;
                messageOutput("less");
            }
            if (getNumber > hiddenNumber) {
                var_numAttempt--;
                messageOutput("more");
            }
            if (getNumber === hiddenNumber) {
                messageOutput("win");
            }
        } 
        else {
            messageOutput("loose");
        }
    }
}

export function makeNumber() {
    hiddenNumber = Math.floor(Math.random() * (maxNum - 1)) + 1;
    var_numAttempt = numAttempt;
}
