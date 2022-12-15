<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\JsonResponse;
use App\Traits\ImageTrait;

class UserController extends Controller
{
	use ImageTrait;

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
		$validated = $request->validated();
		$user = jwtUser();
		// if ($request->name)
		// {
		// 	$user->name = $validated['name'];
		// }
		// if ($validated['password'])
		// {
		// 	$user->password = $validated['password'];
		// }
		if ($request->thumbnail)
		{
			$validated['thumbnail'] = $this->verifyAndUpload($validated['thumbnail'], 'userImages');
		}
		// $user->save();
		$user->update($validated);
		return response()->json(['succes', $user], 200);
	}
}
