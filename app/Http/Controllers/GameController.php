<?php

namespace App\Http\Controllers;

use App\Http\Requests\GamePlayRequest;
use App\Services\GameService;
use Illuminate\Http\JsonResponse;

class GameController extends Controller
{
    private GameService $gameService;

    public function __construct(GameService $gameService)
    {
        $this->gameService = $gameService;
    }

    public function play(GamePlayRequest $request): JsonResponse
    {
        $result = $this->gameService->play($request->all());

        return response()->json($result, 201);
    }
}
