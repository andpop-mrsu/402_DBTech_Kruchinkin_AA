import {
    messageOutput,
    writeAllGame
} from "./View.js";


let idbSupported = false;
let db;

let dbName = "guess-number";
let dbVersion = 1;

let game,
    idLastGame;

export const
    block_greeting = document.getElementById("greeting"),
    block_information = document.getElementById("information"),
    block_showGame = document.getElementById("show-game"),
    block_message = document.getElementById("message"),
    block_result = document.getElementById("result"),
    block_resultText = document.getElementById("resultText"),
    block_menuGame = document.getElementById("menuGame"),
    block_list = document.getElementById("list"),
    block_text_list = document.getElementById("text_list");

export const
    btn_goMenu = document.getElementById("goMenu"),
    btn_nextMenuGame = document.getElementById("nextInfo"),
    btn_nextShowGame = document.getElementById("nextShowGame"),
    btn_checkNumber = document.getElementById("checkNumber"),
    btn_replayGame = document.getElementById("replayGame"),
    btn_newGame = document.getElementById("newGame"),
    btn_listAllGame = document.getElementById("listAllGame"),
    btn_listWinGame = document.getElementById("listWinGame"),
    btn_listLossGame = document.getElementById("listLossGame");

export const
    field_name = document.getElementById("name"),
    field_getNumber = document.getElementById("getNumber");

export const
    text_info = document.getElementById("info");

export const
    num_attempt = 3,
    max_num = 5;

export let
    var_num_attempt = num_attempt,
    hidden_number;

let getNumber;

export function checkNumber() {
    if (field_getNumber.value === "") {
        alert("Enter the number");
    } else {
        getNumber = Number(field_getNumber.value);
        if (var_num_attempt !== 0) {
            if (getNumber < hidden_number) {
                var_num_attempt--;
                messageOutput("less");
                addAttempt((num_attempt - var_num_attempt), getNumber, "less");
            }
            if (getNumber > hidden_number) {
                var_num_attempt--;
                messageOutput("more");
                addAttempt((num_attempt - var_num_attempt), getNumber, "more");
            }
            if (getNumber === hidden_number) {
                messageOutput("win");
                addAttempt((num_attempt - var_num_attempt), getNumber, "win");
                editGame("win");
            }
        } else {
            messageOutput("loss");
            editGame("loose");
            addAttempt((num_attempt - var_num_attempt + 1), getNumber, "loose");
        }
    }
}

export function makeNumber() {
    hidden_number = Math.floor(Math.random() * (max_num - 1)) + 1;
    var_num_attempt = num_attempt;
}

export function startDB() {
    if ("indexedDB" in window) {
        idbSupported = true;
    }

    if (idbSupported) {
        const openRequest = indexedDB.open(dbName, dbVersion);

        openRequest.onupgradeneeded = function (e) {
            console.log("Updating...");
            const thisDB = e.target.result;

            if (!thisDB.objectStoreNames.contains("gamesinfo")) {
                thisDB.createObjectStore("gamesinfo", {autoIncrement: true}).createIndex('idxOutcome', 'outcome');
            }

            if (!thisDB.objectStoreNames.contains("attempts")) {
                thisDB.createObjectStore("attempts", {autoIncrement: true}).createIndex('idx', 'idGame');
            }
        }

        openRequest.onsuccess = function (e) {
            console.log("Success!");
            db = e.target.result;
        }

        openRequest.onerror = function (e) {
            console.log("Error");
            console.dir(e);
        }
    }
}

export function addNewGame(name, max_num, num_attempt, hidden_number) {
    const transaction = db.transaction(["gamesinfo"], "readwrite");
    const store = transaction.objectStore("gamesinfo");

    game = {
        date: new Date().toLocaleDateString(),
        userName: name,
        maxNumber: max_num,
        numAttempt: num_attempt,
        hiddenNumber: hidden_number,
        outcome: null
    }

    let request = store.add(game);

    request.onerror = function (e) {
        console.log("Error", e.target.error.name, ":", e.target.error);
    }

    request.onsuccess = function (e) {
        console.log("New game entry added!");
    }

    getLastIdGame()
}

export function getLastIdGame() {
    const transaction = db.transaction(["gamesinfo"], "readonly");
    const store = transaction.objectStore("gamesinfo");

    let request = store.openCursor(null, "prev");

    request.onsuccess = function (event) {
        let cursor = request.result;

        idLastGame = cursor.key;
    }
}

 function editGame(outcome) {
    const transaction = db.transaction(["gamesinfo"], "readwrite");
    const store = transaction.objectStore("gamesinfo");

    game.outcome = outcome;

    let request = store.put(game, idLastGame);

    request.onerror = function (e) {
        console.log("Error", e.target.error.name, ":", e.target.error);
    }

    request.onsuccess = function (e) {
        console.log("Game " + idLastGame + " edited");
    }

}

 function addAttempt(numAttempt, number, ansComp) {
    const transaction = db.transaction(["attempts"], "readwrite");
    const store = transaction.objectStore("attempts");

    let attempt = {
        idGame: idLastGame,
        numAttempt: numAttempt,
        getNumber: number,
        ansComp: ansComp
    };

    let request = store.add(attempt);

    request.onerror = function (e) {
        console.log("Error", e.target.error.name, ":", e.target.error);
    }

    request.onsuccess = function (e) {
        console.log("Trial entry added!");
    }

}

export function getAllGame() {
    getGame("all");
}

export function getWinGame() {
    getGame("win");
}

export function getLossGame() {
    getGame("loss");
}

async function getGame(out) {

    const gamesinfo = db.transaction(["gamesinfo"], "readonly");
    const store_gamesinfo = gamesinfo.objectStore("gamesinfo");

    const index = store_gamesinfo.index("idxOutcome");

    let cursor_gamesinfo;

    if (out === "all") {
        cursor_gamesinfo = await store_gamesinfo.openCursor();
    } else {
        cursor_gamesinfo = await index.openCursor(out);
    }

    let result = "<table><tr>" +
        "<th>Username</th>" +
        "<th>Maximum number</th>" +
        "<th>Number of attempts</th>" +
        "<th>Hidden number</th>" +
        "<th>Outcome</th>" +
        "<th>Date</th>" +
        "</tr>";

    cursor_gamesinfo.onsuccess = async function (e) {
        let res = e.target.result;

        if (res) {

            result += "<tr>" +
                "<td>" + res.value.userName + "</td>" +
                "<td>" + res.value.maxNumber + "</td>" +
                "<td>" + res.value.numAttempt + "</td>" +
                "<td>" + res.value.hiddenNumber + "</td>" +
                "<td>" + res.value.outcome + "</td>" +
                "<td>" + res.value.date + "</td>" +
                "</tr>";

            result += await getAttempts(res.key);

            res.continue();
        } else {
            result += "</table>";
            writeAllGame(result);
        }
    }
}

let resGetAttempts;

function getAttempts(id) {
    const attempts = db.transaction(["attempts"], "readonly");
    const store_attempts = attempts.objectStore("attempts");
    const gameIndex = store_attempts.index("idx");

    let request = gameIndex.getAll(id);

    request.onsuccess = function () {
        if (request.result !== undefined) {
            resGetAttempts ='';
            request.result.forEach(function (entry) {
                resGetAttempts += '';
            });
        } else {
            console.log("No attempts!");
        }
    };

    return resGetAttempts;
}
