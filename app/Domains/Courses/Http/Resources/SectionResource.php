<?php

namespace App\Domains\Courses\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SectionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'     => $this->id,
            'title'  => $this->title,
            'order'  => $this->order,
            'course' => [
                'id'    => $this->course->id,
                'title' => $this->course->title,
            ],
            'created_at' => $this->created_at,
        ];
    }
}
