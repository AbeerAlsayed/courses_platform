<?php

namespace App\Domains\Auth\Actions;

use App\Domains\Auth\DTOs\RegisterInstructorDTO;
use App\Domains\Auth\DTOs\CreateUserDTO;
use App\Domains\Auth\Enums\InstructorStatus;
use App\Domains\Auth\Enums\RegistrationRole;
use App\Domains\Auth\Models\Instructor;
use App\Domains\Auth\Models\User;
use App\Notifications\NewInstructorRegistered;

class RegisterInstructorAction
{
    public function __construct(
        protected CreateUserAction $createUserAction
    ) {}

    public function execute(RegisterInstructorDTO $data): Instructor
    {
        $user = $this->createUserAction->execute(
            CreateUserDTO::fromRegisterDTO($data, RegistrationRole::Instructor)
        );

        $instructor = Instructor::create([
            'user_id' => $user->id,
            'bio'     => $data->bio,
            'status'  => InstructorStatus::Pending,
        ])->load('user');

        $this->notifyAdmins($instructor);

        return $instructor;
    }

    private function notifyAdmins(Instructor $instructor): void
    {
        User::role('admin')
            ->get()
            ->each(fn($admin) => $admin->notify(new NewInstructorRegistered($instructor)));
    }
}
