<?php

namespace App\Domains\Courses\Actions\Lessons;

use App\Domains\Courses\Models\Lesson;

class DeleteLessonAction
{
    public function execute(Lesson $lesson): void
    {
        $lesson->delete();
    }
}
