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
			'quote_en'           => 'required|string',
			'quote_ka'           => 'required|string',
			'thumbnail'          => 'required|image',
			'user_id'            => 'required|numeric',
			'movie_id'           => 'required|numeric',
		];
	}
}
