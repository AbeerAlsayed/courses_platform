<?php

namespace App\Domains\Interactions\Actions;

use App\Domains\Interactions\Dtos\CommentDTO;
use App\Domains\Interactions\Models\CourseComment;

class CreateCommentAction
{
    public function execute(CommentDTO $data): CourseComment
    {
        return CourseComment::create([
            'user_id' => $data->userId,
            'course_id' => $data->courseId,
            'comment' => $data->comment,
        ]);
    }
}
