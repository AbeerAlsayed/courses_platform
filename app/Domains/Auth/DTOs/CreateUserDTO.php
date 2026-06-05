<?php

namespace App\Domains\Auth\DTOs;

use App\Domains\Auth\Enums\RegistrationRole;

final class CreateUserDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly RegistrationRole $role,
    ) {}

    public static function fromRegisterDTO(object $dto, RegistrationRole $role): self
    {
        return new self(
            name:     $dto->name,
            email:    $dto->email,
            password: $dto->password,
            role:     $role
        );
    }
}
