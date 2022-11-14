<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;

class RegisterController extends Controller
{
	public function register(StoreUserRequest $request)
	{
		$user = User::create($request->validated());
		$users = User::all();
		auth()->login($user);
		return response()->json([
			'status' => true,
			'users'  => $users,
		], 200);
	}
}
