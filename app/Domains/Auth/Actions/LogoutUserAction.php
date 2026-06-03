<?php

namespace App\Domains\Auth\Actions;

use App\Domains\Auth\Models\User;
use Laravel\Sanctum\Contracts\HasApiTokens;

class LogoutUserAction
{
    public function execute(User $user): void
    {
        $token = $user->currentAccessToken();

        if ($token instanceof \Laravel\Sanctum\PersonalAccessToken) {
            $token->delete();
        }
    }
}
