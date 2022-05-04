<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
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
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = ['name' => 'required | max:128',
        'email' => ['required', 'max:255', 'email:strict', 'unique:users,email'],
        'password' => 'required | confirmed | max:30 | min:6',
        'role' => ['required', Rule::in(['doctor', 'patient'])],
        'gender' => 'required',
        'height' => 'required | numeric | max:230 | min:30',
        'weight' => 'required | numeric | max:300 | min:1',
        'birth' => 'required | date | before:today',
        'diseases' => 'required | nullable',
        'previous_treatments' => 'required | nullable'];

        if ($this->get('role') == 'doctor'){
            $rules['grade'] = [' required' ,'numeric', ' min:1',' max:5'];
            $rules['speciality'] = 'required';
        }
        return $rules;
    }
}
