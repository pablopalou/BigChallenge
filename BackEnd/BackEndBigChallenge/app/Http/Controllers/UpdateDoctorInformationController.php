<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateDoctorInformationRequest;
use App\Http\Resources\DoctorInformationResource;
use App\Models\DoctorInformation;
use Illuminate\Support\Facades\Auth;

class UpdateDoctorInformationController extends Controller
{
    public function __invoke(UpdateDoctorInformationRequest $request): DoctorInformationResource
    {
        $doctor = DoctorInformation::where('user_id', Auth::user()->id)->firstOrFail();
        $doctor->update($request->validated());

        return (new DoctorInformationResource($doctor))->additional(['message' => 'Doctor information updated successfully']);
    }
}
