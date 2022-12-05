<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\Player;
use App\Models\Tournament;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'tournament_id' => Tournament::factory(),
            'player_one_id' => Player::factory()->male(),
            'player_two_id' =>  Player::factory()->male(),
            'status' => Game::getDefaultStateFor('status'),
            /*'winner_id'	bigint(20) unsigned NULL
            'results'	json NULL
            'fase'	smallint(5) unsigned
            'champion'*/
        ];
    }

    public function tournament(Tournament $id): GameFactory
    {
        return $this->state(function (array $attributes) use ($id) {
            return [
                'tournament_id' => $id,
            ];
        });
    }
}
