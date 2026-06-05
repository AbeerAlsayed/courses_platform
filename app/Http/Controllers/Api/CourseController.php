<?php

namespace App\Http\Controllers\Api;

use App\Domains\Courses\Actions\Courses\ChangeCourseStatusAction;
use App\Domains\Courses\Actions\Courses\CreateCourseAction;
use App\Domains\Courses\Actions\Courses\DeleteCourseAction;
use App\Domains\Courses\Actions\Courses\ListCoursesAction;
use App\Domains\Courses\Actions\Courses\ShowCourseAction;
use App\Domains\Courses\Actions\Courses\UpdateCourseAction;
use App\Domains\Courses\DTOs\CourseData;
use App\Domains\Courses\Enums\CourseStatus;
use App\Domains\Courses\Models\Course;
use App\Http\Controllers\Controller;
use App\Http\Requests\Courses\ChangeCourseStatusRequest;
use App\Http\Requests\Courses\CreateCourseRequest;
use App\Http\Requests\Courses\UpdateCourseRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CourseController extends Controller
{
    use AuthorizesRequests;
    public function __construct(
        private readonly CreateCourseAction      $createCourseAction,
        private readonly UpdateCourseAction      $updateCourseAction,
        private readonly DeleteCourseAction      $deleteCourseAction,
        private readonly ShowCourseAction        $showCourseAction,
        private readonly ChangeCourseStatusAction $changeCourseStatusAction,
        private readonly ListCoursesAction $listCoursesAction,

    ) {}

    public function index(): JsonResponse
    {
        $courses = $this->listCoursesAction->execute();

        return successResponse(
            'Courses retrieved successfully.',
            $courses
        );
    }
    public function store(CreateCourseRequest $request): JsonResponse
    {
        $this->authorize('create', Course::class);

        $course = $this->createCourseAction->execute(
            data:  CourseData::fromArray($request->validated()),
            actor: $request->user(),              // ✅ Injected — no Auth facade
        );

        return successResponse('Course created successfully.', $course, 201);
    }

    public function update(UpdateCourseRequest $request, Course $course): JsonResponse
    {
        $this->authorize('update', $course);

        $updated = $this->updateCourseAction->execute(
            course: $course,
            data:   CourseData::fromArray($request->validated()),
            actor:  $request->user(),
        );

        return successResponse('Course updated successfully.', $updated);
    }

    public function destroy(Course $course): JsonResponse
    {
        $this->authorize('delete', $course);

        $this->deleteCourseAction->execute($course);

        return successResponse('Course deleted successfully.');
    }

    public function show(Request $request, Course $course): JsonResponse
    {
        $this->authorize('view', $course);

        $result = $this->showCourseAction->execute(
            course: $course,
            user:   $request->user(),   // nullable — guests return only free lessons
        );

        return successResponse('Course retrieved.', [
            'course'      => $result->course,
            'is_enrolled' => $result->isEnrolled,
        ]);
    }

    public function changeStatus(ChangeCourseStatusRequest $request, Course $course): JsonResponse
    {
        $this->authorize('changeStatus', $course);

        $updated = $this->changeCourseStatusAction->execute(
            course: $course,
            status: CourseStatus::from($request->validated('status')),  // ✅ Typed enum
        );

        return successResponse('Course status updated.', $updated);
    }
}
