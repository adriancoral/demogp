<?php

namespace App\Traits;

use App\DataTransferObject\GameData;
use App\Models\Game;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

trait GameOperations
{
    /**
     * @param GameData $data
     * @param int $fase
     */
    protected function createGame(GameData $data, int $fase = 1)
    {
        Game::create($data->additional([
            'status' =>  Game::getDefaultStateFor('status'),
            'fase' => $fase,
        ])->all());
    }

}
