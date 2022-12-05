<?php

namespace App\ModelStates\Tournament;

class Pending extends TournamentState
{
    public static $name = 'pending';

    public function value(): string
    {
        return self::$name;
    }
}
