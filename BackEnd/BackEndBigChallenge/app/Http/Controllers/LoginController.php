<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
class LoginController extends Controller
{
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $arguments = $request->validated();
        $user = User::where('email', $arguments['email'])->first();
        if (!$user || !Hash::check($arguments['password'], $user->password)) {
            return response()->json([
                'status' => 401,
                'message' => 'Invalid credentials',
            ]);
        }

        // DOUBT
        // i have the doubt about if i can choose any name for the token. What does it means? What is the purpose of giving the token a name?
        return response()->json([
            'status' => 200,
            'message' => 'User logged succesfully',
            'token' => $user->createToken('token')->plainTextToken,
        ]);
    }
}
