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

        $errors = $response->json()['errors'];

        $this->assertArrayHasKey('name', $errors);
        $this->assertArrayHasKey('cards', $errors);
    }

    public function testPlayNameAlphaDash(): void
    {
        $response = $this->postJson('/api/game/play', [
            'name' => 'player#1',
            'cards' => '2,3,4',
        ]);

        $response->assertStatus(422);

        $errors = $response->json()['errors'];

        $this->assertArrayHasKey('name', $errors);
    }

    public function testPlayCardsMin(): void
    {
        $response = $this->postJson('/api/game/play', [
            'name' => 'player1',
            'cards' => '',
        ]);

        $response->assertStatus(422);

        $errors = $response->json()['errors'];

        $this->assertArrayHasKey('cards', $errors);
    }

    public function testPlayCardsMax(): void
    {
        $response = $this->postJson('/api/game/play', [
            'name' => 'player1',
            'cards' => '2,3,4,5,6,7,8,9,10,J,Q,K,A,1',
        ]);

        $response->assertStatus(422);

        $errors = $response->json()['errors'];

        $this->assertArrayHasKey('cards', $errors);
    }

    public function testPlayCardsDistinct(): void
    {
        $response = $this->postJson('/api/game/play', [
            'name' => 'player1',
            'cards' => '2,2',
        ]);

        $response->assertStatus(422);

        $errors = $response->json()['errors'];

        $this->assertArrayHasKey('cards', $errors);
    }

    public function testPlayCardsIn(): void
    {
        $response = $this->postJson('/api/game/play', [
            'name' => 'player1',
            'cards' => '1,2,3',
        ]);

        $response->assertStatus(422);

        $errors = $response->json()['errors'];

        $this->assertArrayHasKey('cards', $errors);
    }
}
