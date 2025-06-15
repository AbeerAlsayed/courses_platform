<?php

namespace App\Http\Controllers\Api;


use App\Domains\Courses\Models\Course;
use App\Domains\Enrollments\Models\Enrollment;
use App\Domains\Enrollments\Actions\CreateEnrollmentAction;
use App\Domains\Enrollments\Actions\CancelEnrollmentAction;
use App\Domains\Enrollments\Http\Resources\EnrollmentResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function store(Request $request, Course $course, CreateEnrollmentAction $action)
    {
        $user = $request->user();

        if (!$user || !$user->hasRole('student')) {
            return response()->json(['message' => 'Only students can enroll.'], 403);
        }

        try {
            $enrollment = $action->execute($user, $course);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }

        return successResponse('Enrolled successfully', new EnrollmentResource($enrollment));
    }

    public function destroy(Request $request, Course $course, CancelEnrollmentAction $action)
    {
        $user = $request->user();

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment) {
            return response()->json(['message' => 'You are not enrolled in this course.'], 404);
        }

        $action->execute($enrollment);

        return successResponse('Enrollment cancelled successfully');
    }

    public function myEnrollments(Request $request)
    {
        $user = $request->user();

        $enrollments = $user->enrollments()->with('course')->get();

        return successResponse('My enrollments', EnrollmentResource::collection($enrollments));
    }
}
