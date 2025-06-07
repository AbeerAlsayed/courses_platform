<?php


namespace App\Domains\Courses\Actions\Lessons;

use App\Domains\Courses\Models\Lesson;
use App\Domains\Courses\DTOs\LessonData;
use Illuminate\Http\UploadedFile;

class UpdateLessonAction
{
    public function execute(Lesson $lesson, LessonData $data, ?UploadedFile $file = null): Lesson
    {
        $lesson->update($data->toArray());

        if ($file) {
            $lesson->clearMediaCollection('lesson_media');
            $lesson->addMedia($file)->toMediaCollection('lesson_media');
        }

        return $lesson;
    }
}
