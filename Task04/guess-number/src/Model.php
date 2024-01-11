<?php

namespace Alexzero00\GuessNumber;

use function cli\line;
use function cli\input;

class Model
{
    private $database;
    private $player_name;
    private $game_id;
    private $generated_number;
    private $max_number = 500;
    private $max_attempts = 15;

    public function __construct($player_name)
    {
        $this->database = new Database();
        $this->player_name = $player_name;
        $this->generated_number = 0;
        $this->game_id = $this->database->saveGame([
            'date' => date('Y-m-d H:i:s'),
            'player_name' => $this->player_name,
            'max_number' => $this->max_number,
            'generated_number' => $this->generated_number,
            'outcome' => 'in progress'
        ]);
    }

    public function newGate($player_name)
    {
        $this->database = new Database();
        $this->player_name = $player_name;
        $this->generated_number = 0;
        $this->game_id = $this->database->saveGame([
            'date' => date('Y-m-d H:i:s'),
            'player_name' => $this->player_name,
            'max_number' => $this->max_number,
            'generated_number' => $this->generated_number,
            'outcome' => 'in progress'
        ]);
    }

    public function generateNumber()
    {
        $this->generated_number = mt_rand(1, $this->max_number);
    }
    public function setNumber()
    {
        $this->database->setNumber($this->game_id, $this->generated_number);
    }
    public function getGeneratedNumber()
    {
        return $this->generated_number;
    }

    public function saveAttempt($attempt_number, $proposed_number, $computer_response)
    {
        $this->database->saveAttempt([
            'game_id' => $this->game_id,
            'attempt_number' => $attempt_number,
            'proposed_number' => $proposed_number,
            'computer_response' => $computer_response
        ]);
    }

    public function updateGameOutcome($outcome)
    {
        $this->database->updateGameOutcome($this->game_id, $outcome);
    }

    public function gameLoop()
    {
        line("Введите число:");
        $attempt = 0;
        while ($attempt < $this->max_attempts) {
            try {
                $user_input = input();
            } catch (\Exception $e) {
                return 3;
            }
            if ($user_input == $this->getGeneratedNumber()) {
                line("Поздравляем! Вы угадали число");
                $this->saveAttempt($attempt, $user_input, 'correct');
                $this->updateGameOutcome('win');
                return 4;
            }

            if ($user_input === "!") {
                line("Вы вышли из игры");
                $this->saveAttempt($attempt, $user_input, 'exit');
                $this->updateGameOutcome('exit');
                return 4;
            }

            if ($user_input > $this->getGeneratedNumber()) {
                line("Загаданное число меньше");
                $this->saveAttempt($attempt, $user_input, 'less');
            } else {
                line("Загаданное число больше");
                $this->saveAttempt($attempt, $user_input, 'more');
            }
            $attempt++;
        }
        line("Попытки исчерпаны");
        $this->updateGameOutcome('loss');
        return 5;
    }

    public function setMaxNumber($max_number)
    {
        $this->max_number = $max_number;
        $this->database->setMaxNumber($this->game_id, $max_number);
    }

    public function setMaxAttempts($max_attempts)
    {
        $this->max_attempts = $max_attempts;
    }

    public function delete()
    {
        $this->database->delete();
    }
}
