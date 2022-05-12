<?php

namespace App\Http\Controllers;

use App\Http\Requests\TakeSubmissionRequest;
use App\Models\Submission;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class TakeSubmissionController extends Controller
{
    public function __invoke(TakeSubmissionRequest $request, Submission $submission): JsonResponse
    {
        $submission->update(['doctor_id' => Auth::user()->id, 'state' => Submission::STATUS_IN_PROGRESS]);

        return response()->json([
            'status' => 200,
            'message' => 'Doctor took the submission successfully',
        ]);
    }
}
