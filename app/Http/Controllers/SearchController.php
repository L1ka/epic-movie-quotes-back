<?php

namespace App\Http\Controllers;

use App\Http\Resources\MovieResource;
use App\Http\Resources\QuoteResource;
use App\Models\Movie;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    public function searchMyMovies(Request $request): ResourceCollection
    {
        $user = Auth::user();

        $movies = Movie::where('user_id', $user->id)
        ->where(function ($query) use ($request) {
            $query->where('title->en', 'like', '%'.$request->search.'%')
                ->orWhere('title->ka', 'like', '%'.$request->search.'%');
        })
        ->orderBy('id', 'desc')
        ->get();


        return MovieResource::collection($movies->load('quotes'));
    }

    public function search(Request $request): JsonResponse
    {
        $perPage = $request->params['per_page'];
        $currentPage = $request->params['page'];


        if( !$request->search || $request->search == '#' || $request->search == '@'){
            $quotes = Quote::orderBy('id', 'desc')->paginate($perPage, ['*'], 'page', $currentPage);

            return response()->json(['quotes' => QuoteResource::collection($quotes->load('comments')),
            'lastPage' => $quotes->lastPage()]);

        }

        if (Str::startsWith($request->search, '#')) {
            $searchValue = trim(str_replace('#', '', $request->search));



            $quotes = Quote::orderBy('id', 'desc')->where(function ($query) use ($searchValue) {
            $query->where('quote->en', 'like', '%'.$searchValue.'%')
                ->orWhere('quote->ka', 'like', '%'.$searchValue.'%'); })
                ->paginate($perPage, ['*'], 'page', $currentPage);


            return response()->json(['quotes' => QuoteResource::collection($quotes->load('comments')),
            'lastPage' => $quotes->lastPage()]);

        }

        if (Str::startsWith($request->search, '@'))
        {
            $searchValue = trim(str_replace('@', '', $request->search));

            $quotes = Quote::with('movie')
            ->whereHas('movie', function ($query) use ($searchValue) {
            $query->where('title->en', 'like', '%'.$searchValue.'%')
                ->orWhere('title->ka', 'like', '%'.$searchValue.'%');
                })
                ->paginate($perPage, ['*'], 'page', $currentPage);

            return response()->json(['quotes' => QuoteResource::collection($quotes->load('comments')),
            'lastPage' => $quotes->lastPage()]);
        }
    }
}
