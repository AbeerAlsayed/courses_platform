<?php

namespace App\Domains\Courses\Actions\Courses;

use App\Domains\Auth\Models\User;
use App\Domains\Courses\DTOs\CourseData;
use App\Domains\Courses\Enums\CourseStatus;
use App\Domains\Courses\Models\Course;

class UpdateCourseAction
{
    public function execute(Course $course, CourseData $data, User $actor): Course
    {
        $updateData = $data->toArray();

        if ($actor->hasRole('instructor')) {
            $updateData['status'] = CourseStatus::Pending;
        }

        $course->update($updateData);

        return $course->fresh();
    }
}
