<?php

namespace App\Http\Controllers;

use App\Services\PlayerService;
use Illuminate\Http\JsonResponse;

class PlayerController extends Controller
{
    private PlayerService $playerService;

    public function __construct(PlayerService $playerService)
    {
        $this->playerService = $playerService;
    }

    public function leaderboard(): JsonResponse
    {
        $result = $this->playerService->leaderboard();
        
        return response()->json($result);
    }
}
