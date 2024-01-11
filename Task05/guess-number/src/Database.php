<?php

namespace Alexzero00\GuessNumber;

use RedBeanPHP\R;
use RedBeanPHP\RedException\SQL;

class Database
{
    public function __construct()
    {
        R::setup('sqlite:' . __DIR__ . '/database.db');
    }

    public function saveGame($game)
    {
        $gameBean = R::dispense('games');
        $gameBean->import($game);
        try {
            return R::store($gameBean);
        } catch (SQL $e) {
            return 0;
        }
    }

    public function saveAttempt($attempt)
    {
        $attemptBean = R::dispense('attempts');
        $attemptBean->import($attempt);
        try {
            return R::store($attemptBean);
        } catch (SQL $e) {
            return 0;
        }
    }

    public function updateGameOutcome($game_id, $outcome)
    {
        $gameBean = R::load('games', $game_id);
        $gameBean->outcome = $outcome;
        try {
            return R::store($gameBean);
        } catch (SQL $e) {
            return 0;
        }
    }

    public function getGames($player_name)
    {
        return R::find('games', ' player_name = ? ', [$player_name]);
    }

    public function getWins($player_name)
    {
        return R::find('games', ' player_name = ? AND outcome = "win" ', [$player_name]);
    }

    public function getLosses($player_name)
    {
        return R::find('games', ' player_name = ? AND outcome = "loss" ', [$player_name]);
    }

    public function getPlayerStats($player_name)
    {
        $wins = count(R::find('games', ' player_name = ? AND outcome = "win" ', [$player_name]));
        $losses = count(R::find('games', ' player_name = ? AND outcome = "loss" ', [$player_name]));
        return ['wins' => $wins, 'losses' => $losses];
    }

    public function setMaxNumber($game_id, $max_number)
    {
        $gameBean = R::load('games', $game_id);
        $gameBean->max_number = $max_number;
        try {
            return R::store($gameBean);
        } catch (SQL $e) {
            return 0;
        }
    }

    public function setNumber($game_id, $number)
    {
        $gameBean = R::load('games', $game_id);
        $gameBean->generated_number = $number;
        try {
            return R::store($gameBean);
        } catch (SQL $e) {
            return 0;
        }
    }

    public function delete()
    {
        $gameBean = R::findOne('games', ' id = (SELECT MAX(id) FROM games) ');
        return R::trash($gameBean);
    }
}
