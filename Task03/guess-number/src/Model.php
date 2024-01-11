<?php

namespace Alexzero00\GuessNumber\Model;

function generateNumber()
{
    return mt_rand(1, MAX_VALUE);
}
