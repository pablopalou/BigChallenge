<?php

namespace App\Http\Requests;

use App\Models\DoctorInformation;
use App\Models\Submission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class GetDoctorInformationRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var DoctorInformation $doctor */
        $doctor = $this->route('doctorInformation');
        $doctorId = $doctor->user_id;
        // I can see myself
        if (Auth::user()->id == $doctorId){
            return true;
        }
        // if i am a patient and a doctor took my submission, i can see the docotr information
        foreach (Auth::user()->submissionsMade as $submission){
            if ($submission->state != Submission::STATUS_PENDING && $submission->doctor_id == $doctorId) {
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
