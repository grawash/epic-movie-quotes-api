<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostResetRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;

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
}
