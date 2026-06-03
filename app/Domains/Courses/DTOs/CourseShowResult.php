<?php

namespace App\Domains\Courses\DTOs;

use App\Domains\Courses\Models\Course;

final class CourseShowResult
{
    public function __construct(
        public readonly Course $course,
        public readonly bool   $isEnrolled,
    ) {}
}
