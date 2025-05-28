<?php
namespace App\Domains\Auth\Actions;

use App\Domains\Auth\DTOs\RegisterUserDTO;
use App\Domains\Auth\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterUserAction
{
    public function execute(RegisterUserDTO $data): User
    {
        $user = User::create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => Hash::make($data->password),
        ]);

        $user->assignRole($data->role);

        return $user;
    }
}
