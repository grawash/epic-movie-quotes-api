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
		$imageName = time() . '.' . $request->file->getClientOriginalExtension();
		$image = $request->file('file');
		$path = $image->storeAs('public/movieImages', $imageName);
		$movie = new Movie();
		$movie->title = $request->title;
		$slug = str_replace(' ', '-', $request->title);
		$movie->slug = $slug;
		$movie->director = $request->director;
		$movie->description = $request->description;
		$movie->thumbnail = $path;
		$movie->save();
		if ($request->genre)
		{
			foreach ($request->genre as $gen)
			{
				$genreExists = Genre::where('name', '=', $gen)->first();
				if ($genreExists)
				{
					$movie->genre()->attach($genreExists);
				}
				else
				{
					$genre = new Genre();
					$genre->name = $gen;
					$genre->save();
					$movie->genre()->attach($genre);
				}
			}
		}
		return response()->json($movie, 201);
	}

	public function getList(): JsonResponse
	{
		$movies = Movie::all();
		return response()->json($movies, 200);
	}

	public function getMovie(Movie $id): JsonResponse
	{
		$movie = $id;
		$genres = $movie->genre()->get();
		return response()->json(['movie' => $movie, 'genres' => $genres], 200);
	}
}
