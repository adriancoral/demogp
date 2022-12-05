<?php

namespace App\Models;

use App\ModelStates\Tournament\TournamentState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\HasStates;

class Tournament extends Model
{
    use HasFactory;
    use HasStates;

    protected array $guarded = [];

    protected array $casts = [
        'status' => TournamentState::class,
    ];
}
