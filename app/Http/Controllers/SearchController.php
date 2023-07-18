<?php

namespace App\Http\Controllers;

use App\Http\Resources\MovieResource;
use App\Http\Resources\QuoteResource;
use App\Models\Movie;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    public function searchMyMovies(Request $request): ResourceCollection
    {
        $user = auth()->user();

        $movies = Movie::where('user_id', $user->id)->filter($request)->get();


        return MovieResource::collection($movies->load('quotes'));
    }


    public function search(Request $request): JsonResponse
    {
        $perPage = $request->params['per_page'];
        $currentPage = $request->params['page'];


        if( !$request->search || $request->search == '#' || $request->search == '@')
        {
            $quotes = Quote::orderBy('id', 'desc')->paginate($perPage, ['*'], 'page', $currentPage);
        }

        if (Str::startsWith($request->search, '#'))
        {
            $quotes = Quote::orderBy('id', 'desc')->filterquote($request)
                ->paginate($perPage, ['*'], 'page', $currentPage);
        }

        if (Str::startsWith($request->search, '@'))
        {
            $quotes = Quote::orderBy('id', 'desc')->with('movie')->filtermovie($request)
                ->paginate($perPage, ['*'], 'page', $currentPage);
        }


        return response()->json([
            'quotes' => QuoteResource::collection($quotes->load('comments')),
            'lastPage' => $quotes->lastPage()
        ]);
    }
}
