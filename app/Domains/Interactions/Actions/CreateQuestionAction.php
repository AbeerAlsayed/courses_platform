<?php

namespace App\Domains\Interactions\Actions;

use App\Domains\Interactions\DTOs\QuestionDTO;
use App\Domains\Interactions\Models\CourseQuestion;

class CreateQuestionAction
{
    public function execute(QuestionDTO $data): CourseQuestion
    {
        return CourseQuestion::create([
            'user_id' => $data->userId,
            'course_id' => $data->courseId,
            'question' => $data->question,
        ]);
    }
}
