<?php

namespace App\Http\Requests\Register;
use Illuminate\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(Request $request): array
    {
        app()->setLocale($request->getPreferredLanguage());
        return [
            'email' => ['required', 'email','unique:users'],
            'first_name' => ['required', 'unique:users', 'min:3', 'max:15', 'lowercase', 'regex:/^[a-z0-9 ]+$/'],
            'password' => ['required',  'min:8', 'max:15', 'lowercase', 'regex:/^[a-z0-9 ]+$/'],
            'confirm_password' => ['required', 'same:password']
        ];
    }
}
