<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSymptomsRequest;
use App\Http\Resources\SubmissionResource;
use App\Models\Submission;

class UpdateSubmissionController extends Controller
{
    public function __invoke(UpdateSymptomsRequest $request, Submission $submission):SubmissionResource
    {
        $submission->update($request->validated());

        return (new SubmissionResource($submission))->additional(['message' => 'Symptoms updated successfully']);
    }
}
