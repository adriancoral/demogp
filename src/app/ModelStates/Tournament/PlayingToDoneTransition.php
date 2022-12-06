<?php

namespace App\ModelStates\Tournament;

use App\Models\Tournament;
use Spatie\ModelStates\Transition;

class PlayingToDoneTransition extends Transition
{
    private Tournament $tournament;

    public function __construct(Tournament $tournament)
    {
        $this->tournament = $tournament;
    }

    public function handle(): Tournament
    {
        $this->tournament->status = new Done($this->tournament);
        $this->tournament->save();

        /// Dispatch evento de recálculo de handicap para todos los players del torneo

        return $this->tournament;
    }
}
