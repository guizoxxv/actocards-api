<?php

namespace App\Services;

use App\Models\Game;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class GameService
{
    private array $availableCards = [
        '2',
        '3',
        '4',
        '5',
        '6',
        '7',
        '8',
        '9',
        '10',
        'J',
        'Q',
        'K',
        'A',
    ];

    public function getAvailableCards(): array
    {
        return $this->availableCards;
    }

    public function play(array $data): Game
    {
        $validator = Validator::make(
            $data,
            [
                'name' => 'required|alpha_dash|max:10',
                'cards' => 'required|array|min:1,max:13',
                'cards.*' => [
                    'required',
                    'distinct:strict',
                    Rule::in($this->availableCards),
                ],
            ],
            [
                'cards.array' => 'Invalid input',
            ],
        );

        if ($validator->fails()) {
            // throw new ValidationException($validator);
            $errorMsgs = collect($validator->errors()->toArray())->mapWithKeys(function($item, $key) {
                $key = explode('.', $key)[0];
                return [$key => $item];
            })->toArray();

            throw ValidationException::withMessages($errorMsgs);
        }

        $computerCards = $this->generateComputerCards(count($data['cards']));

        return $this->saveGame($data['name'], $data['cards'], $computerCards);
    }

    protected function generateComputerCards(int $size): array
    {
        // TODO: Use average to generate a comparable computer hand
        
        $currentAvailableCards = array_slice($this->availableCards, -$size);

        shuffle($currentAvailableCards);

        return $currentAvailableCards;
    }

    private function saveGame(string $player, array $playerCards, array $computerCards): Game
    {
        $playerScore = $this->calculateScore($playerCards, $computerCards);
        
        $computerScore = count($playerCards) - $playerScore;

        $game = new Game;

        $game->player = $player;
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
        return array_search($card, $this->availableCards, true);
    }
}
