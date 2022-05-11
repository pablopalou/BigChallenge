<?php

namespace App\Http\Requests;

use App\Models\PatientInformation;
use App\Models\Submission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class GetPatientInformationRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var PatientInformation $patient */
        $patient = $this->route('patientInformation');
        $patientId = $patient->user_id;
        // I can see myself
        if (Auth::user()->id == $patientId) {
            return true;
        }
        // if i am a doctor i can see MY patients information
        foreach (Auth::user()->submissionsTaken as $submission) {
            if ($submission->patient_id == $patientId) {
                return true;
            }
        }
        // otherwise
        return false;
    }

    public function rules(): array
    {
        return [
            //
        ];
    }
}
