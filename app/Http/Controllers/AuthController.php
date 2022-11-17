<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
	public function register(StoreUserRequest $request): JsonResponse
	{
		$user = User::create($request->validated());
		auth()->login($user);
		return response()->json('User successfuly registered!', 200);
	}

	public function login(LoginRequest $request): JsonResponse
	{
		$token = auth()->attempt($request->all());
		if (!$token)
		{
			return response()->json([
				'status'  => 'error',
				'message' => 'Unauthorized',
			], 401);
		}
		return $this->respondWithToken($token);
	}

	public function logout(): JsonResponse
	{
		auth()->logout();

		return response()->json(['message' => 'Successfully logged out']);
	}

	protected function respondWithToken(string $token): JsonResponse
	{
		return response()->json([
			'access_token' => $token,
			'token_type'   => 'bearer',
			'expires_in'   => auth()->factory()->getTTL() * 60,
		]);
	}
}
