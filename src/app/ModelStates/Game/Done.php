<?php

namespace App\ModelStates\Game;

class Done extends GameState
{
    public static $name = 'done';

    public function value(): string
    {
        return self::$name;
    }
}
