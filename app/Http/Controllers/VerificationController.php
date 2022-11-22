<?php

namespace App\Http\Controllers;

use App\Http\Requests\VerifyRequest;
use App\Models\User;
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
	}
}
