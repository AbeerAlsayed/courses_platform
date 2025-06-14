<?php

namespace App\Http\Controllers\Api;

use App\Domains\Courses\Models\Course;
use App\Http\Controllers\Controller;
use App\Domains\Courses\Models\Section;
use App\Domains\Courses\Models\Lesson;
use App\Domains\Courses\DTOs\LessonData;
use App\Domains\Courses\Actions\Lessons\CreateLessonAction;
use App\Domains\Courses\Actions\Lessons\UpdateLessonAction;
use App\Domains\Courses\Actions\Lessons\DeleteLessonAction;
use App\Http\Requests\Lessons\StoreLessonRequest;
use App\Http\Requests\Lessons\UpdateLessonRequest;
use App\Domains\Courses\Http\Resources\LessonResource;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    /**
     * GET /courses/{course}/sections/{section}/lessons
     * عرض قائمة الدروس في قسم محدد.
     */
    public function index(Section $section)
    {
        $lessons = $section->lessons()->orderBy('order')->get();
        return LessonResource::collection($lessons);
    }

    /**
     * GET /courses/{course}/sections/{section}/lessons/{lesson}
     * عرض درس واحد بعد التحقق من الصلاحية:
     * - لو الدرس مجاني، يسمح لأي مستخدم (حتى ضيف).
     * - لو الدرس غير مجاني، يجب أن يكون المستخدم مسجّلًا في الكورس.
     */
    public function show(Request $request, Section $section, Lesson $lesson)
    {
        // 1. إذا الدرس مجاني => عرض
        if ($lesson->is_free) {
            return new LessonResource($lesson);
        }

        // 2. الدرس غير مجاني => يجب تكون مسجّل دخول
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized. Please log in to view this lesson.'], 401);
        }

        // 3. تحقق من ان المستخدم مسجّل في الكورس
        //    يفترض أن في موديل Course علاقة students(): belongsToMany(User::class, 'course_user', 'course_id', 'user_id').
        $course = $section->course;
        $isEnrolled = $course->students()
            ->where('user_id', $user->id)
            ->exists();

        if (!$isEnrolled) {
            return response()->json(['message' => 'Forbidden. You are not enrolled in this course.'], 403);
        }

        return new LessonResource($lesson);
    }

    /**
     * POST /courses/{course}/sections/{section}/lessons
     * إنشاء درس جديد (فقط لصاحب الكورس).
     */


    public function store($courseId, $sectionOrder, StoreLessonRequest $request, CreateLessonAction $action)
    {
        $course = Course::findOrFail($courseId);
        $user = auth()->user();

        // نبحث عن القسم بناءً على course_id + order
        $section = Section::where('course_id', $courseId)
            ->where('order', $sectionOrder)
            ->first();

        if (!$section) {
            return response()->json(['message' => 'Section not found in this course.'], 404);
        }

        // التحقق أن المستخدم هو صاحب الكورس
        if ($course->instructor_id !== optional($user->instructor)->id) {
            return response()->json(['message' => 'Forbidden. You are not the course owner.'], 403);
        }

        // تحويل البيانات
        $data = LessonData::fromArray($request->validated());
        $file = $request->file('file');

        // تنفيذ عملية إنشاء الدرس
        $lesson = $action->execute($section, $data, $file);

        return successResponse('Lesson created successfully', new LessonResource($lesson));
    }

    /**
     * PUT /courses/{course}/sections/{section}/lessons/{lesson}
     * تعديل درس (فقط لصاحب الكورس).
     */
    public function update(Section $section, Lesson $lesson, UpdateLessonRequest $request, UpdateLessonAction $action) {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized.'], 401);
        }

        $course = $section->course;
        // فقط صاحب الكورس (instructor) له صلاحية تعديل الدرس
        if ($course->instructor_id !== optional($user->instructor)->id) {
            return response()->json(['message' => 'Forbidden. You are not the course owner.'], 403);
        }

        $data = LessonData::fromArray($request->validated());
        $file = $request->file('file'); // المفتاح نفسه في الـ Request

        $lesson = $action->execute($lesson, $data, $file);

        return successResponse('Lesson updated successfully', new LessonResource($lesson));
    }

    /**
     * DELETE /courses/{course}/sections/{section}/lessons/{lesson}
     * حذف درس (فقط لصاحب الكورس).
     */
    public function destroy(Section $section, Lesson $lesson, DeleteLessonAction $action) {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized.'], 401);
        }

        $course = $section->course;
        // فقط صاحب الكورس (instructor) له صلاحية حذف الدرس
        if ($course->instructor_id !== optional($user->instructor)->id) {
            return response()->json(['message' => 'Forbidden. You are not the course owner.'], 403);
        }

        $action->execute($lesson);

        return successResponse('Lesson deleted successfully');
    }
}
