<?php

namespace App\Domains\Payments\DTOs;

use App\Domains\Payments\Enums\PaymentProvider;
use App\Domains\Payments\Enums\PaymentStatus;
use Illuminate\Http\Request;

class PaymentData
{
    public function __construct(
        public readonly int $user_id,
        public readonly int $course_id,
        public readonly float $amount,
        public readonly PaymentProvider $payment_provider = PaymentProvider::STRIPE,
        public readonly PaymentStatus $status = PaymentStatus::PENDING,
        public readonly ?string $provider_reference = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            user_id: $data['user_id'],
            course_id: $data['course_id'],
            amount: $data['amount'],
            payment_provider: $data['payment_provider'] ?? 'stripe',
            status: $data['status'] ?? 'pending',
            provider_reference: $data['provider_reference'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->user_id,
            'course_id' => $this->course_id,
            'amount' => $this->amount,
            'payment_provider' => $this->payment_provider,
            'status' => $this->status,
            'provider_reference' => $this->provider_reference,
        ];
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            user_id: $request->user()->id,
            course_id: $request->course_id,
            amount: $request->amount,
            payment_provider: $request->payment_provider ?? 'stripe',
            status: $request->status ?? 'pending',
            provider_reference: $request->provider_reference
        );
    }

}
