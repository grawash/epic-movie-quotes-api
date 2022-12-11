<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuoteRequest;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;

class QuoteController extends Controller
{
	public function store(StoreQuoteRequest $request): JsonResponse
	{
		$validated = $request->validated();
		$quote = Quote::create($validated);
		return response()->json($quote, 201);
	}

	public function index(): JsonResponse
	{
		$quotes = Quote::all();
		return response()->json($quotes, 201);
	}
}
