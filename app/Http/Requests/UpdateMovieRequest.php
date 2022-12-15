<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMovieRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
			'title_en'                 => 'required|string',
			'title_ka'                 => 'required|string',
			'director_en'              => 'required|string',
			'director_ka'              => 'required|string',
			'description_en'           => 'required|string',
			'description_ka'           => 'required|string',
			'genre'                    => 'required|array',
			'thumbnail'                => 'image',
		];
	}

	protected function prepareForValidation()
	{
		$this->merge([
			'slug'  => str_replace(' ', '-', $this->get('title_en')),
		]);
	}
}
