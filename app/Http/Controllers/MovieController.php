<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;

class MovieController extends Controller
{
	public function store(StoreMovieRequest $request): JsonResponse
	{
		$movie = new Movie();
		$movie->title = $request->title;
		$slug = str_replace(' ', '-', $request->title);
		$movie->slug = $slug;
		$movie->director = $request->director;
		$movie->description = $request->description;
		$movie->save();
		if ($request->genre)
		{
			foreach ($request->genre as $gen)
			{
				$genre = new Genre();
				$genre->name = $gen;
				$genre->save();
				$movie->genre()->attach($genre);
			}
		}
		return response()->json($movie, 201);
	}
}
