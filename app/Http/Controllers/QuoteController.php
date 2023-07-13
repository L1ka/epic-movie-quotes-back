<?php

namespace App\Http\Controllers;

use App\Http\Requests\Quote\QuoteRequest;
use App\Models\Movie;
use App\Models\Quote;
use Illuminate\Http\Request;


class QuoteController extends Controller
{
    public function store(QuoteRequest $request): void
    {
        $movie = Movie::where('id', $request->id)->first();
        $this->authorize('store', $movie);

        $quote = Quote::create([
           'quote' => json_encode($request->quote),
           'movie_id' => $request->id,
           'user_id' => $request->user_id,
            'image' => '/storage/'.request()->file('image')->store('thumbnails'),
        ]);
     }

    public function update(QuoteRequest $request): void
    {
        $quote = Quote::where('id', $request->id)->first();
        $this->authorize('update', $quote);


        if( is_string($request->image)){
            $image = $request->image;
        } else{
            $image = '/storage/'.$request->image->store('thumbnails');
        };


        $quote->update(['quote' => json_encode($request->quote),'image' => $image ]);

    }

    public function delete(Request $request): void
    {
        $quote = Quote::where('id', $request->id)->first();

        $this->authorize('delete', $quote);

        $quote->delete();
    }
}
