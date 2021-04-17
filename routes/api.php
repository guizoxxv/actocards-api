<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\PlayerController;

Route::post('/game/play', [GameController::class, 'play']);

Route::get('/player/leaderboard', [PlayerController::class, 'leaderboard']);
