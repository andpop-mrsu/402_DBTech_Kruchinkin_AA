import {
    startGame,
    informationOutput,
    showGameOutput,
    menuGame
} from './View.js';

import {
    getAllGame,
     getLossGame, 
     getWinGame, 
     startDB,
    btn_nextMenuGame,
    btn_nextShowGame,
    btn_checkNumber,
    btn_replayGame,
    btn_newGame,
    btn_goMenu,
    checkNumber,
    btn_listAllGame,
    btn_listWinGame,
    btn_listLossGame
} from "./Model.js";

startGame();

document.addEventListener("DOMContentLoaded", startDB);

btn_nextMenuGame.addEventListener("click", menuGame);

btn_newGame.addEventListener("click", informationOutput);

btn_nextShowGame.addEventListener("click", showGameOutput);

btn_checkNumber.addEventListener("click", checkNumber);

btn_replayGame.addEventListener("click", showGameOutput);

btn_goMenu.addEventListener("click", menuGame);

btn_listAllGame.addEventListener("click", getAllGame);

btn_listWinGame.addEventListener("click", getWinGame);

btn_listLossGame.addEventListener("click", getLossGame);
