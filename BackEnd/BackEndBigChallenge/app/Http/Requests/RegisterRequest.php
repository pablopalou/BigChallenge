<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // QUESTION
        // should i put some logic here or only true?
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules  = [
            'name' => 'required | max:128',
            'email' => 'required | max:255 | email:strict | unique:users,email',
            'role' => 'required',
            'gender' => 'required',
            'height' => 'required | numeric | max:230 | min:30',
            'weight' => 'required | numeric | max:300 | min:1',
            'birth' => 'required | date | before:today'
        ];


        # if we have a doctor, we will have all the information beacuse maybe he wants to be a patient sometimes
        # so, we will check if the role attribute is Doctor or not.

        if ($this->attributes->get('role') === "Doctor"){
            $rules['grade'] = 'required | numeric | min:1 | max:5';
            $rules['speciality'] = 'required';
        }

        return $rules;
    }
}
