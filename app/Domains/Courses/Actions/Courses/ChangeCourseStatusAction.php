<?php

namespace App\Domains\Courses\Actions\Courses;

use App\Domains\Courses\Enums\CourseStatus;
use App\Domains\Courses\Models\Course;
use App\Notifications\CourseStatusChangedNotification;

class ChangeCourseStatusAction
{
    public function execute(Course $course, CourseStatus $status): Course  // ✅ Typed enum
    {
        $course->update(['status' => $status]);  // ✅ Enum — cast handles serialization

        $course->loadMissing('instructor.user');

        $course->instructor?->user?->notify(
            new CourseStatusChangedNotification($course)
        );

        return $course;
    }
}
