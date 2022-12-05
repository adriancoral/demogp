<?php

namespace App\ModelStates\Game;

class Pending extends GameState
{
    public static $name = 'pending';

    public function value(): string
    {
        return self::$name;
    }
}
