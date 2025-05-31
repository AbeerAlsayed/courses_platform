<?php

namespace App\Domains\Auth\DTOs;

use App\Domains\Auth\Enums\CourseStatus;
use Illuminate\Validation\ValidationException;

class UpdateInstructorStatusDTO
{
    public int $instructorId;
    public string $status;

    public function __construct(int $instructorId, string $status)
    {
        if (!in_array($status, [CourseStatus::Approved->value, CourseStatus::Rejected->value])) {
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
