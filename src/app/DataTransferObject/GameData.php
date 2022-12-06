<?php
namespace App\DataTransferObject;

use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class GameData  extends Data
{

    public function __construct(
        public int $tournament_id,
        public int $player_one_id,
        public int $player_two_id,
    ) {
    }

    public static function rules(): array
    {
        return [
            'tournament_id' => ['required'],
            'player_one_id' => ['required'],
            'player_two_id' => ['required'],
        ];
    }
}
