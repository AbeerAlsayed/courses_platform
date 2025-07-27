<?php

namespace App\Domains\Enrollments\Models;

use App\Domains\Auth\Models\User;
use App\Domains\Courses\Models\Course;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $fillable = ['user_id', 'course_id'];

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }


}
