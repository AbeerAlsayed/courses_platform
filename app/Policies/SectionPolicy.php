<?php

namespace App\Policies;

use App\Domains\Auth\Models\User;
use App\Domains\Courses\Models\Section;

class SectionPolicy
{
    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('instructor');
    }

    public function update(User $user, Section $section): bool
    {
        return $user->hasRole('admin') ||
            $section->course->instructor_id === optional($user->instructor)->id;
    }


    public function delete(User $user, Section $section): bool
    {
        return $this->update($user, $section);
    }
}
