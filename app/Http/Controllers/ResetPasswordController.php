<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostResetRequest;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

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
		return response()->json($status, 201);
		// return $status === Password::PASSWORD_RESET
		// 			? redirect()->route('reset.login')->with('status', __($status))
		// 			: back()->withErrors(['email' => [__($status)]]);
	}
}
