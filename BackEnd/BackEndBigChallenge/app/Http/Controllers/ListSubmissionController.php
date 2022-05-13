<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListSubmissionRequest;
use App\Http\Resources\SubmissionResourceCollection;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListSubmissionController extends Controller
{
    public function __invoke(Request $request): SubmissionResourceCollection
    {
        // state is a query param that is not mandatory and role is mandatory
        $user = User::find(Auth::user()->id);
        if ($user->hasRole('patient') || $request->get('role') == 'patient'){ 
            $submissions = Submission::patientListSubmissions()->filter(request(['state']))->get();
        } else {
            $submissions = Submission::doctorListSubmissions()->filter(request(['state']))->get();
        }
        return new SubmissionResourceCollection($submissions);
    }
}
