<?php

namespace App\Http\Controllers;

use App\DataTransferObject\TournamentData;
use App\Models\Game;
use App\Models\Tournament;
use App\Traits\ApiResponses;
use Illuminate\Http\JsonResponse;

class TournamentController extends Controller
{
    use ApiResponses;

    /**
     * @param TournamentData $data
     * @return JsonResponse
     */
    public function create(TournamentData $data): JsonResponse
    {
        return $this->successfulResponse(Tournament::create($data->all()));
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->successfulResponse(Tournament::all());
    }

    /**
     * @param Tournament $tournament
     * @return JsonResponse
     */
    public function results(Tournament $tournament): JsonResponse
    {
        $final = Game::where('tournament_id', $tournament->id)->where('champion', 1)->first();
        return $this->successfulResponse([
            'tournament' => $tournament,
            'winner' => $final->winner,
        ]);
    }
}
