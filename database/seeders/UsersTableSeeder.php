<?php

namespace Database\Seeders;

use App\Domains\Auth\Models\Instructor;
use App\Domains\Auth\Models\Student;
use Illuminate\Database\Seeder;
use App\Domains\Auth\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    protected $guardName = 'api';

    public function run(): void
    {
        $this->createUserWithRole('admin@example.com', 'Admin User', 'password', 'admin');
        $this->createUserWithRole('instructor@example.com', 'Instructor User', 'password', 'instructor');
        $this->createUserWithRole('student@example.com', 'Student User', 'password', 'student');
    }

    protected function createUserWithRole(string $email, string $name, string $password, string $role): void
    {
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($password),
            ]
        );

        $user->assignRole($role);

        match ($role) {
            'student' => Student::firstOrCreate(
                ['user_id' => $user->id],
                ['birth_date' => now()->subYears(22)]
            ),
            'instructor' => Instructor::firstOrCreate(
                ['user_id' => $user->id],
                ['bio' => 'Experienced instructor', 'status' => 'approved']
            ),
            default => null,
        };
    }
}
