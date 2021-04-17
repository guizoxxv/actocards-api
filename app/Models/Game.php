<?php

namespace App\Models;

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
    ];

    protected static function booted()
    {
        static::created(function ($game) {
            $player = $game->player;

            $player->games = $player->games + 1;

            if ($game->win) {
                $player->wins = $player->wins + 1;
            }

            $player->save();
        });
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
