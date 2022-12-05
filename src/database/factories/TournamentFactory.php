<?php

namespace Database\Factories;

use App\Models\Tournament;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tournament>
 */
class TournamentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->words(2, true),
            'association' =>  $this->faker->randomElement(['wta', 'atp']),
            'players_total' =>  $this->faker->randomElement([32, 16, 8]),
            'status' => Tournament::getDefaultStateFor('status'),
        ];
    }

    /**
     * @return TournamentFactory
     */
    public function male(): TournamentFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'association' => 'atp'
            ];
        });
    }

    /**
     * @return TournamentFactory
     */
    public function female(): TournamentFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'association' => 'wta'
            ];
        });
    }
}
