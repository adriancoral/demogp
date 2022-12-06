<?php

namespace App\ModelStates\Tournament;

use App\Jobs\GameSimulation;
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

        GameSimulation::dispatch($this->tournament, 1);

        return $this->tournament;
    }
}
