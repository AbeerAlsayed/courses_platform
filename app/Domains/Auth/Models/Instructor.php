<?php

namespace App\Domains\Auth\Models;


use App\Domains\Auth\Enums\InstructorStatus;
use App\Domains\Courses\Models\Course;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    protected $fillable = [
        'user_id', 'bio', 'status',
    ];

    protected $casts = [
        'status' => InstructorStatus::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
