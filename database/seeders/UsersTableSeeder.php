<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\Auth\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    // guard المستخدم
    protected $guardName = 'api';

    public function run(): void
    {
        // أنشئ أو احصل على المستخدم وأعطه دور مع تحديد الـ guard
        $this->createUserWithRole('admin@example.com', 'Admin User', 'password', 'admin');
        $this->createUserWithRole('instructor@example.com', 'Instructor User', 'password', 'instructor');
        $this->createUserWithRole('student@example.com', 'Student User', 'password', 'student');
    }

    /**
     * دالة مساعدة لإنشاء مستخدم وتعيين دور له مع تحديد guard
     */
    protected function createUserWithRole(string $email, string $name, string $password, string $role): void
    {
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($password),
            ]
        );

        // تعيين الدور مع تحديد guard (spatie عادة يلتقط guard تلقائياً من الدور نفسه)
        $user->assignRole($role);
    }
}
