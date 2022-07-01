<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetPrescriptionRequest;
use App\Models\Submission;
use Illuminate\Support\Facades\Storage;

class GetPrescriptionController extends Controller
{
    public function __invoke(GetPrescriptionRequest $request, Submission $submission)
    {
        return response()->json([
            'status' => 200,
            'url' => $submission->prescriptions,
        ]);
    }
}
