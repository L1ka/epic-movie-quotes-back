<?php

namespace App\Http\Controllers;

use App\Http\Requests\Quote\QuoteRequest;
use App\Http\Resources\QuoteResource;
use App\Models\Movie;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class QuoteController extends Controller
{
	public function store(QuoteRequest $request): void
	{
		$movie = Movie::find($request->movie_id);
		$this->authorize('store', $movie);

		Quote::create([
			...$request->validated(),
			'image'    => '/storage/' . request()->image->store('thumbnails'),
		]);
	}

	public function update(Request $request, Quote $quote): void
	{
		$this->authorize('update', $quote);

		if (is_string($request->image)) {
			$image = $request->image;
		} else {
			$image = '/storage/' . $request->image->store('thumbnails');
		}

		$quote->update(['quote' => $request->quote, 'image' => $image]);
	}

	public function delete(Quote $quote): void
	{
		$this->authorize('delete', $quote);

		$quote->delete();
	}

	public function show(Quote $quote): QuoteResource|JsonResponse
	{
		$quote = Quote::find($quote->id);

		if ($quote) {
			return new QuoteResource($quote->load('comments'));
		}

		return response()->json(['quote' => 'quote not found'], 200);
	}

	public function index(Movie $movie): ResourceCollection|JsonResponse
	{
		$movie = Movie::find($movie->id);
		if ($movie) {
			$quotes = $movie->quotes->sortByDesc('id');

			return QuoteResource::collection($quotes->load('comments'));
		}

		return response()->json(['movies' => 'movie not found'], 200);
	}
}
