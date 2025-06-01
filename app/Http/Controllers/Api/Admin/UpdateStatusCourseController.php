<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Courses\ApproveCourseRequest;
use App\Domains\Courses\Models\Course;
use App\Http\Resources\CourseResource;
use App\Domains\Courses\Actions\Courses\ChangeCourseStatusAction;

class UpdateStatusCourseController extends Controller
{
    public function updateStatus(ApproveCourseRequest $request, Course $course, ChangeCourseStatusAction $action)
    {
        $updatedCourse = $action->execute($course, $request->validated()['status']);

        return successResponse('Course status updated successfully', new CourseResource($updatedCourse));
    }
}
