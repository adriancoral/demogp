<?php

namespace App\ModelStates\Tournament;

use App\Models\Tournament;
use Spatie\ModelStates\Transition;

class EnrollmentToPlayingTransition extends Transition
{
    private Tournament $tournament;

    public function __construct(Tournament $tournament)
    {
        $this->tournament = $tournament;
    }

    public function handle(): Tournament
    {
        $this->tournament->status = new Playing($this->tournament);
        $this->tournament->save();

        /// Dispatch evento de calculo de juegos

        return $this->tournament;
    }
}
