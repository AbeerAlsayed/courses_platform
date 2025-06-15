<?php

namespace App\Domains\Auth\Models;

use App\Domains\Courses\Models\Course;
use App\Domains\Enrollments\Models\Enrollment;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasRoles;

    protected $guard_name = 'api';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function instructor()
    {
        return $this->hasOne(Instructor::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'user_id');
    }

    public function enrolledCourses()
    {
        return $this->belongsToMany(
            Course::class,
            'enrollments',
            'user_id',
            'course_id'
        )->withTimestamps();
    }

}
