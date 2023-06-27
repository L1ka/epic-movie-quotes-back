<?php

namespace App\Http\Controllers;

use App\Events\AddLike;
use App\Http\Requests\Like\LikeRequest;
use App\Http\Resources\MovieResource;
use App\Models\Like;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function create(LikeRequest $request): JsonResponse
    {

        $oldLike = Like::where('quote_id', $request->input('quote_id'))->where('user_id', $request->input('user_id'))->first();

        if($oldLike) {
            $oldLike->delete();
        }else {
            Like::create($request->validated());
        }

        $movie = Movie::where('user_id', $request->input('user_id'))->first();


        event(new AddLike(new MovieResource($movie)));

        return response()->json(['success' => 'like added successfully'], 200);
    }
}
