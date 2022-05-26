<?php

namespace App\Http\Requests;

use App\Models\Submission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class GetPrescriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Submission $submission */
        $submission = $this->route('submission');

        return $submission->status == Submission::STATUS_READY && (($submission->patient_id == Auth::user()->id) || ($submission->doctor_id == Auth::user()->id)) ;
    }

    public function rules(): array
    {
        return [
            //
        ];
    }
}
