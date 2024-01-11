<?php

namespace Alexzero00\GuessNumber;

use RedBeanPHP\R;
use RedBeanPHP\RedException\SQL;

use function cli\line;
use function cli\input;

class Model
{
    private $player_name;
    private $game;
    private $max_number = 500;
    private $max_attempts = 15;

    public function __construct($player_name)
    {
        R::setup('sqlite:' . __DIR__ . '/database.db');
        $this->player_name = $player_name;
        $this->game = R::dispense('games');
        $this->game->date = date('Y-m-d H:i:s');
        $this->game->player_name = $this->player_name;
        $this->game->max_number = $this->max_number;
        $this->game->generated_number = 0;
        $this->game->outcome = 'in progress';
        try {
            R::store($this->game);
        } catch (SQL $e) {
        }
    }

    public function newGate($player_name)
    {
        $this->player_name = $player_name;
        $this->game = R::dispense('games');
        $this->game->date = date('Y-m-d H:i:s');
        $this->game->player_name = $this->player_name;
        $this->game->max_number = $this->max_number;
        $this->game->generated_number = 0;
        $this->game->outcome = 'in progress';
        try {
            R::store($this->game);
        } catch (SQL $e) {
        }
    }

    public function generateNumber()
    {
        $this->game->generated_number = mt_rand(1, $this->max_number);
    }

    public function setNumber()
    {
        try {
            R::store($this->game);
        } catch (SQL $e) {
        }
    }

    public function getGeneratedNumber()
    {
        return $this->game->generated_number;
    }

    public function saveAttempt($attempt_number, $proposed_number, $computer_response)
    {
        $attempt = R::dispense('attempts');
        $attempt->game_id = $this->game->id;
        $attempt->attempt_number = $attempt_number;
        $attempt->proposed_number = $proposed_number;
        $attempt->computer_response = $computer_response;
        R::store($attempt);
    }

    public function updateGameOutcome($outcome)
    {
        $this->game->outcome = $outcome;
        try {
            R::store($this->game);
        } catch (SQL $e) {
        }
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
        $this->game->max_number = $max_number;
        R::store($this->game);
    }

    public function setMaxAttempts($max_attempts)
    {
        $this->max_attempts = $max_attempts;
    }

    public function delete()
    {
        R::trash($this->game);
    }
}
