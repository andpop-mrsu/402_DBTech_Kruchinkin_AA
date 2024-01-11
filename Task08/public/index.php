<?php

require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;

$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);

$app->get('/', 'index.html');

$app->get('/games', function ($request, $response) {
    $gamesinfo = json_encode(listGames());
    $response->getBody()->write($gamesinfo);
    return $response;
});

$app->get('/games/{id}', function ($request, $response, array $args) {
    $Gameid = $args['id'];
    $responseBody = json_encode(turnsById($Gameid));
    $response->getBody()->write($responseBody);
    return $response;
});

$app->post('/games', function ($request, $response) {
    $string = json_decode($request->getBody());
    $info = explode("|", $string);
    $gamesinfo = explode("+", $info[1]);
    $turns = explode("+", $info[0]);
    insertInfo($gamesinfo, $turns);
    $response->write('Database created!');
    return $response;
});

$app->run();

function openDatabase()
{
    if (!file_exists("./../db/guess-number.db")) {
        $db = new \SQLite3('./../db/guess-number.db');

        $gamesinfoTable = "CREATE TABLE info(
            id INTEGER PRIMARY KEY,
            date DATE,
            name TEXT,
            result TEXT)";
        $db->exec($gamesinfoTable);


        $stepsInfoTable = "CREATE TABLE attempts(
            id INTEGER KEY,
            attempt INTEGER,
            letter TEXT,
            result TEXT)";
        $db->exec($stepsInfoTable);
    } else {
        $db = new \SQLite3('./../db/guess-number.db');
    }
    return $db;
}

function getGameId($db)
{
    $query = "SELECT id 
    FROM info 
    ORDER BY id DESC LIMIT 1";
    $result = $db->querySingle($query);
    if (is_null($result)) {
        return 1;
    }
    return $result + 1;
}

function insertInfo($gamesinfo, $turns)
{
    $db = openDatabase();
    $id = getGameId($db);
    $data = $gamesinfo[0];
    $name = $gamesinfo[1];
    $result = $gamesinfo[2];
    $attempt = $gamesinfo[3];
    $letter = $gamesinfo[4];
    $letterResult = $gamesinfo[5];
    $db->exec("INSERT INTO info (
        id,
        date,
        name,
        result
        ) VALUES (
        '$id', 
        '$data', 
        '$name',  
        '$result')");
    for ($i = 0; $i < count($attempt); $i++) {
        $db->exec("INSERT INTO attempts (
            id, 
            attempt, 
            letter,
            result
            ) VALUES (
            '$id', 
            '$attempt[$i]', 
            '$letter[$i]',
            '$letterResult[$i]')");
    }
}

function listGames()
{
    $db = openDatabase();
    $result = $db->query("SELECT * FROM info");
    $gamesinfo = "";
    while ($row = $result->fetchArray()) {
        for ($i = 0; $i < 5; $i++) {
            $gamesinfo .= $row[$i] . "|";
        }
        $gamesinfo .= ";";
    }
    return $gamesinfo;
}


function gameById($id)
{
    $db = openDatabase();
    $result = $db->query("SELECT * FROM info WHERE id = '$id'");
    $gamesinfo = "";
    while ($row = $result->fetchArray()) {
        for ($i = 0; $i < 5; $i++) {
            $gamesinfo .= $row[$i] . "|";
        }
        $gamesinfo .= ";";
    }
}

function turnsById($id)
{
    $db = openDatabase();
    $result = $db->query("SELECT * FROM attempts WHERE id = '$id'");
    $turnsInfo = "";
    while ($row = $result->fetchArray()) {
        for ($i = 0; $i < 4; $i++) {
            $turnsInfo .= $row[$i] . "|";
        }
        $turnsInfo .= ";";
    }
    return $turnsInfo;
}
