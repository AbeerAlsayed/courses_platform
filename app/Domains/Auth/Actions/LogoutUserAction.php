<?php
namespace App\Domains\Auth\Actions;

use App\Domains\Auth\Models\User;

class LogoutUserAction
{
    public function execute(User $user): void
    {
        $user->currentAccessToken()->delete();
    }
}
