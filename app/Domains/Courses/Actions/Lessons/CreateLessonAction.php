<?php


namespace App\Domains\Courses\Actions\Lessons;

use App\Domains\Courses\Models\Lesson;
use App\Domains\Courses\Models\Section;
use App\Domains\Courses\DTOs\LessonData;
use Illuminate\Http\UploadedFile;

class CreateLessonAction
{
    public function execute(Section $section, LessonData $data, ?UploadedFile $file = null): Lesson
    {
        $lesson = $section->lessons()->create($data->toArray());

        if ($file) {
            $lesson->addMedia($file)->toMediaCollection('lesson_media');
        }

        return $lesson;
    }
}
