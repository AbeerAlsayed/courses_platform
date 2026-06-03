<?php

namespace App\Domains\Auth\Actions;

use App\Domains\Auth\DTOs\RegisterStudentDTO;
use App\Domains\Auth\DTOs\CreateUserDTO;
use App\Domains\Auth\Enums\RegistrationRole;
use App\Domains\Auth\Models\Student;

class RegisterStudentAction
{
    public function __construct(
        protected CreateUserAction $createUserAction
    ) {}

    public function execute(RegisterStudentDTO $data): Student
    {
        $user = $this->createUserAction->execute(
            CreateUserDTO::fromRegisterDTO($data, RegistrationRole::Student)
        );
        return Student::create([
            'user_id'    => $user->id,
            'birth_date' => $data->birthDate,
        ])->load('user');
    }
}
