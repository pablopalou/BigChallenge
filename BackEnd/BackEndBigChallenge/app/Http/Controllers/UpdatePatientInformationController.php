<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePatientInformationRequest;
use App\Http\Resources\PatientInformationResource;
use App\Models\PatientInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpdatePatientInformationController extends Controller
{
    public function __invoke(UpdatePatientInformationRequest $request):PatientInformationResource
    {
        $patient = PatientInformation::where('user_id', Auth::user()->id)->firstOrFail();
        $patient->update($request->validated());
        return (new PatientInformationResource($patient))->additional(['message' => 'Patient information updated successfully']);
    }
}
