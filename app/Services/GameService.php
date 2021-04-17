<?php

namespace App\Services;

use App\Models\Game;

class GameService
{
    private PlayerService $playerService;

    public function __construct(PlayerService $playerService)
    {
        $this->playerService = $playerService;
    }

    public function play(array $data): Game
    {
        $playerCards = $data['cards'];
        $computerCards = $this->generateComputerCards(count($playerCards));

        $scores = $this->calculateScore($playerCards, $computerCards);

        $playerScore = $scores['playerScore'];
        $computerScore = $scores['computerScore'];

        $status = $this->checkStatus($playerScore, $computerScore);

        return $this->saveGame(
            $data['name'],
            $playerCards,
            $computerCards,
            $playerScore,
            $computerScore,
            $status
        );
    }

    protected function generateComputerCards(int $size): array
    {
        // TODO: Use average to generate a comparable computer hand
        
        $currentAvailableCards = array_slice(config('constants.cards'), -$size);

        shuffle($currentAvailableCards);

        return $currentAvailableCards;
    }

    private function saveGame(
        string $player,
        array $playerCards,
        array $computerCards,
        int $playerScore,
        int $computerScore,
        string $status,
    ): Game
    {   
        $player = $this->playerService->firstOrCreate($player);

        $game = new Game;

        $game->player_id = $player->id;
        $game->player_score = $playerScore;
        $game->computer_score = $computerScore;
        $game[$status] = true;
        $game->hands = json_encode([
            'player' => $playerCards,
            'computer' => $computerCards,
        ]);

        $game->save();

        return $game->refresh();   
    }

    private function calculateScore(array $playerCards, array $computerCards): array
    {
        $playerScore = 0;
        $computerScore = 0;
        
        foreach($playerCards as $index => $card) {
            $playerCardValue = $this->getCardValue($card);
            $computerCardValue = $this->getCardValue($computerCards[$index]);

            if ($playerCardValue > $computerCardValue) {
                $playerScore++;
            }
            
            if ($playerCardValue < $computerCardValue) {
                $computerScore++;
            }
        }

        return [
            'playerScore' => $playerScore,
            'computerScore' => $computerScore,
        ];
    }

    private function getCardValue(string $card): int
    {
        return array_search($card, config('constants.cards'), true);
    }

    private function checkStatus(int $playerScore, int $computerScore): string
    {
        if ($playerScore > $computerScore) {
            return 'win';
        }

        if ($playerScore < $computerScore) {
            return 'lose';
        }

        return 'tie';
    }
}
