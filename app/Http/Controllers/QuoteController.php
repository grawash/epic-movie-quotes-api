<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuoteRequest;
use App\Http\Requests\UpdateQuoteRequest;
use App\Models\Quote;
use App\Traits\ImageTrait;
use Illuminate\Http\JsonResponse;

class QuoteController extends Controller
{
	use ImageTrait;

	public function store(StoreQuoteRequest $request): JsonResponse
	{
		$validated = $request->validated();
		$validated['thumbnail'] = $this->verifyAndUpload($validated['thumbnail'], 'quoteImages');
		$quote = Quote::create($validated);
		return response()->json($quote, 201);
	}

	public function index(): JsonResponse
	{
		$quotes = Quote::all();
		return response()->json($quotes, 201);
	}

	public function show(Quote $quote): JsonResponse
	{
		return response()->json(['quote' => $quote], 200);
	}

	public function update(UpdateQuoteRequest $request, Quote $quote): JsonResponse
	{
		$validated = $request->validated();
		if ($request->thumbnail)
		{
			$validated['thumbnail'] = $this->verifyAndUpload($validated['thumbnail'], 'quoteImages');
		}
		$quote->update($validated);
		return response()->json(['quote' => $quote], 200);
	}

	public function destroy(Quote $quote): JsonResponse
	{
		$quote->delete();
		return response()->json('quote was deleted', 200);
	}
}
