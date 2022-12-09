<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMovieRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
			'title'                => 'required|string',
			'director'             => 'required|string',
			'description'          => 'required|string',
			'genre'                => 'required|array',
			'thumbnail'            => 'required|image',
			'userId'               => 'required|numeric',
		];
	}

	protected function prepareForValidation()
	{
		$this->merge([
			'slug' => str_replace(' ', '-', $this->get('title')),
		]);
	}
}
