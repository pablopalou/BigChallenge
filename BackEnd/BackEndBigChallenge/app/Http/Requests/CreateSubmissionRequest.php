<?php

namespace App\Http\Requests;

use App\Models\Submission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateSubmissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'state' => ['required', Rule::in([Submission::STATUS_PENDING, Submission::STATUS_IN_PROGRESS, Submission::STATUS_READY])],
            'symptoms' => ['required'],
            'prescriptions' => ['nullable', 'mimes:txt'],
            'doctor_id' => ['nullable', Rule::exists('doctor_information', 'user_id')],
        ];
    }
}
