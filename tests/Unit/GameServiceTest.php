<?php

namespace Tests\Unit;

use App\Models\Game;
use App\Services\GameService;
use App\Services\PlayerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class GameServiceTest extends TestCase
{
    use RefreshDatabase;

    private GameService $gameService;

    public function setUp(): void
    {
        parent::setUp();

        $this->gameService = app(GameService::class);
    }   

    public function test_play(): void
    {
        $result = $this->gameService->play([
            'name' => 'player1',
            'cards' => ['2', '3', '4'],
        ]);

        $this->assertInstanceOf(Game::class, $result);
    }

    public function test_result(): void
    {
         $mock = Mockery::mock(GameService::class, [new PlayerService])
            ->makePartial();

        $mock->shouldAllowMockingProtectedMethods()
            ->shouldReceive('generateComputerCards')
            ->once()
            ->andReturn(['2', '3', '4', '5']);

        $result = $mock->play([
            'name' => 'player1',
            'cards' => ['3', '4', '2', '5'],
        ]);

        $this->assertEquals(2, $result->player_score);
        $this->assertEquals(1, $result->computer_score);
        $this->assertEquals(true, $result->win);
        $this->assertEquals(false, $result->lose);
        $this->assertEquals(false, $result->tie);
    }
}
