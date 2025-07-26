<?php

namespace App\Domains\Payments\DTOs;

class ProcessStripePaymentResponse
{
    public function __construct(
        public string $paymentId,
        public string $status,
        public ?string $redirectUrl = null,
        public ?string $error = null
    ) {}
}
