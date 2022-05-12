<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetSubmissionRequest;
use App\Http\Resources\SubmissionResource;
use App\Models\Submission;

class GetSubmissionController extends Controller
{
    public function __invoke(GetSubmissionRequest $request, Submission $submission): SubmissionResource
    {
        return (new SubmissionResource($submission))->additional(['message' => 'Received Submission Successfully']);
    }
}
