<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResendVerificationEmailController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->user()->sendEmailVerificationNotification();

        return response()->json(['status'=>200, 'message'=>'Verfication Email resended']);
    }
}
