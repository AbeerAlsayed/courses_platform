<?php

namespace App\Domains\Auth\Models;

use App\Domains\Courses\Models\Course;
use App\Domains\Enrollments\Models\Enrollment;
use App\Domains\Payments\Models\Payment;
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

    public function courses()
    {
        return $this->belongsToMany(Course::class)->withTimestamps()->withPivot('enrolled_at');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

}
