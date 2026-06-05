<?php

namespace App\Domains\Auth\DTOs;

class RegisterInstructorDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public readonly ?string $bio,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            bio:      $data['bio'] ?? null,
        );
    }
}
