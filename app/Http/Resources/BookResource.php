<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


/**
 * Class BookResource
 *
 * Transforms Book model data into a structured
 * JSON response for API consumers.
 *
 * Includes basic book information, category details,
 * borrowing statistics, and rating metrics.
 */
class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id'             => $this->id,
            'title'          => $this->title,
            'author'         => $this->author,
            'description'    => $this->description,
            'publish_year'   => $this->publish_year,
            'language'       => $this->language,
            'copies_number'  => $this->copies_number,
            'cover_image'    => $this->cover_image ? asset('storage/' . $this->cover_image) : null,

            // Related category data (loaded conditionally)
            'category' => $this->whenLoaded('category', function () {
                return [
                    'id'   => $this->category->id,
                    'name' => $this->category->name,
                ];
            }),

            // Aggregated statistics calculated via eager loading
            'borrows_count'  => $this->borrows_count ?? 0,
            'average_rating' => round($this->average_rating ?? 0, 1),
            'ratings_count'  => $this->ratings_count ?? 0,
        ];
    }
}