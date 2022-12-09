<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Traits\ImageTrait;

class MovieController extends Controller
{
	use ImageTrait;

	public function store(StoreMovieRequest $request, User $user): JsonResponse
	{
		$path = $this->verifyAndUpload($request->thumbnail, 'movieImages');
		$validated = $request->only('title', 'description', 'director', 'slug');
		$validated['user_id'] = $user->id;
		$validated['thumbnail'] = $path;
		$movie = Movie::create($validated);
		foreach ($request->genre as $gen)
		{
			$genre = Genre::firstOrCreate(['name' => $gen]);
			$movie->genre()->attach($genre);
		}
		return response()->json($movie, 201);
	}

	public function index(User $user): JsonResponse
	{
		$movies = Movie::where('user_id', $user->id)->get();
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
		$path = $this->verifyAndUpload($request->thumbnail, 'movieImages');
		$movie->genre()->detach();
		$validated = $request->only('title', 'description', 'director', 'slug');
		$validated['thumbnail'] = $path;
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
		// $movie->genre()->detach();
		return response()->json('movie was deleted', 200);
	}
}
