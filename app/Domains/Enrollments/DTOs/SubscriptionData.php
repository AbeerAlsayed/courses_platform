<?php

namespace App\Domains\Subscription\DTOs;

class SubscriptionData
{
    public function __construct(
        public int $studentId,
        public int $courseId,
        public \DateTime $startsAt,
        public \DateTime $endsAt,
    ) {}

    public static function fromRequest($request): self
    {
        return new self(
            studentId: auth()->user()->student->id, // أو من الطلب مباشرة
            courseId: $request->input('course_id'),
            startsAt: now(),
            endsAt: now()->addMonth(), // صلاحية شهر مثلاً
        );
    }
}
