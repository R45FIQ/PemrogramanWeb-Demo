<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
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
            'title' => $this->title,
            'type' => $this->type,
            'genre' => $this->genre,
            'rating' => $this->rating !== null ? (float) $this->rating : null,
            'release_year' => $this->release_year !== null ? (int) $this->release_year : null,
            'description' => $this->description,
            'episodes' => $this->when($this->episodes !== null, $this->episodes),
            'chapters' => $this->when($this->chapters !== null, $this->chapters),
            'author' => $this->when($this->author !== null, $this->author),
            'studio' => $this->when($this->studio !== null, $this->studio),
            'country' => $this->when($this->country !== null, $this->country),
            'users' => UserResource::collection($this->whenLoaded('users')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
