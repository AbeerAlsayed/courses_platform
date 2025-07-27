<?php

namespace App\Domains\Courses\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SectionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'order'      => $this->order,
            'lessons'    => LessonResource::collection($this->lessons),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
