<?php

namespace App\Http\Requests\Quote;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class QuoteRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
	 */
	public function rules(Request $request): array
	{
		//json_encode($request->quote);
		return [
			'quote'    => ['required', 'json'],
			'image'    => ['required'],
			'movie_id' => ['required'],
			'user_id'  => ['required'],
		];
	}
}
