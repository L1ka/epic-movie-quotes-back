<?php

namespace App\Http\Controllers;

use App\Events\AddComment;
use App\Http\Requests\Comment\CommentRequest;
use App\Http\Resources\MovieResource;
use App\Models\Comment;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function create(CommentRequest $request): JsonResponse
    {
        $comment = Comment::create($request->validated());



        $movie = Movie::where('user_id', $request->input('user_id'))->first();

        event(new AddComment(new MovieResource($movie)));

        return response()->json(['success' => 'comment added successfully'], 200);
    }


}
