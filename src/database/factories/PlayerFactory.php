<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class PlayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $association = $this->faker->randomElement(['wta', 'atp']);
        $gender = ($association === 'atp')? 'male' : 'female';
        return [
            'name' => $this->faker->name($gender),
            'handicap' => $this->faker->numberBetween(0, 100),
            'association' => $association,
            'features' => $this->playerFeatures($gender)
        ];
    }

    private function playerFeatures($gender): array
    {
        if ($gender == 'male') {
            return [
                'strength' => $this->faker->numberBetween(0, 100),
                'speed' => $this->faker->numberBetween(0, 100),
            ];
        }

        return [
            'reaction' => $this->faker->numberBetween(0, 100),
        ];

    }

    /**
     * @param string|null $name
     * @return PlayerFactory
     */
    public function male(?string $name): PlayerFactory
    {
        return $this->state(function (array $attributes) use ($name) {
            return [
                'name' => $name ?? $this->faker->name('male'),
                'association' => 'atp',
                'features' => $this->playerFeatures('male'),
            ];
        });
    }

    /**
     * @param string|null $name
     * @return PlayerFactory
     */
    public function female(?string $name): PlayerFactory
    {
        return $this->state(function (array $attributes) use ($name) {
            return [
                'name' => $name ?? $this->faker->name('female'),
                'association' => 'wta',
                'features' => $this->playerFeatures('female'),
            ];
        });
    }

    /**
     * @param string $gender
     * @return PlayerFactory
     */
    public function gender(string $gender): PlayerFactory
    {
        return $this->state(function (array $attributes) use ($gender) {
            return [
                'name' => $this->faker->name($gender),
                'association' => ($gender === 'male')? 'wta' : 'atp',
            ];
        });
    }

    /**
     * @param int|null $handicap
     * @return PlayerFactory
     */
    public function handicap(?int $handicap): PlayerFactory
    {
        return $this->state(function (array $attributes) use ($handicap) {
            return [
                'handicap' => $handicap ?? $this->faker->numberBetween(0, 100),
            ];
        });
    }
}
