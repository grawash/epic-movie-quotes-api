<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuoteIdRequest;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use App\Events\NotifyUser;

class CommentController extends Controller
{
	public function store(StoreCommentRequest $request): JsonResponse
	{
		$validated = $request->validated();
		$comment = Comment::create($validated);
		broadcast(new NotifyUser($comment))->toOthers();
		return response()->json($comment, 201);
	}

	public function index(QuoteIdRequest $request): JsonResponse
	{
		$comments = Comment::where('quote_id', $request->quote_id)->get();
		$comments->load('user');
		return response()->json($comments);
	}
}
