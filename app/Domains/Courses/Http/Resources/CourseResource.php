<?php

namespace App\Domains\Courses\Http\Resources;

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
            'is_enrolled' => $this->when(isset($this->is_enrolled), $this->is_enrolled),

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

            // عرض الأقسام فقط إن كانت محملة وبها عناصر
            'sections' => $this->when(
                $this->relationLoaded('sections') && $this->sections->isNotEmpty(),
                SectionResource::collection($this->sections)
            ),

            // عرض الدروس المستقلة فقط إن كانت محملة وبها عناصر
            'standalone_lessons' => $this->when(
                $this->relationLoaded('standaloneLessons') && $this->standaloneLessons->isNotEmpty(),
                LessonResource::collection($this->standaloneLessons)
            ),
        ];
    }
}
