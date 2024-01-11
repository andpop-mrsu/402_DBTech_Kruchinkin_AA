<?php

namespace Alexzero00\GuessNumber\View;

use Exception;
use Alexzero00\GuessNumber\Database;

use function cli\line;
use function cli\input;

function displayWelcomeMessage()
{
    line("Добро пожаловать в игру 'Угадай число'!");
}

function displayGame()
{
    line(file_get_contents(__DIR__ . '\README.md'));
}

function getPlayerName()
{
    line("Пожалуйста, введите ваше имя:");
    try {
        return input();
    } catch (Exception $e) {
        return 2;
    }
}

function displayGames($player_name)
{
    $database = new Database();
    $games = $database->getGames($player_name);
    foreach ($games as $game) {
        line("Игра {$game['id']}: Дата - {$game['date']}, 
             Игрок - {$game['player_name']}, Максимальное число 
             - {$game['max_number']}, Загаданное число - {$game['generated_number']}, 
             Исход - {$game['outcome']}");
    }
}

function displayWins($player_name)
{
    $database = new Database();
    $wins = $database->getWins($player_name);
    foreach ($wins as $win) {
        line("Выигрышная игра {$win['id']}: Дата - {$win['date']},
         Игрок - {$win['player_name']}, Максимальное число - {$win['max_number']}, 
         Загаданное число - {$win['generated_number']}");
    }
}

function displayLosses($player_name)
{
    $database = new Database();
    $losses = $database->getLosses($player_name);
    foreach ($losses as $loss) {
        line("Проигрышная игра {$loss['id']}: Дата - {$loss['date']},
         Игрок - {$loss['player_name']}, Максимальное число - {$loss['max_number']}, 
         Загаданное число - {$loss['generated_number']}");
    }
}

function displayPlayerStats($player_name)
{
    $database = new Database();
    $playerStats = $database->getPlayerStats($player_name);
    line("Статистика игрока '$player_name': Выигрыши - {$playerStats['wins']}, Проигрыши - {$playerStats['losses']}");
}

function displayMenu()
{
    line("1. Начать игру");
    line("2. Показать все игры");
    line("3. Показать выигрышные игры");
    line("4. Показать проигрышные игры");
    line("5. Показать статистику игрока");
    line("6. Правила игры");
    line("7. Изменить максимальное число");
    line("8. Изменить количество попыток");
    line("9. Выйти из игры");
    line("Пожалуйста, выберите пункт меню:");
    try {
        return input();
    } catch (Exception $e) {
        return 2;
    }
}
