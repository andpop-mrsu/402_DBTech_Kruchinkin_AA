<?php

namespace Alexzero00\GuessNumber\View;

use RedBeanPHP\R;

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
    } catch (\Exception $e) {
        return 2;
    }
}

function displayGames($player_name)
{
    $games = R::find('games', ' player_name = ? ', [$player_name]);
    foreach ($games as $game) {
        line("Игра {$game['id']}: 
        Дата - {$game['date']}, 
        Игрок - {$game['player_name']}, 
        Максимальное число - {$game['max_number']}, 
        Загаданное число - {$game['generated_number']}, 
        Исход - {$game['outcome']}");
    }
}

function displayWins($player_name)
{
    $wins = R::find('games', ' player_name = ? AND outcome = "win" ', [$player_name]);
    foreach ($wins as $win) {
        line("Выигрышная игра {$win['id']}:
         Дата - {$win['date']},
         Игрок - {$win['player_name']}, 
         Максимальное число - {$win['max_number']}, 
         Загаданное число - {$win['generated_number']}");
    }
}

function displayLosses($player_name)
{
    $losses = R::find('games', ' player_name = ? AND outcome = "loss" ', [$player_name]);
    foreach ($losses as $loss) {
        line("Проигрышная игра {$loss['id']}:
         Дата - {$loss['date']},
         Игрок - {$loss['player_name']}, 
         Максимальное число - {$loss['max_number']}, 
         Загаданное число - {$loss['generated_number']}");
    }
}

function displayPlayerStats($player_name)
{
    $wins = count(R::find('games', ' player_name = ? AND outcome = "win" ', [$player_name]));
    $losses = count(R::find('games', ' player_name = ? AND outcome = "loss" ', [$player_name]));
    line("Статистика игрока '$player_name':
     Выигрыши - {$wins}, 
     Проигрыши - {$losses}");
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
    } catch (\Exception $e) {
        return 2;
    }
}
