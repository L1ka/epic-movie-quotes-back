<?php

namespace App\Http\Controllers;

use App\Http\Requests\Movie\MovieRequest;
use App\Http\Resources\GenreResource;
use App\Http\Resources\MovieResource;
use App\Models\Genre;
use Illuminate\Http\Request;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;
use PhpCsFixer\Console\Report\FixReport\JsonReporter;
use PhpOption\None;

class MovieController extends Controller
{
    public function create(MovieRequest $request): void
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

    public function update(Request $request): void
    {

        $movie = Movie::where('id', $request->id)->first();
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

    public function delete(Request $request): void
    {
        $movie = Movie::where('id', $request->id)->first();
        $this->authorize('delete', $movie);

        $movie->delete();
    }

    public function getMovie(Request $request): MovieResource|null
    {
        $movie = Movie::where('id', $request->id)->first();

        if($movie) return  new MovieResource($movie->load('quotes'));
        return null;
    }


    public function getMovies(): ResourceCollection|null
    {
        $user = Auth::user();

        $movies = Movie::orderBy('id', 'desc')->where('user_id', $user->id)->get();

        if($movies) return MovieResource::collection($movies->load('quotes'));
        return null;
    }


    public function getGenres(): ResourceCollection
    {
        return  GenreResource::collection(Genre::all());
    }

}
