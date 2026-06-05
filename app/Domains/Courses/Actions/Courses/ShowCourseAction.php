<?php

namespace App\Domains\Courses\Actions\Courses;

use App\Domains\Auth\Models\User;
use App\Domains\Courses\DTOs\CourseShowResult;
use App\Domains\Courses\Models\Course;

class ShowCourseAction
{
    public function execute(Course $course, ?User $user): CourseShowResult
    {
        $isEnrolled = $user
            ? $course->users()->where('user_id', $user->id)->exists()
            : false;

        $course->load([
            'sections.lessons' => function ($query) use ($isEnrolled) {
                if (! $isEnrolled) {
                    $query->where('is_free', true);
                }
                $query->orderBy('order');
            },
            'standaloneLessons' => function ($query) use ($isEnrolled) {
                if (! $isEnrolled) {
                    $query->where('is_free', true);
                }
                $query->orderBy('order');
            },
            'category',
            'instructor.user',
        ]);

        return new CourseShowResult(course: $course, isEnrolled: $isEnrolled);
    }
}
