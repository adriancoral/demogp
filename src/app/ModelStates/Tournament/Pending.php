<?php

namespace App\ModelStates\Tournament;

class Pending extends TournamentState
{
    public static string $name = 'pending';

    public function value(): string
    {
        return self::$name;
    }
}
