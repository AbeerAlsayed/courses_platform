<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    // تعريف الـ guard المستخدم
    protected $guardName = 'api';

    public function run(): void
    {
        // مسح الكاش للصلاحيات والأدوار لتجنب التعارض
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // قائمة الصلاحيات
        $permissions = [
            'view courses',
            'create courses',
            'edit courses',
            'delete courses',
            'manage users',
        ];

        // إنشاء الصلاحيات أو التأكد من وجودها مع تعيين الـ guard
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => $this->guardName,
            ]);
        }

        // إنشاء الأدوار مع guard معين
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => $this->guardName]);
        $student = Role::firstOrCreate(['name' => 'student', 'guard_name' => $this->guardName]);
        $instructor = Role::firstOrCreate(['name' => 'instructor', 'guard_name' => $this->guardName]);

        // تعيين الصلاحيات لكل دور
        $admin->syncPermissions(Permission::where('guard_name', $this->guardName)->get());
        $student->syncPermissions(['view courses']);
        $instructor->syncPermissions(['view courses', 'create courses', 'edit courses']);
    }
}
