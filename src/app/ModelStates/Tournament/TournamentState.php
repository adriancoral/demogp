<?php
namespace App\ModelStates\Tournament;

use Spatie\ModelStates\Exceptions\InvalidConfig;
use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class TournamentState extends State
{
    abstract public function value(): string;

    /**
     * @throws InvalidConfig
     */
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Pending::class)
            ->allowTransition(Pending::class, Processing::class)
            ->allowTransition(Processing::class, Done::class, ProcessingToDoneTransition::class);

    }
}
