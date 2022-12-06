<?php

namespace App\Http\Controllers;

use App\DataTransferObject\TournamentData;
use App\Models\Tournament;
use App\Traits\ApiResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
}
