<?php

namespace App\Http\Resources;

use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\QuoteResource;

class MovieResource extends JsonResource
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
            'title' => json_decode($this->title),
            'year' => $this->year,
            'director' => json_decode($this->director),
            'discription' => json_decode($this->discription),
            'image' => $this->image,
           'quotes' => QuoteResource::collection($this->quotes),
           'genres' => $this->genres,
        ];
    }
}
