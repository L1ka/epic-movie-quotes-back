<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuoteResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'id'       => $this->id,
			'quote'    => json_decode($this->quote),
			'image'    => $this->image,
			'movie'    => new MovieResource($this->movie),
			'user'     => new UserResource($this->user),
			'comments' => CommentResaurce::collection($this->whenLoaded('comments', function () {
				return $this->comments->sortByDesc('id');
			})),
			'likes_count' => $this->likers()->count(),
            'liked_by_user' => $this->likers->contains(auth()->id()) ? new UserResource(auth()->user()) : null,
		];
	}
}
