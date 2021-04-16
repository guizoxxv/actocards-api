<?php

namespace App\Http\Controllers;

use App\Services\GameService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GameController extends Controller
{
    private GameService $gameService;

    public function __construct(GameService $gameService)
    {
        $this->gameService = $gameService;
    }

    public function play(Request $request): JsonResponse
    {
        if (is_string($request->cards)) {
            $request->merge([
                'cards' => explode(',', $request->cards),
            ]);
        }

        $result = $this->gameService->play($request->all());

        return response()->json($result, 201);
    }
}
