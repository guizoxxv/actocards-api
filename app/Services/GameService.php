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
        $computerCards = $this->generateComputerCards(count($data['cards']));

        return $this->saveGame($data['name'], $data['cards'], $computerCards);
    }

    protected function generateComputerCards(int $size): array
    {
        // TODO: Use average to generate a comparable computer hand
        
        $currentAvailableCards = array_slice(config('constants.cards'), -$size);

        shuffle($currentAvailableCards);

        return $currentAvailableCards;
    }

    private function saveGame(string $player, array $playerCards, array $computerCards): Game
    {
        $playerScore = $this->calculateScore($playerCards, $computerCards);
        
        $computerScore = count($playerCards) - $playerScore;

        $player = $this->playerService->firstOrCreate($player);

        $game = new Game;

        $game->player_id = $player->id;
        $game->player_score = $playerScore;
        $game->computer_score = $computerScore;
        $game->win = $playerScore > $computerScore;
        $game->hands = json_encode([
            'player' => $playerCards,
            'computer' => $computerCards,
        ]);

        $game->save();

        return $game;   
    }

    private function calculateScore(array $playerCards, array $computerCards): int
    {
        $score = 0;
        
        foreach($playerCards as $index => $card) {
            if ($this->getCardValue($card) > $this->getCardValue($computerCards[$index])) {
                $score++;
            }
        }

        return $score;
    }

    private function getCardValue(string $card): int {
        return array_search($card, config('constants.cards'), true);
    }
}
