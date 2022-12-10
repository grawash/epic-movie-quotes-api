<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
			'name'                  => 'required|min:3|max:15',
			'email'                 => 'required|email',
			'password'              => 'confirmed|required|min:8|max:15',
			'password_confirmation' => 'required',
		];
	}
}
