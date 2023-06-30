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
            'email' => 'required|email|unique:user',
            'username' => 'required|unique:user',
            'password' => 'required|confirmed|min:8|regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@#$%^&+=])?[A-Za-z\d@#$%^&+=]*$/',
            'password_confirmation' => 'required|same:password',
            'userType' => 'required'
        ];
    }
}