<?php

namespace App\Services;

use App\Models\Player;

class PlayerService
{
    public function firstOrCreate(string $name): Player
    {
        return Player::firstOrCreate([
            'name' => $name,
        ]);
    }

    public function leaderboard(): array
    {
        return [];
    }
}
