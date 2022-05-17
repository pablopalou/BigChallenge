<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubmissionResourceCollection;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListSubmissionController
{
    public function __invoke(Request $request): SubmissionResourceCollection
    {
        // state is a query param that is not mandatory and role is mandatory
        $user = User::find(Auth::user()->id);
        if ($user->hasRole('doctor') && $request->get('role') == 'doctor') {
            $submissions = Submission::doctorListSubmissions()->filter(request(['state']))->get();
        } else {
            $submissions = Submission::patientListSubmissions()->filter(request(['state']))->get();
        }

        return new SubmissionResourceCollection($submissions);
    }
}
