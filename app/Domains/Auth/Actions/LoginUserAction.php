<?php

namespace App\Domains\Auth\Actions;

use App\Domains\Auth\Services\AuthService;
use App\Domains\Auth\DTOs\LoginUserDTO;

class LoginUserAction
{
    public function __construct(
        protected AuthService $authService
    ) {}

    public function execute(LoginUserDTO $data): array
    {
        return $this->authService->login($data);
    }
}
