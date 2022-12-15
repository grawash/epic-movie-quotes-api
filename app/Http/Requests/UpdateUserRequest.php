<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
			'name'                  => 'min:3|max:15',
			'password'              => 'confirmed|min:8|max:15',
			'password_confirmation' => '',
			'email'                 => 'email',
			'thumbnail'             => '',
		];
	}
}
