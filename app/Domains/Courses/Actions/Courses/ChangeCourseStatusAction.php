<?php


namespace App\Domains\Courses\Actions\Courses;
use App\Domains\Courses\Enums\CourseStatus;
use App\Notifications\CourseStatusChanged;

class ChangeCourseStatusAction
{
    public function execute(Course $course, string $status): Course
    {
        if (!in_array($status, [CourseStatus::Approved->value, CourseStatus::Rejected->value])) {
            throw new \InvalidArgumentException('Invalid status.');
        }

        $course->status = $status;
        $course->save();


        $instructor = $course->instructor?->user;
        if ($instructor) {
            $instructor->notify(new CourseStatusChanged($course));
        }

        return $course;
    }
}
