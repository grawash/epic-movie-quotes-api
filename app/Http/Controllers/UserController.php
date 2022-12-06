<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class UserController extends Controller
{
	public function index(): JsonResponse
	{
		return response()->json(
			[
				'message' => 'authenticated successfully',
				'user'    => jwtUser(),
			],
			200
		);
	}

	public function update(UpdateUserRequest $request)
	{
		$user = jwtUser();
		if ($request->name)
		{
			$user->name = $request->name;
		}
		if ($request->password)
		{
			Password::reset(
				$request->only('email', 'password', 'password_confirmation', 'token'),
				function ($user, $password) {
					$user->forceFill([
						'password' => $password,
					])->setRememberToken(Str::random(60));
					$user->save();

					event(new PasswordReset($user));
				}
			);
		}
		$user->save();
		return response()->json(['succes', $user], 200);
	}
}
