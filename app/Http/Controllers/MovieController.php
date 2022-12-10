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
		$validated = $request->only('title', 'description', 'director', 'slug', 'thumbnail');
		$validated['user_id'] = $request->userId;
		$validated['thumbnail'] = $this->verifyAndUpload($validated['thumbnail'], 'movieImages');
		$movie = Movie::create($validated);
		foreach ($request->genre as $gen)
		{
			$genre = Genre::firstOrCreate(['name' => $gen]);
			$movie->genre()->attach($genre);
		}
		return response()->json($movie, 201);
	}

	public function index(UserIdRequest $request): JsonResponse
	{
		$movies = Movie::where('user_id', $request->userId)->get();
		return response()->json($movies, 200);
	}

	public function show(Movie $movie): JsonResponse
	{
		$genres = $movie->genre()->get();
		$movie->genres = $genres;
		return response()->json(['movie' => $movie], 200);
	}

	public function update(UpdateMovieRequest $request, Movie $movie): JsonResponse
	{
		$movie->genre()->detach();
		$validated = $request->only('title', 'description', 'director', 'slug', 'thumbnail');
		if ($request->thumbnail)
		{
			$validated['thumbnail'] = $this->verifyAndUpload($validated['thumbnail'], 'movieImages');
		}
		$movie->update($validated);
		foreach ($request->genre as $gen)
		{
			$genre = Genre::firstOrCreate(['name' => $gen]);
			$movie->genre()->attach($genre);
		}
		return response()->json(['movie' => $movie], 200);
	}

	public function destroy(Movie $movie): JsonResponse
	{
		$movie->delete();
		return response()->json('movie was deleted', 200);
	}
}
