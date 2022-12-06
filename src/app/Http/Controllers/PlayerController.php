<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Player;
use App\Traits\ApiResponses;
use Illuminate\Http\JsonResponse;

class PlayerController extends Controller
{
    use ApiResponses;

    /**
     * @param Player $player
     * @return JsonResponse
     */
    public function show(Player $player): JsonResponse
    {
        return $this->successfulResponse([
            'player' => $player,
            'score' => $this->score($player),
        ]);
    }

    /**
     * @param $player
     * @return array
     */
    private function score($player): array
    {
        $games =  Game::where('player_one_id', $player->id)
            ->orWhere('player_two_id', $player->id)->get();

        return [
            'games' => $games->count(),
            'win' => $games->filter(function ($game) use ($player) {
                return $game->winner_id == $player->id;
            })->count(),
            'champion' => $games->sum('champion'),
            'tournaments' => $games->unique('tournament_id')->count(),
        ];
    }


}
