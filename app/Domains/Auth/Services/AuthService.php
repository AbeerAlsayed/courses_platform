<?php

namespace App\Domains\Auth\Services;

use App\Domains\Auth\DTOs\LoginUserDTO;
use App\Domains\Auth\Enums\InstructorStatus;
use App\Domains\Auth\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    private const TOKEN_NAME = 'auth_token';

    public function login(LoginUserDTO $data): array
    {
        $user = User::with('instructor')
        ->where('email', $data->email)
            ->first();

        $this->validateCredentials($user, $data->password);
        $this->validateInstructorStatus($user);

        return [
            'user'  => $user,
            'token' => $user->createToken(self::TOKEN_NAME)->plainTextToken,
        ];
    }

    private function validateCredentials(?User $user, string $password): void
    {
        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'credentials' => ['The provided credentials are incorrect.'],
            ]);
        }
    }

    private function validateInstructorStatus(User $user): void
    {
        if (! $user->hasRole('instructor')) {
            return;
        }

        $instructor = $user->instructor;

        if ($instructor && $instructor->status !== InstructorStatus::Approved) {
            throw ValidationException::withMessages([
                'account' => ['Your instructor account is pending approval.'],
            ]);
        }
    }
}
