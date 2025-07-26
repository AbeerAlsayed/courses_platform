<?php

namespace App\Domains\Auth\Services;

use App\Domains\Auth\DTOs\LoginUserDTO;
use App\Domains\Auth\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use App\Domains\Auth\Enums\InstructorStatus;

class AuthService
{
    public function login(LoginUserDTO $data): array
    {
        $user = User::where('email', $data->email)->first();

        if (!$user || !Hash::check($data->password, $user->password)) {
            throw ValidationException::withMessages([
                'credentials' => ['The provided credentials are incorrect.'],
            ]);
        }

        // ✅ التحقق من حالة المدرس إن وجد
        if ($user->hasRole('instructor')) {
            $instructor = $user->instructor;

            if ($instructor && $instructor->status !== InstructorStatus::Approved->value) {
                throw ValidationException::withMessages([
                    'account' => ['Your instructor account is pending approval.'],
                ]);
            }
        }

        return [
            'user' => $user,
            'token' => $user->createToken('auth_token')->plainTextToken,
        ];
    }
}
