<?php

namespace App\Policies;

use App\Domains\Auth\Models\User;
use App\Domains\Courses\Models\Lesson;

use Illuminate\Auth\Access\HandlesAuthorization;

class LessonPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the lesson.
     * أي مستخدم مسجل يمكنه مشاهدة الدرس.
     */
    public function view(User $user, Lesson $lesson): bool
    {
        // ممكن تضيف شروط إضافية مثل أن يكون الدرس مجاني أو مشتراه
        return true;
    }

    /**
     * Determine whether the user can create lessons in the given section.
     */
    public function create(User $user, $section): bool
    {
        $course = $section->course;
        // فقط صاحب الكورس (المدرب) يمكنه إنشاء الدروس
        return $course->instructor_id === optional($user->instructor)->id;
    }

    /**
     * Determine whether the user can update the lesson.
     */
    public function update(User $user, Lesson $lesson): bool
    {
        $course = $lesson->section->course;
        // فقط صاحب الكورس (المدرب) يمكنه تعديل الدرس
        return $course->instructor_id === optional($user->instructor)->id;
    }

    /**
     * Determine whether the user can delete the lesson.
     */
    public function delete(User $user, Lesson $lesson): bool
    {
        $course = $lesson->section->course;
        // فقط صاحب الكورس (المدرب) يمكنه حذف الدرس
        return $course->instructor_id === optional($user->instructor)->id;
    }
}
