<?php

namespace App\Domains\Auth\Actions;

use App\Domains\Auth\DTOs\CreateUserDTO;
use App\Domains\Auth\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUserAction
{
    public function execute(CreateUserDTO $data): User
    {
        $user = User::create([
            'name'     => $data->name,
            'email'    => $data->email,
            'password' => Hash::make($data->password),
        ]);

        $user->assignRole($data->role->value);
        return $user;
    }
}
