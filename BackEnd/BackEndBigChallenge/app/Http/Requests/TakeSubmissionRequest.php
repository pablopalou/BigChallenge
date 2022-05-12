<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TakeSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $submission = $this->route('submission');

        return !isset($submission->doctor_id);
    }

    public function rules(): array
    {
        return [
            //
        ];
    }
}
