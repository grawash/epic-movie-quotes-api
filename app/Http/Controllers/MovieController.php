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
		$movie = Movie::create([
			'title'       => $validated['title'],
			'director'    => $validated['director'],
			'description' => $validated['description'],
			'user_id'     => $validated['user_id'],
			'slug'        => $request->slug,
			'thumbnail'   => $validated['thumbnail'],
		]);
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
		$validated = $request->only('title', 'description', 'director', 'slug', 'thumbnail');
		if ($request->thumbnail)
		{
			$validated['thumbnail'] = $this->verifyAndUpload($validated['thumbnail'], 'movieImages');
		}
		$movie->update($validated);
		foreach ($request->genre as $gen)
		{
			$genre = Genre::firstOrCreate(['name' => $gen]);
			$movie->genres()->attach($genre);
		}
		return response()->json(['movie' => $movie]);
	}

	public function destroy(Movie $movie): JsonResponse
	{
		$movie->delete();
		return response()->json('movie was deleted');
	}
}
