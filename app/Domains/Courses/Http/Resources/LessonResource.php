<?php

namespace App\Domains\Courses\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'order'       => $this->order,
            'duration'    => $this->duration,
            'is_free'     => $this->is_free,
            'media_url'   => $this->getFirstMediaUrl('lesson_media') ?? null,
            'created_at'  => $this->created_at->toDateTimeString(),
        ];
    }
}
