<?php
namespace App\DataTransferObject;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class TournamentData  extends Data
{

    public function __construct(
        public string $name,
        public string $association,
        public int $players_total,
    ) {
    }

    public static function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'association' => ['required', 'string', Rule::in(['wta', 'atp'])],
            'players_total' => ['required', 'numeric', Rule::in([32, 16, 8])],
        ];
    }
}
