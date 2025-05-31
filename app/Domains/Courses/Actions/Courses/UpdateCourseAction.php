<?php

namespace App\Domains\Courses\Actions\Courses;

use App\Domains\Courses\DTOs\CourseData;
use App\Domains\Courses\Enums\CourseStatus;
use App\Domains\Courses\Models\Course;

class UpdateCourseAction
{
    public function execute(Course $course, CourseData $data): Course
    {
        $updateData = $data->toArray();

        if (auth()->user()->hasRole('instructor')) {
            unset($updateData['status']);
            $updateData['status'] = CourseStatus::Pending->value;
        }

        $course->update($updateData);
        return $course;
    }

}
