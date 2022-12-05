<?php

namespace App\Models;

use App\ModelStates\Game\GameState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\HasStates;

class Game extends Model
{
    use HasFactory;
    use HasStates;

    protected array $guarded = [];

    protected array $casts = [
        'status' => GameState::class,
    ];
}
