<?php


namespace App\Domains\Courses\Actions\Courses;
use App\Domains\Courses\Enums\CourseStatus;
use App\Domains\Courses\Models\Course;
use App\Notifications\CourseStatusChangedNotification;
use Illuminate\Validation\ValidationException;

class ChangeCourseStatusAction
{
    public function execute(Course $course, string $status): Course
    {
        if (!in_array($status, [CourseStatus::Approved->value, CourseStatus::Rejected->value])) {
            throw ValidationException::withMessages([
                'status' => 'Invalid course status.',
            ]);
        }

        $course->status = $status;
        $course->save();

        $course->loadMissing('instructor.user');
        $instructor = $course->instructor?->user;
        if ($instructor) {
            $instructor->notify(new CourseStatusChangedNotification($course));
        }

        return $course;
    }
}
