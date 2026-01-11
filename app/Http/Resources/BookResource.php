<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class BookResource extends JsonResource
{
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
            'cover_image'    => $this->cover_image ? asset('storage/'.$this->cover_image) : null,

            // Relations.
            'category' => [
                'id'   => $this->category->id,
                'name' => $this->category->name,
            ],

            // These come from withCount() and withAvg()
            'borrows_count'  => $this->borrows_count ?? 0,
            'average_rating' => round($this->reviews_avg_stars ?? 0, 1),
        ];
    }
}