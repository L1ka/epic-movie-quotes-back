<?php

namespace App\Http\Requests\Password;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
	 */
	public function rules(): array
	{
		return [
			'token'            => ['required'],
			'email'            => ['required', 'email'],
			'password'         => ['required', 'min:8', 'max:15', 'lowercase', 'alpha_num:ascii'],
			'confirm_password' => ['required', 'same:password'],
		];
	}
}
