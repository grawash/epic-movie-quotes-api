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
			'title_en'                 => 'required|string',
			'title_ka'                 => 'required|string',
			'director_en'              => 'required|string',
			'director_ka'              => 'required|string',
			'description_en'           => 'required|string',
			'description_ka'           => 'required|string',
			'genre'                    => 'required|array',
			'thumbnail'                => 'required|image',
			'user_id'                  => 'required|numeric',
		];
	}

	protected function prepareForValidation()
	{
		$this->merge([
			'slug'  => str_replace(' ', '-', $this->get('title_en')),
			'genre' => json_decode($this->genre),
		]);
	}
}
