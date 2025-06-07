<?php

namespace App\Http\Controllers\Api;

use App\Domains\Auth\Models\User;
use App\Domains\Courses\Actions\Courses\CreateCourseAction;
use App\Domains\Courses\Actions\Courses\DeleteCourseAction;
use App\Domains\Courses\Actions\Courses\UpdateCourseAction;
use App\Domains\Courses\DTOs\CourseData;
use App\Domains\Courses\Http\Resources\CourseResource;
use App\Domains\Courses\Models\Course;
use App\Http\Controllers\Controller;
use App\Http\Requests\Courses\StoreCourseRequest;
use App\Http\Requests\Courses\UpdateCourseRequest;
use App\Notifications\CoursePendingApproval;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Notification;

class CourseController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $courses = Course::paginate(10);
        return successResponse('My Courses', CourseResource::collection($courses));
    }

    public function store(StoreCourseRequest $request, CreateCourseAction $createCourseAction)
    {
        $dto = CourseData::fromArray($request->validated() + ['instructor_id' => auth()->user()->instructor->id]);
        $course = $createCourseAction->execute($dto);

        if ($request->hasFile('image')) {
            $course->addMediaFromRequest('image')->toMediaCollection('cover');
        }

        $admins = User::role('admin')->get();
        Notification::send($admins, new CoursePendingApproval($course));

        return successResponse('Course submitted for review', new CourseResource($course));
    }

    public function update(UpdateCourseRequest $request, Course $course, UpdateCourseAction $updateCourseAction)
    {
        $this->authorize('update', $course);

        $dto = CourseData::fromArray($request->validated());
        $course = $updateCourseAction->execute($course, $dto);

        if ($request->hasFile('image')) {
            $course->addMediaFromRequest('image')->toMediaCollection('cover');
        }

        return successResponse('Course updated successfully', new CourseResource($course));
    }

    public function destroy(Course $course, DeleteCourseAction $deleteCourseAction)
    {
        $this->authorize('update', $course);

        $deleteCourseAction->execute($course);

        return successResponse('Course deleted successfully');
    }

    public function show(Course $course)
    {
        return successResponse('Course details', new CourseResource($course));
    }
}
