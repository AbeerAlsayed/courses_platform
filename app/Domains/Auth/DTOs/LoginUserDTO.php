<?php

namespace App\Domains\Auth\DTOs;

use Illuminate\Http\Request;

class LoginUserDTO
{
    public function __construct(
        public string $email,
        public string $password
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            email: $data['email'],
            password: $data['password']
        );
    }

    public static function fromRequest(Request $request): self
    {
        return self::fromArray($request->validated());
    }
}
