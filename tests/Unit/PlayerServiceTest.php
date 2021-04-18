<?php

namespace Tests\Unit;

use App\Models\Player;
use App\Services\PlayerService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlayerServiceTest extends TestCase
{
    use RefreshDatabase;

    private PlayerService $playerService;

    public function setUp(): void
    {
        parent::setUp();

        $this->playerService = app(PlayerService::class);
    }

    public function test_first_or_create(): void
    {
        $result = $this->playerService->firstOrCreate('player1');

        $this->assertInstanceOf(Player::class, $result);
    }

    public function test_leaderboard(): void
    {
        $this->seed();

        $result = $this->playerService->leaderboard();

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertEquals(3, $result->count());
    }
}
