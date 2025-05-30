<?php

namespace App\Http\Controllers\Api\Auth;

use App\Domains\Auth\Actions\LoginUserAction;
use App\Domains\Auth\Actions\LogoutUserAction;
use App\Domains\Auth\Actions\RegisterInstructorAction;
use App\Domains\Auth\Actions\RegisterStudentAction;
use App\Domains\Auth\DTOs\LoginUserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(
        protected LoginUserAction $loginUserAction,
        protected LogoutUserAction $logoutUserAction,
        protected RegisterInstructorAction $registerInstructorAction,
        protected RegisterStudentAction $registerStudentAction,

    ) {}

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $role = $data['role'] ?? 'student';

        $roleActionMap = [
            'student' => RegisterStudentAction::class,
            'instructor' => RegisterInstructorAction::class,
        ];

        if (!array_key_exists($role, $roleActionMap)) {
            abort(403, 'Invalid role.');
        }

        $dtoClass = 'App\\Domains\\Auth\\DTOs\\Register' . ucfirst($role) . 'DTO';
        $dto = $dtoClass::fromArray($data);

        $action = app($roleActionMap[$role]);
        $user = $action->execute($dto);

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
