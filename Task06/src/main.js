import {
    startGame,
    informationOutput,
    showGameOutput
} from './View.js';

import {
    btnNextInfo,
    btnNextShowGame,
    btnCheckNumber,
    btnReplayGame,
    checkNumber
} from "./Model.js";


startGame();

btnNextInfo.addEventListener("click", informationOutput);
btnNextShowGame.addEventListener("click", showGameOutput);
btnCheckNumber.addEventListener("click", checkNumber);
btnReplayGame.addEventListener("click", showGameOutput);
