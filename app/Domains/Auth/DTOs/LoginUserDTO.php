<?php

namespace App\Domains\Auth\DTOs;

class LoginUserDTO
{
    public string $email;
    public string $password;

    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public static function fromArray(array $data): self
    {
        if (!isset($data['email'], $data['password'])) {
            throw new \InvalidArgumentException("Missing email or password");
        }

        return new self(
            email: $data['email'],
            password: $data['password']
        );
    }
}
