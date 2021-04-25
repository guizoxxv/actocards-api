<?php

namespace App\Models;

use App\Events\UpdateLeaderboard;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'player',
        'hands',
        'player_score',
        'computer_score',
        'win',
        'lose',
        'tie',
    ];

    protected static function booted()
    {
        static::created(function ($game) {
            $player = $game->player;

            $player->games = $player->games + 1;

            if ($game->win) {
                $player->wins = $player->wins + 1;
            }

            if ($game->lose) {
                $player->losses = $player->losses + 1;
            }

            if ($game->tie) {
                $player->ties = $player->ties + 1;
            }

            $player->save();

            event(resolve(UpdateLeaderboard::class));
        });
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
