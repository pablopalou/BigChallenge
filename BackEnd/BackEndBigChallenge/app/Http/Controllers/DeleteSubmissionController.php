<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteSubmissionRequest;
use App\Models\Submission;
use Illuminate\Http\JsonResponse;

class DeleteSubmissionController extends Controller
{
    public function __invoke(DeleteSubmissionRequest $request, Submission $submission): JsonResponse
    {
        $submission->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Submission deleted successfully',
        ]);
    }
}
