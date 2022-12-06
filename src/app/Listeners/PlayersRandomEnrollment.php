<?php

namespace App\Listeners;

use App\DataTransferObject\GameData;
use App\Events\TournamentCreated;
use App\Models\Game;
use App\Models\Player;
use App\ModelStates\Tournament\Enrollment;
use App\ModelStates\Tournament\Playing;
use App\Traits\GameOperations;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PlayersRandomEnrollment
{
    use GameOperations;
    /**
     * Handle the event.
     *
     * @param  TournamentCreated  $event
     * @return void
     */
    public function handle(TournamentCreated $event)
    {
        $tournament = $event->tournament();

        $tournament->status->transitionTo(Enrollment::class);

        Player::where('association', $tournament->association)->inRandomOrder()
            ->limit($tournament->players_total)->get()->chunk(2)
            ->each(function ($playersPair) use ($tournament) {
                $this->createGame(GameData::from([
                    'tournament_id' => $tournament->id,
                    'player_one_id' => $playersPair->first()->id,
                    'player_two_id' => $playersPair->last()->id,
                ]), 1);
        });

        $tournament->status->transitionTo(Playing::class);

    }
}
