<?php

namespace App\Domains\Auth\DTOs;

class RegisterInstructorDTO
{
    public string $name;
    public string $email;
    public string $password;
    public string $bio;
    public string $status;

    public function __construct(string $name, string $email, string $password, string $bio, string $status = 'pending')
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->bio = $bio;
        $this->status = $status;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            bio: $data['bio'] ?? 'No bio provided',
            status: $data['status'] ?? 'pending',
        );
    }
}
