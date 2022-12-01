<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Notifications\VerifyEmail;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Http\JsonResponse;
use Illuminate\Auth\Events\Registered;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class AuthController extends Controller
{
	public function register(StoreUserRequest $request): JsonResponse
	{
		$user = User::create($request->validated());
		// event(new Registered($user));
		$token = Str::random(32);
		$user->notify(new VerifyEmail($token));
		return response()->json('User successfuly registered!', 201);
	}

	public function login(LoginRequest $request): JsonResponse
	{
		$authenticated = auth()->attempt($request->all());
		if (!$authenticated)
		{
			return response()->json([
				'status'  => 'error',
				'message' => 'Unauthorized',
			], 401);
		}
		$payload = [
			'exp' => Carbon::now()->addMinutes(30)->timestamp,
			'uid' => User::where('email', '=', $request->email)->first()->id,
		];

		$jwt = JWT::encode($payload, config('auth.jwt_secret'), 'HS256');

		$cookie = cookie('access_token', $jwt, 30, '/', config('auth.front_end_top_level_domain'), true, true, false, 'Strict');

		return response()->json(['success', $jwt], 200)->withCookie($cookie);
	}

	public function logout(): JsonResponse
	{
		$cookie = cookie('access_token', '', 0, '/', config('auth.front_end_top_level_domain'), true, true, false, 'Strict');

		return response()->json('success', 200)->withCookie($cookie);
	}

	public function googleAuthentication()
	{
		return response()->json([
			'url' => Socialite::driver('google')->stateless()->redirect()->getTargetUrl(),
		]);
	}

	public function googleRedirect()
	{
		$googleUser = Socialite::driver('google')->stateless()->user();
		if (!User::where('email', $googleUser->email)->first())
		{
			$status = 'created';
			$user = User::updateOrCreate([
				'name'                 => $googleUser->name,
				'email'                => $googleUser->email,
				'password'             => $googleUser->id,
			]);
		}
		else
		{
			$status = 'login';
			$user = User::where('email', $googleUser->email)->first();
		}
		$payload = [
			'exp' => Carbon::now()->addMinutes(30)->timestamp,
			'uid' => User::where('email', '=', $user->email)->first()->id,
		];

		$jwt = JWT::encode($payload, config('auth.jwt_secret'), 'HS256');

		$cookie = cookie('access_token', $jwt, 30, '/', config('auth.front_end_top_level_domain'), true, true, false, 'Strict');

		return response()->json(['success', $jwt, $user, 'status' => $status], 200)->withCookie($cookie);
	}
}
