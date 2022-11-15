<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;

class AuthController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth:api', ['except' => ['login', 'register']]);
	}

	public function register(StoreUserRequest $request)
	{
		$user = User::create($request->validated());
		auth()->login($user);
		return response()->json('User successfuly registered!', 200);
	}

	public function login(LoginRequest $request)
	{
		$token = auth()->attempt($request->all());
		if (!$token)
		{
			return response()->json([
				'status'  => 'error',
				'message' => 'Unauthorized',
			], 401);
		}
		$user = auth()->user();
		return response()->json([
			'status'        => 'success',
			'user'          => $user,
			'authorisation' => [
				'token' => $token,
				'type'  => 'bearer',
			],
		]);
	}

	public function logout()
	{
		auth()->logout();

		return response()->json(['message' => 'Successfully logged out']);
	}
}
