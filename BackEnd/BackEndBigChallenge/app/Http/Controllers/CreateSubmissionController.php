<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSubmissionRequest;
use App\Http\Resources\SubmissionResource;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;

class CreateSubmissionController extends Controller
{
    public function __invoke(CreateSubmissionRequest $request): SubmissionResource
    {
        $arguments = $request->validated();
        $arguments['patient_id'] = Auth::user()->id;
        $submission = Submission::create($arguments);

        return (new SubmissionResource($submission))->additional(['message' => 'Submission created successfully']);
    }
}
