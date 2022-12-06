<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\Player;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class TournamentTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();

    }

    /** @test */
    public function a_tournament_can_be_created()
    {
        Player::factory()->count(32)->male(null)->create();

        $data = [
            'name' => 'ATP 16',
            'association' => 'atp',
            'players_total' => 16,
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->json('POST', route('tournament.create'), $data, ['Accept' => 'application/json'])
            ->assertOk()
            ->assertJsonStructure([
                "success",
                "payload"
            ]);

        $this->assertCount(1, Tournament::all());

        $response->assertJson(function (AssertableJson $json) use ($data) {
            return $json->has('payload.id')
                ->where('payload.name', $data['name'])
                ->where('payload.association', $data['association'])
                ->where('payload.players_total', $data['players_total'])
                ->where('payload.status', 'playing')
                ->etc();
            }
        );
    }
}
