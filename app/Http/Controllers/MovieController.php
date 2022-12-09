<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Traits\ImageTrait;

class MovieController extends Controller
{
	use ImageTrait;

	public function store(StoreMovieRequest $request, User $id): JsonResponse
	{
		// $imageName = time() . '.' . $request->thumbnail->getClientOriginalExtension();
		// $image = $request->file('thumbnail');
		// $path = $image->storeAs('public/movieImages', $imageName);
		$path = $this->verifyAndUpload($request->thumbnail, 'movieImages');
		// $movie = new Movie();
		// $movie->movie_id = $id->id;
		// $movie->title = $request->title;
		// $slug = $request->slug;
		// $movie->slug = $slug;
		// $movie->director = $request->director;
		// $movie->description = $request->description;
		// $movie->thumbnail = $path;
		// $movie->save();
		$validated = $request->only('title', 'description', 'director', 'slug');
		$validated['movie_id'] = $id->id;
		$validated['thumbnail'] = $path;
		$movie = Movie::create($validated);
		// if ($request->genre)
		// {
		// 	foreach ($request->genre as $gen)
		// 	{
		// 		$genreExists = Genre::where('name', '=', $gen)->first();
		// 		if ($genreExists)
		// 		{
		// 			$movie->genre()->attach($genreExists);
		// 		}
		// 		else
		// 		{
		// 			$genre = new Genre();
		// 			$genre->name = $gen;
		// 			$genre->save();
		// 			$movie->genre()->attach($genre);
		// 		}
		// 	}
		// }
		foreach ($request->genre as $gen)
		{
			$genre = Genre::firstOrCreate(['name' => $gen]);
			$movie->genre()->attach($genre);
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

	public function update(StoreMovieRequest $request, Movie $id): JsonResponse
	{
		$movie = $id;
		$movie->genre()->detach();
		$movie->update($request->only('title', 'director', 'description', 'thumbnail'));
		$slug = $request->slug;
		$movie->slug = $slug;

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
		return response()->json(['movie' => $movie], 200);
	}

	public function delete(Movie $id): JsonResponse
	{
		$movie = $id;
		$movie->delete();
		$movie->genre()->detach();
		return response()->json('movie was deleted', 200);
	}
}
