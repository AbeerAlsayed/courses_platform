<?php

namespace App\Domains\Auth\Actions;

use App\Domains\Auth\DTOs\RegisterStudentDTO;
use App\Domains\Auth\Models\Student;
use App\Domains\Auth\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterStudentAction
{
    public function execute(RegisterStudentDTO $data): User
    {
        $user = User::create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => Hash::make($data->password),
        ]);

        $user->assignRole('student');

        Student::create([
            'user_id' => $user->id,
            'birth_date' => $data->extra['birth_date'] ?? now()->subYears(20),
        ]);

        return $user;
    }
}
