<?php


namespace App\Domains\Courses\Actions\Courses;

use App\Domains\Courses\DTOs\CourseData;
use App\Domains\Courses\Enums\CourseStatus;
use App\Domains\Courses\Models\Course;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

class CreateCourseAction
{
    public function execute(CourseData $data): Course
    {
        $createData = $data->toArray();

        if (Auth::user()->hasRole('instructor')) {
            $createData['status'] = CourseStatus::Pending->value;
        }

        return Course::create($createData);
    }

}
