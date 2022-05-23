<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetDoctorInformationRequest;
use App\Http\Resources\DoctorInformationResource;
use App\Models\DoctorInformation;

class GetDoctorInformationController extends Controller
{
    public function __invoke(GetDoctorInformationRequest $request, DoctorInformation $doctorInformation): DoctorInformationResource
    {
        return (new DoctorInformationResource($doctorInformation))->additional([
            'message' => 'Received Doctor Information successfully',
            'name' => $doctorInformation->user->name,
            'email' => $doctorInformation->user->email,
        ]);
    }
}
