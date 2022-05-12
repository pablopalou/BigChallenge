<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class DeleteSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $submission = $this->route('submission');
        return $submission->patient_id == Auth::user()->id;
    }

    public function rules(): array
    {
        return [
            //
        ];
    }
}
