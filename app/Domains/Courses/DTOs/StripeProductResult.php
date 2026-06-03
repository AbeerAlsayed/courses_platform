<?php

namespace App\Domains\Courses\DTOs;

final class StripeProductResult
{
    public function __construct(
        public readonly string $productId,
        public readonly string $priceId,
    ) {}
}
