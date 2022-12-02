<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\JsonResponse;

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

	public function updateName(UpdateUserRequest $request)
	{
		$user = jwtUser();
		$user->name = $request->name;
		$user->save();
		return response()->json(['succes', $user], 200);
	}
}
