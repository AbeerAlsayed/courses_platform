<?php

namespace App\Domains\Cart\DTOs;

class AddToCartData
{
    public function __construct(
        public readonly string|int|null $userId,
        public readonly int $courseId
    ) {}
}
