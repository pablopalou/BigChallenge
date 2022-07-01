<?php

namespace App\Http\Requests;

use App\Models\Submission;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class GetSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Submission $submission */
        $submission = $this->route('submission');

        $user = User::find(Auth::user()->id);

        return Auth::user()->id == $submission->patient_id || $submission->doctor_id == Auth::user()->id || ($submission->state == Submission::STATUS_PENDING && $user->hasRole('doctor'));
    }

    public function rules(): array
    {
        return [
            //
        ];
    }
}
