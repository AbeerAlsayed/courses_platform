<?php

namespace App\Policies;

use App\Domains\Auth\Models\User;
use App\Domains\Courses\Models\Course;
use Illuminate\Auth\Access\Response;

class CoursePolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Course $course): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Course $course): bool
    {
        return $user->instructor && $course->instructor_id === $user->instructor->id;
    }

    public function delete(User $user, Course $course): bool
    {
        return $user->instructor && $course->instructor_id === $user->instructor->id;
    }
}
