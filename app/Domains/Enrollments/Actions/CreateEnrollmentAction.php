<?php

namespace App\Domains\Enrollments\Actions;

use App\Domains\Courses\Models\Course;
use App\Domains\Auth\Models\User;
use App\Domains\Courses\Enums\CourseStatus;
use App\Domains\Enrollments\Models\Enrollment;
use Exception;

class CreateEnrollmentAction
{
    public function execute(User $student, Course $course): Enrollment
    {
        if (!$student->hasRole('student')) {
            throw new Exception("Only students can enroll.");
        }

        if ($course->status !== CourseStatus::Approved) {
            throw new Exception("Cannot enroll in a course that is not approved.");
        }

        if ($course->students()->where('user_id', $student->id)->exists()) {
            throw new Exception("You are already enrolled in this course.");
        }

        return Enrollment::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
        ]);
    }
}
