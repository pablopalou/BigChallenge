<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        // TODO $this->route->submissino_>patient id == Auth()->user->id 
        return true;
    }

    public function rules(): array
    {
        return [
            'symptoms'=> ['required'],
        ];
    }
}
