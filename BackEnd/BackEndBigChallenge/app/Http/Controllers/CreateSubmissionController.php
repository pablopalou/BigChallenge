<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSubmissionRequest;
use App\Http\Resources\SubmissionResource;
use App\Models\Submission;

class CreateSubmissionController extends Controller
{
    public function __invoke(CreateSubmissionRequest $request): SubmissionResource
    {
        $arguments = $request->validated();
        $submission = Submission::create($arguments);
        return (new SubmissionResource($submission))->additional(['message' => 'Submission created successfully']);
    }
}
