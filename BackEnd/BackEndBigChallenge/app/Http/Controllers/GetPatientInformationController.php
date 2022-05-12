<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetPatientInformationRequest;
use App\Http\Resources\PatientInformationResource;
use App\Models\PatientInformation;

class GetPatientInformationController extends Controller
{
    public function __invoke(GetPatientInformationRequest $request, PatientInformation $patientInformation): PatientInformationResource
    {
        return (new PatientInformationResource($patientInformation))->additional([
            'message' => 'Received Patient Information successfully',
            'name' => $patientInformation->user->name,
            'email' => $patientInformation->user->email,
        ]);
    }
}
