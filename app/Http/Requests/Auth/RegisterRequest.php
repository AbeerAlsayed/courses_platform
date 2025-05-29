<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // لا توجد صلاحيات مطلوبة للتسجيل
    }

    public function rules(): array
    {
        $role = $this->input('role', 'student');

        return match ($role) {
            'student' => $this->studentRules(),
            'instructor' => $this->instructorRules(),
        };
    }

    protected function studentRules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'unique:users,email'],
            'password'   => ['required', 'string', 'min:4', 'confirmed'],
            'birth_date' => ['required', 'date'],
            'role'       => ['required', 'in:student'],
        ];
    }

    protected function instructorRules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:4', 'confirmed'],
            'bio'      => ['required', 'string', 'max:1000'],
            'role'     => ['required', 'in:instructor'],
        ];
    }
    public function messages()
    {
        return [
            'role.in' => 'The role field must be one of the following:  instructor, or student.',
        ];
    }
}
