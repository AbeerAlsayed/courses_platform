<?php

namespace App\Http\Controllers\Auth;

use App\Domains\Auth\Actions\LoginUserAction;
use App\Domains\Auth\Actions\LogoutUserAction;
use App\Domains\Auth\Actions\RegisterUserAction;
use App\Domains\Auth\DTOs\LoginUserDTO;
use App\Domains\Auth\DTOs\RegisterUserDTO;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(
        protected LoginUserAction $loginUserAction,
        protected LogoutUserAction $logoutUserAction,
        protected RegisterUserAction $registerUserAction
    ) {}

    public function register(RegisterRequest $request)
    {
        $dto = RegisterUserDTO::fromArray($request->validated());

        $user = $this->registerUserAction->execute($dto);

        return successResponse('User registered successfully', $user, 201);
    }

    public function login(LoginRequest $request)
    {
        try {
            $dto = LoginUserDTO::fromArray($request->validated());
            $token = $this->loginUserAction->execute($dto);

            return successResponse('Login successful', ['token' => $token]);
        } catch (ValidationException $e) {
            return errorResponse('Login failed', $e->errors(), 422);
        }
    }

    public function logout(Request $request)
    {
        $this->logoutUserAction->execute($request->user());

        return successResponse('Logged out successfully');
    }

    public function adminDashboard()
    {
        return successResponse('Welcome Admin!');
    }

    public function instructorDashboard()
    {
        return successResponse('Welcome Instructor!');
    }

    public function studentDashboard()
    {
        return successResponse('Welcome Student!');
    }
}
