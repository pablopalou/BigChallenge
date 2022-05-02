<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;

class VerifyEmailController extends Controller
{
    public function __invoke(EmailVerificationRequest $request): JsonResponse
    {
        $request->fulfill();
        return response()->json(['status'=>'200', 'message'=> 'User email verified succesfully']);
    }
}
