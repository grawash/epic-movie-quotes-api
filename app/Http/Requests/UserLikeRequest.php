<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserLikeRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
			'sender_id'   => 'required|numeric',
			'quote_id'    => 'required|numeric',
		];
	}
}
