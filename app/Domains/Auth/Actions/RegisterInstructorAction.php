<?php

namespace App\Domains\Auth\Actions;

use App\Domains\Auth\DTOs\RegisterInstructorDTO;
use App\Domains\Auth\Enums\InstructorStatus;
use App\Domains\Auth\Models\Instructor;
use App\Domains\Auth\Models\User;
use App\Notifications\NewInstructorRegistered;
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

        $instructor = Instructor::create([
            'user_id' => $user->id,
            'bio' => $data->bio,
            'status' => InstructorStatus::Pending->value,
        ]);

        User::role('admin')->get()->each(function ($admin) use ($instructor) {
            $admin->notify(new NewInstructorRegistered($instructor));
        });

        return $user;
    }
}
