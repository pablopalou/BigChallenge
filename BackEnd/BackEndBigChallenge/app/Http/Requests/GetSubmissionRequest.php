<?php

namespace App\Http\Requests;

use App\Models\Submission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class GetSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Submission $submission */
        $submission = $this->route('submission');
        return (Auth::user()->id == $submission->patient_id || ($submission->state != Submission::STATUS_PENDING && $submission->doctor_id == Auth::user()->id));
    }

    public function rules(): array
    {
        return [
            //
        ];
    }
}
