<?php

namespace App\Domains\Auth\Actions;

use App\Domains\Auth\DTOs\LoginUserDTO;
use App\Domains\Auth\Services\AuthService;

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
