<?php

namespace App\ModelStates\Tournament;

class Enrollment extends TournamentState
{
    public static string $name = 'enrollment';

    public function value(): string
    {
        return self::$name;
    }
}
