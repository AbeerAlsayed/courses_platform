<?php

namespace App\Domains\Interactions\DTOs;

class CommentDTO
{
    public function __construct(
        public int $userId,
        public int $courseId,
        public string $comment,
    ) {}
}
