<?php

namespace Alexzero00\GuessNumber;

use PDO;

class Database
{
    private $pdo;

    public function __construct()
    {
        $databasePath = __DIR__ . '/database.db';
        $this->pdo = new PDO('sqlite:' . $databasePath);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Создание таблиц, если они еще не существуют
        $this->pdo->exec(
            "
            CREATE TABLE IF NOT EXISTS games (
                id INTEGER PRIMARY KEY,
                date TEXT,
                player_name TEXT,
                max_number INTEGER,
                generated_number INTEGER,
                outcome TEXT
            )
        "
        );

        $this->pdo->exec(
            "
            CREATE TABLE IF NOT EXISTS attempts (
                id INTEGER PRIMARY KEY,
                game_id INTEGER,
                attempt_number INTEGER,
                proposed_number INTEGER,
                computer_response TEXT,
                FOREIGN KEY(game_id) REFERENCES games(id)
            )
        "
        );
    }

    public function saveGame($game)
    {
        $stmt = $this->pdo->prepare(
            "
            INSERT INTO games (date, player_name, max_number, generated_number, outcome)
            VALUES (:date, :player_name, :max_number, :generated_number, :outcome)
        "
        );

        $stmt->execute(
            [
            ':date' => $game['date'],
            ':player_name' => $game['player_name'],
            ':max_number' => $game['max_number'],
            ':generated_number' => $game['generated_number'],
            ':outcome' => $game['outcome']
            ]
        );

        return $this->pdo->lastInsertId();
    }

    public function saveAttempt($attempt)
    {
        $stmt = $this->pdo->prepare(
            "
            INSERT INTO attempts (game_id, attempt_number, proposed_number, computer_response)
            VALUES (:game_id, :attempt_number, :proposed_number, :computer_response)
        "
        );

        $stmt->execute(
            [
            ':game_id' => $attempt['game_id'],
            ':attempt_number' => $attempt['attempt_number'],
            ':proposed_number' => $attempt['proposed_number'],
            ':computer_response' => $attempt['computer_response']
            ]
        );
    }

    public function updateGameOutcome($game_id, $outcome)
    {
        $stmt = $this->pdo->prepare("UPDATE games SET outcome = :outcome WHERE id = :game_id");
        $stmt->execute([':outcome' => $outcome, ':game_id' => $game_id]);
    }

    public function getGames($player_name)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM games WHERE player_name = :player_name");
        $stmt->execute([':player_name' => $player_name]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getWins($player_name)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM games WHERE player_name = :player_name AND outcome = 'win'");
        $stmt->execute([':player_name' => $player_name]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLosses($player_name)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM games WHERE player_name = :player_name AND outcome = 'loss'");
        $stmt->execute([':player_name' => $player_name]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPlayerStats($player_name)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as wins FROM games 
                                           WHERE player_name = :player_name AND outcome = 'win'");
        $stmt->execute([':player_name' => $player_name]);
        $wins = $stmt->fetch(PDO::FETCH_ASSOC)['wins'];

        $stmt = $this->pdo->prepare("SELECT COUNT(*) as losses FROM games 
                                           WHERE player_name = :player_name AND outcome = 'loss'");
        $stmt->execute([':player_name' => $player_name]);
        $losses = $stmt->fetch(PDO::FETCH_ASSOC)['losses'];

        return ['wins' => $wins, 'losses' => $losses];
    }

    public function setMaxNumber($game_id, $max_number)
    {
        $sql = "UPDATE games SET max_number = :max_number WHERE id = :game_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['max_number' => $max_number, 'game_id' => $game_id]);
    }

    public function setNumber($game_id, $number)
    {
        $sql = "UPDATE games SET generated_number = :generated_number WHERE id = :game_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['generated_number' => $number, 'game_id' => $game_id]);
    }

    public function delete()
    {
        $sql = "DELETE FROM games WHERE id = (SELECT MAX(id) FROM games)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
    }
}
