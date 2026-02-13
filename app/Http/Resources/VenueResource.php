<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * API resource for transforming Venue model to JSON.
 */
class VenueResource extends JsonResource
{
    /**
     * Transform the resource into an array for API response.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'address' => $this->address,
            'lat' => $this->lat ? (float) $this->lat : null,
            'lng' => $this->lng ? (float) $this->lng : null,
            'price' => $this->price ? (float) $this->price : 0,
            'capacity' => (int) $this->capacity,
            'rating' => $this->rating ? (float) $this->rating : null,
            'images' => $this->images ?? [],
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
