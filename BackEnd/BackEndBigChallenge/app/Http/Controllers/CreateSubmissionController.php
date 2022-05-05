<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSumbissionRequest;
use App\Http\Resources\SubmissionResource;
use App\Models\Submission;

class CreateSubmissionController extends Controller
{
    public function __invoke(CreateSumbissionRequest $request): SubmissionResource
    {
        $arguments = $request->validated();
        $sumbission = Submission::create($arguments);
        return (new SubmissionResource($sumbission))->additional(['message' => 'Submission created successfully']);
    }
}
