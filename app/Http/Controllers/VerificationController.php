<?php

namespace App\Http\Controllers;

use App\Http\Requests\VerifyRequest;
use App\Models\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;

class VerificationController extends Controller
{
	public function verify(VerifyRequest $request)
	{
		$user = User::find($request->route('id'));

		if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification())))
		{
			// dd('it should work');
			throw new AuthorizationException();
		}

		if ($user->markEmailAsVerified())
		{
			event(new Verified($user));
		}
		$payload = [
			'exp' => Carbon::now()->addMinutes(30)->timestamp,
			'uid' => $user->id,
		];

		$jwt = JWT::encode($payload, config('auth.jwt_secret'), 'HS256');

		$cookie = cookie('access_token', $jwt, 30, '/', config('auth.front_end_top_level_domain'), true, true, false, 'Strict');

		return response()->json(['success', $jwt], 200)->withCookie($cookie);
	}
}
