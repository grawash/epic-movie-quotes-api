<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostResetRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Firebase\JWT\JWT;

class ResetPasswordController extends Controller
{
	public function email(PostResetRequest $request): JsonResponse
	{
		$status = Password::sendResetLink(
			$request->only('email')
		);
		return response()->json($status, 201);
		// return $status === Password::RESET_LINK_SENT
		// 			? back()->with(['status' => __($status)])
		// 			: back()->withErrors(['email' => __($status)]);
	}

	public function update(ResetPasswordRequest $request): JsonResponse
	{
		$status = Password::reset(
			$request->only('email', 'password', 'password_confirmation', 'token'),
			function ($user, $password) {
				$user->forceFill([
					'password' => $password,
				])->setRememberToken(Str::random(60));
				$user->save();

				event(new PasswordReset($user));
			}
		);
		$user = User::where('email', $request->email)->first();
		$payload = [
			'exp' => Carbon::now()->addMinutes(30)->timestamp,
			'uid' => $user->id,
		];

		$jwt = JWT::encode($payload, config('auth.jwt_secret'), 'HS256');

		$cookie = cookie('access_token', $jwt, 30, '/', config('auth.front_end_top_level_domain'), true, true, false, 'Strict');

		return response()->json(['success', $jwt], 200)->withCookie($cookie);
		// return $status === Password::PASSWORD_RESET
		// 			? redirect()->route('reset.login')->with('status', __($status))
		// 			: back()->withErrors(['email' => [__($status)]]);
	}
}
