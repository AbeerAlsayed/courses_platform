<?php

namespace App\Domains\Interactions\DTOs;

class QuestionDTO
{
    public function __construct(
        public int $userId,
        public int $courseId,
        public string $question,
    ) {}
}
