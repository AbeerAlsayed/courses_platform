<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'duration' => $this->duration,
            'status' => $this->status,
            'category' => [
                'id' => $this->category?->id,
                'name' => $this->category?->name,
            ],
            'instructor' => [
                'id' => $this->instructor?->id,
                'name' => $this->instructor?->user?->name,
            ],
            'image_url' => $this->getFirstMediaUrl('cover'),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
