<?php

namespace App\Domains\Auth\DTOs;

use App\Domains\Auth\Enums\InstructorStatus;
use Illuminate\Validation\ValidationException;

class UpdateInstructorStatusDTO
{
    public int $instructorId;
    public string $status;

    public function __construct(int $instructorId, string $status)
    {
        if (!in_array($status, [InstructorStatus::Approved->value, InstructorStatus::Rejected->value])) {
            throw ValidationException::withMessages([
                'status' => 'Invalid instructor status.',
            ]);
        }

        $this->instructorId = $instructorId;
        $this->status = $status;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            instructorId: $data['instructor_id'],
            status: $data['status']
        );
    }
}
