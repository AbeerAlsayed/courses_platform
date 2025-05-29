<?php

namespace App\Domains\Auth\Actions;

use App\Domains\Auth\DTOs\RegisterInstructorDTO;
use App\Domains\Auth\Models\Instructor;
use App\Domains\Auth\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterInstructorAction
{
    public function execute(RegisterInstructorDTO $data): User
    {
        $user = User::create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => Hash::make($data->password),
        ]);

        $user->assignRole('instructor');

        Instructor::create([
            'user_id' => $user->id,
            'bio' => $data->extra['bio'] ?? 'No bio provided',
            'status' => $data->extra['status'] ?? 'pending',
        ]);

        return $user;
    }
}
