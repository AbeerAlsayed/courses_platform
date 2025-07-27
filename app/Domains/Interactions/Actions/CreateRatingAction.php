<?php

namespace App\Domains\Interactions\Actions;

use App\Domains\Courses\Models\Course;
use App\Domains\Interactions\Dtos\RatingDTO;
use App\Domains\Interactions\Models\CourseRating;
use App\Notifications\NewRatingNotification;

class CreateRatingAction
{
    public function execute(RatingDTO $data): CourseRating
    {
        $rating= CourseRating::create([
            'user_id' => $data->userId,
            'course_id' => $data->courseId,
            'rating' => $data->rating,
            'review' => $data->review,
        ]);
        $course = Course::find($data->courseId);
        $instructor = $course->instructor;

        $instructor->notify(new NewRatingNotification(
            studentName: auth()->user()->name,
            commentContent: $data->comment,
            courseTitle: $course->title,
    ));

        return $rating;
    }
}
