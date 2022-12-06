<?php

namespace App\Jobs;

use App\DataTransferObject\GameData;
use App\Models\Game;
use App\Models\Player;
use App\Models\Tournament;
use App\ModelStates\Game\Done;
use App\Traits\GameOperations;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class GameSimulation //implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use GameOperations;

    private Tournament $tournament;
    private int $fase;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Tournament $tournament, int $fase)
    {
        $this->tournament = $tournament;
        $this->fase = $fase;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $games = Game::where('tournament_id', $this->tournament->id)->where('fase', $this->fase)
            ->where('status', 'pending')
            ->get();

        $games->each(function ($game) {
            $this->execGame($game);
        });

        if ($games->count() == 1) {
            $games[0]->update(['champion' => 1]);
        } else {
            $this->nextFase();
        }
    }

    private function nextFase()
    {
        Game::where('tournament_id', $this->tournament->id)
            ->where('status', 'done')
            ->where('fase', $this->fase)
            ->inRandomOrder()
            ->get()->chunk(2)->each(function ($playersPair) {
                $this->createGame(GameData::from([
                    'tournament_id' => $this->tournament->id,
                    'player_one_id' => $playersPair->first()->winner_id,
                    'player_two_id' => $playersPair->last()->winner_id,
                ]), $this->fase + 1);
            });
        self::dispatch($this->tournament, $this->fase + 1);
    }

    /**
     * @param Game $game
     */
    private function execGame(Game $game)
    {
        $results = $this->simulateGame($game->playerOne, $game->playerTwo);
        $game->update([
            'winner_id' => $results['winner_id'],
            'results' => $results['results']
        ]);

       $game->status->transitionTo(Done::class);
    }

    /**
     * @param Player $one
     * @param Player $two
     * @return array
     */
    private function simulateGame(Player $one, Player $two): array
    {
        $playerOne = $this->weightedAverage($one);
        $playerTwo = $this->weightedAverage($two);

        if ($playerOne['total'] !== $playerTwo['total']) {
            return [
                'results' => [$playerOne->all(), $playerTwo->all()],
                'winner_id' => ($playerOne['total'] > $playerTwo['total']) ? $playerOne['id'] : $playerTwo['id']
            ];
        }

        return $this->simulateGame($one, $two);
    }

    /**
     * @param Player $player
     * @return Collection
     */
    private function weightedAverage(Player $player): Collection
    {
        if ($player->association == 'atp') {
            $score = collect([
                'strength' => $player->features['strength'] * 0.20,
                'speed' => $player->features['speed'] * 0.30,
                'handicap' => $player->handicap * 0.40,
                'luck' => rand(1, 100) * 0.10,
            ]);
        } else {
            $score = collect([
                'reaction' => $player->features['reaction'] * 0.30,
                'handicap' => $player->handicap * 0.50,
                'luck' => rand(1, 100) * 0.20,
            ]);
        }

        $scoreTotal = $score->values()->sum();

        return $score->merge([
            'total' => $scoreTotal,
            'id' => $player->id
        ]);
    }

}
