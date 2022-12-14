<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuoteIdRequest;
use App\Http\Requests\StoreLikeRequest;
use App\Http\Requests\UserLikeRequest;
use App\Models\Like;
use Illuminate\Http\JsonResponse;

class LikeController extends Controller
{
	public function store(StoreLikeRequest $request): JsonResponse
	{
		$validated = $request->validated();
		$like = Like::create($validated);
		return response()->json($like, 201);
	}

	public function showUserLikes(QuoteIdRequest $request): JsonResponse
	{
		$likes = Like::where('quote_id', $request->quote_id)->get();
		return response()->json($likes);
	}

	public function chekQuoteLikeStatus(UserLikeRequest $request): JsonResponse
	{
		$like = Like::where('user_id', $request->sender_id)->where('quote_id', $request->quote_id)->get();
		return response()->json($like);
	}

	public function destroy(UserLikeRequest $request): JsonResponse
	{
		$like = Like::where('user_id', $request->sender_id)->where('quote_id', $request->quote_id)->first();
		$like->delete();
		return response()->json('quote was deleted');
	}
}
