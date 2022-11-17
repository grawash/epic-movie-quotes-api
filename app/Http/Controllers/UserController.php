<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
	public function user(): JsonResponse
	{
		return response()->json(auth()->user(), 200);
	}
}
