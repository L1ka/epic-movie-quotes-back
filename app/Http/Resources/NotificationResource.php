<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'movie_id' =>$this->movie_id,
            'quote_id' =>$this->quote_id,
            'seen' => $this->seen,
            'type' => $this->type,
            'user' => new UserResource($this->user),
            'created_at' =>$this->created_at,
        ];
    }
}
