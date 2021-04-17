<?php

namespace Tests\Unit;

use App\Models\Game;
use App\Services\GameService;
use App\Services\PlayerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
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

    public function test_play_name_alpha_dash(): void
    {
        $this->expectException(ValidationException::class);

        $this->gameService->play([
            'name' => 'player#1',
            'cards' => ['2', '3', '4'],
        ]);
    }

    public function test_play_computer_cards_length(): void
    {
        $result = $this->gameService->play([
            'name' => 'player1',
            'cards' => ['2', '3', '4'],
        ]);

        $hands = json_decode($result->hands);

        $this->assertEquals(3, count($hands->computer));
    }

    public function test_play_score(): void
    {
         $mock = Mockery::mock(GameService::class, [new PlayerService])
            ->makePartial();

        $mock->shouldAllowMockingProtectedMethods()
            ->shouldReceive('generateComputerCards')
            ->once()
            ->andReturn(['2', '3', '4']);

        $result = $mock->play([
            'name' => 'player1',
            'cards' => ['3', '4', '2'],
        ]);

        $this->assertEquals(2, $result->player_score);
        $this->assertEquals(1, $result->computer_score);
    }

    public function test_play_cards_required(): void
    {
        $this->expectException(ValidationException::class);

        $this->gameService->play([
            'name' => 'player1',
        ]);
    }

    public function test_play_cards_max(): void
    {
        $this->expectException(ValidationException::class);

        $this->gameService->play([
            'name' => 'player1',
            'cards' => [
                ...$this->gameService->getAvailableCards(),
                '1',
            ],
        ]);
    }

    public function test_play_cards_distinct(): void
    {
        $this->expectException(ValidationException::class);

        $this->gameService->play([
            'name' => 'player1',
            'cards' => ['2', '2'],
        ]);
    }

    public function test_play_cards_in(): void
    {
        $this->expectException(ValidationException::class);

        $this->gameService->play([
            'name' => 'player1',
            'cards' => ['1'],
        ]);
    }
}
