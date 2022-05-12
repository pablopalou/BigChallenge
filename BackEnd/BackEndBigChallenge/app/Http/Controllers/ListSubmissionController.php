<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubmissionResourceCollection;
use App\Models\Submission;
use Illuminate\Http\Request;

class ListSubmissionController extends Controller
{
    public function __invoke(Request $request): SubmissionResourceCollection
    {
        // We filter only by state
        return new SubmissionResourceCollection(Submission::filter(request(['state']))->get());
    }
}
