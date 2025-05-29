<?php

namespace App\Domains\Auth\DTOs;

class RegisterStudentDTO
{
    public string $name;
    public string $email;
    public string $password;
    public string $birthDate;

    public function __construct(string $name, string $email, string $password, string $birthDate)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->birthDate = $birthDate;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            birthDate: $data['birth_date'] ?? now()->subYears(20)->toDateString()
        );
    }
}
