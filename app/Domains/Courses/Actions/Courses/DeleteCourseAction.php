<?php

namespace App\Domains\Courses\Actions\Courses;

use App\Domains\Courses\Models\Course;

class DeleteCourseAction
{
    public function execute(Course $course): void
    {
        $course->delete();
    }
}
