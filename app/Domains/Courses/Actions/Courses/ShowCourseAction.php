<?php


namespace App\Domains\Courses\Actions\Courses;
use App\Domains\Courses\Enums\CourseStatus;
use App\Domains\Courses\Models\Course;
use App\Notifications\CourseStatusChangedNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ShowCourseAction
{
    public function execute(Course $course): Course
    {
        $user = Auth::user();

        $isEnrolled = $course->users()
            ->where('user_id', optional($user)->id)
            ->exists();

        // تحميل الأقسام مع الدروس (إن كان المستخدم مشتركاً أو الدروس مجانية)
        $course->load([
            'sections.lessons' => function ($query) use ($isEnrolled) {
                if (!$isEnrolled) {
                    $query->where('is_free', true);
                }
                $query->orderBy('order');
            },
            'standaloneLessons' => function ($query) use ($isEnrolled) {
                if (!$isEnrolled) {
                    $query->where('is_free', true);
                }
                $query->orderBy('order');
            },
            'category',
            'instructor.user',
        ]);

        // سنضيف الخاصية للإرجاع لاحقًا من الـ Resource
        $course->is_enrolled = $isEnrolled;

        return $course;
    }
}
