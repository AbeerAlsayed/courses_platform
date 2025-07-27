<?php

namespace App\Domains\Interactions\Actions;

use App\Domains\Interactions\Dtos\RatingDTO;
use App\Domains\Interactions\Models\CourseRating;

class CreateRatingAction
{
    public function execute(RatingDTO $data): CourseRating
    {
        return CourseRating::create([
            'user_id' => $data->userId,
            'course_id' => $data->courseId,
            'rating' => $data->rating,
            'review' => $data->review,
        ]);
    }
}
