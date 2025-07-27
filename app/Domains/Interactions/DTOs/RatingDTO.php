<?php

namespace App\Domains\Interactions\DTOs;

class RatingDTO
{
    public function __construct(
        public int $userId,
        public int $courseId,
        public int $rating,
        public ?string $review = null,
    ) {}
}
