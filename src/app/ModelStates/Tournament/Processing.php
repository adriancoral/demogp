<?php

namespace App\ModelStates\Tournament;

class Processing extends TournamentState
{
    public static $name = 'processing';

    public function value(): string
    {
        return self::$name;
    }
}
