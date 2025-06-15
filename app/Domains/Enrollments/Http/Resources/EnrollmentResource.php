<?php

namespace App\Domains\Enrollments\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EnrollmentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'course_id' => $this->course_id,
            'course_title' => $this->course->title,
            'enrolled_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
