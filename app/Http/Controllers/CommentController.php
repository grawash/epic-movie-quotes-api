<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuoteIdRequest;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
	public function store(StoreCommentRequest $request): JsonResponse
	{
		$validated = $request->validated();
		$comment = Comment::create($validated);
		return response()->json($comment, 201);
	}

	public function quoteComments(QuoteIdRequest $request): JsonResponse
	{
		$comments = Comment::where('quote_id', $request->quote_id)->get();
		$comments->load('user');
		return response()->json($comments);
	}
}
