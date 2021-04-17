<?php

namespace App\Services;

use App\Models\Player;
use Illuminate\Database\Eloquent\Collection;

class PlayerService
{
    public function firstOrCreate(string $name): Player
    {
        return Player::firstOrCreate([
            'name' => $name,
        ]);
    }

    public function leaderboard(): Collection
    {
        return Player::orderBy('wins', 'desc')
            ->orderBy('games')
            ->limit(10)
            ->get();
    }
}
