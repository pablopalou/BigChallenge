<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(RegisterRequest $request)
    {
        $args = $request->validated();
        $user = User::create($args);
        // DOUBT
        // I saw on the internet that i should dispatch an event in this way. But... what is the purpose of doing this? What will do this line?
        event(new Registered($user));

        return response()->json([
            'status' => 200,
            'message' => 'User registered succesfully',
        ]);
    }
}
