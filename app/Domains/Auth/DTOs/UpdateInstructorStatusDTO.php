<?php

namespace App\Domains\Auth\DTOs;

use App\Domains\Auth\Enums\InstructorStatus;
use Illuminate\Validation\ValidationException;

class UpdateInstructorStatusDTO
{
    public function __construct(
        public int $instructorId,
        public InstructorStatus $status
    ) {}

    public static function fromArray(array $data): self
    {
        $status = InstructorStatus::tryFrom($data['status']);

        if (!$status) {
            throw ValidationException::withMessages([
                'status' => 'Invalid instructor status.',
            ]);
        }

        return new self(
            instructorId: $data['instructor_id'],
            status: $status
        );
    }
}

