<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testPlay(): void
    {
        $response = $this->postJson('/api/game/play', [
            'name' => 'player1',
            'cards' => '2,3,4',
        ]);

        $response->assertStatus(201);
    }

    public function testPlayRequired(): void
    {
        $response = $this->postJson('/api/game/play', []);

        $response->assertStatus(422);
    }

    public function testPlayNameAlphaDash(): void
    {
        $response = $this->postJson('/api/game/play', [
            'name' => 'player#1',
            'cards' => '2,3,4',
        ]);

        $response->assertStatus(422);
    }

    public function testPlayCardsMin(): void
    {
        $response = $this->postJson('/api/game/play', [
            'name' => 'player1',
            'cards' => '',
        ]);

        $response->assertStatus(422);
    }

    public function testPlayCardsMax(): void
    {
        $response = $this->postJson('/api/game/play', [
            'name' => 'player1',
            'cards' => '2,3,4,5,6,7,8,9,10,J,Q,K,A,1',
        ]);

        $response->assertStatus(422);
    }

    public function testPlayCardsDistinct(): void
    {
        $response = $this->postJson('/api/game/play', [
            'name' => 'player1',
            'cards' => '2,2',
        ]);

        $response->assertStatus(422);
    }

    public function testPlayCardsIn(): void
    {
        $response = $this->postJson('/api/game/play', [
            'name' => 'player1',
            'cards' => '1,2,3',
        ]);

        $response->assertStatus(422);
    }
}
