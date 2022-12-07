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

	public function update(UpdateUserRequest $request)
	{
		$user = jwtUser();
		if ($request->name)
		{
			$user->name = $request->name;
		}
		if ($request->password)
		{
			$user->password = $request->password;
		}
		$user->save();
		return response()->json(['succes', $user], 200);
	}
}
