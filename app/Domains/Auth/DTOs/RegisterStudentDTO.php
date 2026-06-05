<?php

namespace App\Domains\Auth\DTOs;

use Carbon\Carbon;

final class RegisterStudentDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly Carbon $birthDate,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name:      $data['name'],
            email:     $data['email'],
            password:  $data['password'],
            birthDate: Carbon::parse($data['birth_date']),
        );
    }
}
