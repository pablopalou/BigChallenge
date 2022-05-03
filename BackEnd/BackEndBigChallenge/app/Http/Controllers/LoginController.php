<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request): JsonResponse | ValidationException
    {
        $arguments = $request->validated();
        $user = User::where('email', $arguments['email'])->first();
        if (!$user || !Hash::check($arguments['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // I also proved this but is not working
        // $user = Auth::getProvider()->retrieveByCredentials(['email'=>$request['email'], 'password'=>$request['password'] ]);
        // Auth::login($user);

        return response()->json([
            'status' => 200,
            'message' => 'User logged succesfully',
            'token' => $user->createToken('token')->plainTextToken,
        ]);
    }
}
