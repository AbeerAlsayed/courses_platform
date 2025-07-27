<?php

namespace App\Domains\Interactions\Actions;

use App\Domains\Courses\Models\Course;
use App\Domains\Interactions\Dtos\CommentDTO;
use App\Domains\Interactions\Models\CourseComment;
use App\Notifications\NewCommentNotification;

class CreateCommentAction
{
    public function execute(CommentDTO $data): Comment
    {
        $comment = CourseComment::create([
            'user_id' => $data->userId,
            'course_id' => $data->courseId,
            'comment' => $data->comment,
        ]);

        $course = Course::find($data->courseId);
        $instructor = $course->instructor;

        $instructor->notify(new NewCommentNotification(
            studentName: auth()->user()->name,
            commentContent: $data->comment,
            courseTitle: $course->title,
    ));

        return $comment;
    }

}
