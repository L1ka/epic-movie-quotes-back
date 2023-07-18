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
        $this->authorize('create',Movie::class);

        $newMovie =   Movie::create([
            'title' => json_encode($request->title),
            'year' => $request->year,
            'director' => json_encode($request->director),
            'discription' => json_encode($request->discription),
            'image' => '/storage/'.$request->image->store('thumbnails'),
            'user_id' => $request->user_id
        ]);

        $newMovie->genres()->attach($request->genre);

    }

    public function update(Request $request, Movie $movie)
    {
        $this->authorize('update', $movie);

        if( is_string($request->image)){
            $image = $request->image;
        } else{
            $image = '/storage/'.$request->image->store('thumbnails');
        };


        $movie->update([
            'title' => json_encode($request->title),
            'year' => $request->year,
            'director' => json_encode($request->director),
            'discription' => json_encode($request->discription),
            'image' => $image
        ]);

        $movie->genres()->sync($request->genre);
    }

    public function delete(Movie $movie)
    {
        $this->authorize('delete', $movie);

        $movie->delete();
    }

    public function show(Movie $movie): MovieResource|JsonResponse
    {
        $movie = Movie::find($movie->id);

        if($movie) return  new MovieResource($movie->load('quotes'));
        return response()->json(['movie' => 'movie not found'], 200);
    }


    public function showMovies(): ResourceCollection|JsonResponse
    {
        $user = auth()->user();

        $movies = Movie::orderBy('id', 'desc')->where('user_id', $user->id)->get();

        if($movies) return MovieResource::collection($movies->load('quotes'));
        return response()->json(['movies' => 'movie not found'], 200);
    }


    public function showGenres(): ResourceCollection
    {
        return  GenreResource::collection(Genre::all());
    }

}
