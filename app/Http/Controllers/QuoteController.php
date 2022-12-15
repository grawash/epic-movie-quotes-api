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
		$quote = new Quote();
		$quote->thumbnail = $validated['thumbnail'];
		$quote->user_id = $validated['user_id'];
		$quote->movie_id = $validated['movie_id'];
		$quote->setTranslation('quote', 'en', $validated['quote_en']);
		$quote->setTranslation('quote', 'ka', $validated['quote_ka']);
		$quote->save();
		return response()->json($quote, 201);
	}

	public function index(): JsonResponse
	{
		$quotes = Quote::with('user', 'movie')->paginate(12);
		return response()->json($quotes);
	}

	public function show(Quote $quote): JsonResponse
	{
		return response()->json(['quote' => $quote]);
	}

	public function update(UpdateQuoteRequest $request, Quote $quote): JsonResponse
	{
		$validated = $request->validated();
		if ($request->thumbnail)
		{
			$validated['thumbnail'] = $this->verifyAndUpload($validated['thumbnail'], 'quoteImages');
			$quote->thumbnail = $validated['thumbnail'];
		}
		$quote->replaceTranslations('quote', ['en' => $request->quote_en, 'ka' => $request->quote_ka]);
		$quote->save();

		return response()->json(['quote' => $quote]);
	}

	public function destroy(Quote $quote): JsonResponse
	{
		$quote->delete();
		return response()->json('quote was deleted');
	}
}
