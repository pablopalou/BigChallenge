<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailVerificationRequest as RequestsEmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class VerifyEmailController extends Controller
{
    public function __invoke(RequestsEmailVerificationRequest $request): RedirectResponse
    {
        Auth::loginUsingId($request->route('id'));
        $request->fulfill();

        return redirect()->to('http://localhost:3000/login');
        // return response()->json(['status'=>'200', 'message'=> 'User email verified succesfully']);
    }
}
