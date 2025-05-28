<?php

namespace App\Domains\Auth\DTOs;

class RegisterUserDTO
{
    private const ALLOWED_ROLES = ['admin', 'instructor', 'student'];

    public string $name;
    public string $email;
    public string $password;
    public string $role;

    public function __construct(string $name, string $email, string $password, string $role = 'student')
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->role = in_array($role, self::ALLOWED_ROLES) ? $role : 'student';
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            role: $data['role'] ?? 'student'
        );
    }
}
