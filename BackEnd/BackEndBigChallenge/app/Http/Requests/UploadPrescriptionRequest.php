<?php

namespace App\Http\Requests;

use App\Models\Submission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UploadPrescriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Submission $submission */
        $submission = $this->route('submission');

        return $submission->doctor_id == Auth::user()->id;
    }

    public function rules(): array
    {
        return [
            'prescriptions' => ['required', 'file', 'mimes:txt'],
        ];
    }
}
