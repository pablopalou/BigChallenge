<?php

namespace App\Http\Requests;

use App\Models\Submission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateSymptomsRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Submission $submission */
        $submission = $this->route('submission');

        return $submission->patient_id == Auth::user()->id && $submission->state == Submission::STATUS_PENDING;
    }

    public function rules(): array
    {
        return [
            'symptoms'=> ['required'],
        ];
    }
}
