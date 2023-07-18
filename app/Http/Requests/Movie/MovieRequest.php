<?php

namespace App\Http\Requests\Movie;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class MovieRequest extends FormRequest
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
			'title'    => ['required', 'json', 'unique:movies,title->en', 'unique:movies,title->ka'],
			'year'     => ['required',  'digits:4'],
			'director' => ['required', 'json'],
			'image'    => ['required'],
            'discription'  => ['required', 'json'],
            'user_id'   => ['required'],
		];
	}
}
