<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlayerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testLeaderboard(): void
    {
        $this->seed();
        
        $response = $this->getJson('/api/player/leaderboard');

        $response->assertStatus(200);
    }
}
