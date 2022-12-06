<?php

namespace App\ModelStates\Tournament;

class Playing extends TournamentState
{
    public static string $name = 'playing';

    public function value(): string
    {
        return self::$name;
    }
}
