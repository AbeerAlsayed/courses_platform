<?php
// app/Policies/CoursePolicy.php

namespace App\Policies;

use App\Domains\Auth\Models\User;
use App\Domains\Courses\Models\Course;

class CoursePolicy
{
    public function create(User $user): bool
    {
        return $user->hasRole('instructor')
            && $user->instructor?->status === \App\Domains\Auth\Enums\InstructorStatus::Approved;
    }

    public function update(User $user, Course $course): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->hasRole('instructor')
            && $course->instructor?->user_id === $user->id;
    }

    public function changeStatus(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Course $course): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->hasRole('instructor')
            && $course->instructor?->user_id === $user->id;
    }

    /** Everyone can view approved courses; owners and admins see all */
    public function view(User $user, Course $course): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($course->instructor?->user_id === $user->id) {
            return true; // Owner always sees their own course
        }

        return $course->status === \App\Domains\Courses\Enums\CourseStatus::Approved;
    }
}
