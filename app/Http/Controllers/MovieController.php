<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Http\Requests\UserIdRequest;
use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use App\Traits\ImageTrait;

class MovieController extends Controller
{
	use ImageTrait;

	public function store(StoreMovieRequest $request): JsonResponse
	{
		$validated = $request->validated();
		$validated['thumbnail'] = $this->verifyAndUpload($validated['thumbnail'], 'movieImages');
		// $movie = Movie::create(
		// [
		// 	'title'       => $validated['title'],
		// 	'director'    => $validated['director'],
		// 	'description' => $validated['description'],
		// 	'user_id'     => $validated['user_id'],
		// 	'slug'        => $request->slug,
		// 	'thumbnail'   => $validated['thumbnail'],
		// ]
		// );
		$movie = new Movie();
		$movie->slug = $request->slug;
		$movie->thumbnail = $validated['thumbnail'];
		$movie->user_id = $validated['user_id'];
		$movie->setTranslation('title', 'en', $validated['title_en']);
		$movie->setTranslation('title', 'ka', $validated['title_ka']);
		$movie->setTranslation('description', 'en', $validated['description_en']);
		$movie->setTranslation('description', 'ka', $validated['description_ka']);
		$movie->setTranslation('director', 'en', $validated['director_en']);
		$movie->setTranslation('director', 'ka', $validated['director_ka']);
		$movie->save();
		foreach ($request->genre as $gen)
		{
			$genre = Genre::firstOrCreate(['name' => $gen]);
			$movie->genres()->attach($genre);
		}
		return response()->json($movie, 201);
	}

	public function index(UserIdRequest $request): JsonResponse
	{
		$movies = Movie::where('user_id', $request->user_id)->get();
		return response()->json($movies);
	}

	public function show(Movie $movie): JsonResponse
	{
		$movie->load('quotes', 'genres');
		return response()->json(['movie' => $movie]);
	}

	public function update(UpdateMovieRequest $request, Movie $movie): JsonResponse
	{
		$movie->genres()->detach();
		$validated = $request->only('title_en', 'title_ka', 'description_en', 'description_ka', 'director_en', 'director_ka', 'slug', 'thumbnail');
		if ($request->thumbnail)
		{
			$validated['thumbnail'] = $this->verifyAndUpload($validated['thumbnail'], 'movieImages');
			$movie->thumbnail = $validated['thumbnail'];
		}
		foreach ($request->genre as $gen)
		{
			$genre = Genre::firstOrCreate(['name' => $gen]);
			$movie->genres()->attach($genre);
		}
		$movie->slug = $request->slug;
		$movie->replaceTranslations('title', ['en' => $request->title_en, 'ka' => $request->title_ka]);
		$movie->replaceTranslations('description', ['en' => $request->description_en, 'ka' => $request->description_ka]);
		$movie->replaceTranslations('director', ['en' => $request->director_en, 'ka' => $request->director_ka]);

		$movie->save();
		return response()->json(['movie' => $movie]);
	}

	public function destroy(Movie $movie): JsonResponse
	{
		$movie->delete();
		return response()->json('movie was deleted');
	}
}
