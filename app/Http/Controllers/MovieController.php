<?php

namespace App\Http\Controllers;

use App\Http\Requests\Movie\MovieRequest;
use App\Http\Resources\GenreResource;
use App\Http\Resources\MovieResource;
use App\Models\Genre;
use Illuminate\Http\Request;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MovieController extends Controller
{
	public function store(MovieRequest $request): void
	{
		$this->authorize('create', Movie::class);

		$newMovie = Movie::create([
			...$request->validated(),
			'image' => '/storage/' . $request->image->store('thumbnails'),
		]);

		$newMovie->genres()->attach(json_decode($request->genre));
	}

	public function update(Request $request, Movie $movie): void
	{
		$this->authorize('update', $movie);

		if (is_string($request->image)) {
			$image = $request->image;
		} else {
			$image = '/storage/' . $request->image->store('thumbnails');
		}

		$movie->update([...$request->all(), 'image' => $image]);

		$movie->genres()->sync(json_decode($request->genre));
	}

	public function delete(Movie $movie): void
	{
		$this->authorize('delete', $movie);

		$movie->delete();
	}

	public function show(Movie $movie): MovieResource|JsonResponse
	{
		$movie = Movie::find($movie->id);

		if ($movie) {
			return  new MovieResource($movie->load('quotes'));
		}
		return response()->json(['movie' => 'movie not found'], 200);
	}

	public function index(): ResourceCollection|JsonResponse
	{
		$user = auth()->user();

		$movies = Movie::orderBy('id', 'desc')->where('user_id', $user->id)->get();

		if ($movies) {
			return MovieResource::collection($movies->load('quotes'));
		}
		return response()->json(['movies' => 'movie not found'], 200);
	}

	public function showGenres(): ResourceCollection
	{
		return  GenreResource::collection(Genre::all());
	}
}
