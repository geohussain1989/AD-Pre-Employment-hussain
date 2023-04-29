<?php

namespace App\Http\Requests;

use App\Rules\AgeRule;
use App\Rules\MaxUserRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
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

            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'address' => 'required|max:255',
            'date_of_birth' => [
                'required',
                'date_format:m/d/Y',
                new AgeRule()
            ],
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6',
            'interests' => 'nullable|array'
        ];
    }
}
