<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePatientInformationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'gender' => ['required', Rule::in(['male', 'female', 'other'])],
            'height' => ['required', 'numeric', 'max:230', 'min:30'],
            'weight' => ['required', 'numeric', 'max:300', 'min:1'],
            'birth' => ['required', 'date ', ' before:today'],
        ];
    }
}
