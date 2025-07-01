<?php

namespace App\Domains\Payments\DTOs;

use Illuminate\Http\Request;

class PaymentData
{
    public function __construct(
        public int $user_id,
        public int $course_id,
        public float $amount,
        public string $payment_provider = 'stripe',
        public string $status = 'pending',
        public ?string $provider_reference = null,
    ) {}

    /**
     * بناء DTO من كائن Request (من API)
     */
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

    /**
     * بناء DTO من مصفوفة (للاستخدام الداخلي)
     */
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

    /**
     * تحويل الكائن إلى مصفوفة (للتخزين أو التمرير)
     */
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
}
