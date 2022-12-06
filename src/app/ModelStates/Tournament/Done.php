<?php

namespace App\ModelStates\Tournament;

class Done extends TournamentState
{
    public static string $name = 'done';

    public function value(): string
    {
        return self::$name;
    }
}
