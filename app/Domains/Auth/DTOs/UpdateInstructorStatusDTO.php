<?php

namespace App\Domains\Auth\DTOs;

use App\Domains\Auth\Enums\InstructorStatus;

final class UpdateInstructorStatusDTO
{
    public function __construct(
        public readonly InstructorStatus $status,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            status: InstructorStatus::from($data['status']),
        );
    }
}
