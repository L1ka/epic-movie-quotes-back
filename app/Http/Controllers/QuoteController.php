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
        $movie = Movie::find($request->id);
        $this->authorize('store', $movie);

        Quote::create([
           'quote' => json_encode($request->quote),
           'movie_id' => $request->id,
           'user_id' => $request->user_id,
            'image' => '/storage/'.request()->file('image')->store('thumbnails'),
        ]);
     }

    public function update(QuoteRequest $request): void
    {
        $quote = Quote::find($request->id);
        $this->authorize('update', $quote);


        if( is_string($request->image)){
            $image = $request->image;
        } else{
            $image = '/storage/'.$request->image->store('thumbnails');
        };


        $quote->update(['quote' => json_encode($request->quote),'image' => $image ]);

    }

    public function delete(Quote $quote): void
    {
        $quote = Quote::find($quote->id);

        $this->authorize('delete', $quote);

        $quote->delete();
    }

    public function show(Quote $quote): QuoteResource|JsonResponse
    {
        $quote = Quote::find($quote->id);

        if($quote) return new QuoteResource($quote->load('comments'));

        return response()->json(['quote' => 'quote not found'], 200);
    }

    public function showQuotes(Movie $movie): ResourceCollection|JsonResponse
    {
        $movie = Movie::find($movie->id);
        if($movie) {
            $quotes = $movie->quotes->sortByDesc('id');

            return QuoteResource::collection($quotes->load('comments'));
        }

        return response()->json(['movies' => 'movie not found'], 200);
    }
}
