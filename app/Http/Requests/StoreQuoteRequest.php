<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuoteRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
			'quote'           => 'required',
			'thumbnail'       => 'required',
			'user_id'         => 'required',
			'movie_id'        => 'required',
			'movie_title'     => 'required',
		];
	}
}
