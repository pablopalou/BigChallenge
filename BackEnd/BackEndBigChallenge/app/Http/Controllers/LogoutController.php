<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function __invoke():JsonResponse
    {
        // QUESTION
        // Why   Auth::user()->tokens()->delete(); is no working anymore? It says: 'tokens() is not a function'
        Auth::user()->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return response()->json([
            'status' => 200,
            'message' => 'User logged out succesfully',
        ]);
    }
}
