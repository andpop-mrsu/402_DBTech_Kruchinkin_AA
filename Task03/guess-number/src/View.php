<?php

namespace Alexzero00\GuessNumber\View;

use function cli\line;

function displayGame()
{
    line("Пожалуйста, введите число от 1 до " . MAX_VALUE . ", или введите ! для выхода из игры");
}

function displayWelcomeMessage()
{
    $welcomeLine = "Приветствуем вас в игре \"Угадай число\"!"
        . " Ваша задача - угадать число, которое загадал компьютер, в заданном диапазоне"
        . " за ограниченное количество попыток. Пока что результаты игры не сохраняются в базе данных.";
    line($welcomeLine);
}
