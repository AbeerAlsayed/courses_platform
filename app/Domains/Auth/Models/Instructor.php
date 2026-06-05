<?php

namespace App\Domains\Auth\Models;

use App\Domains\Auth\Enums\InstructorStatus;
use App\Domains\Courses\Models\Course;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Instructor extends Model
{
    protected $fillable = [
        'user_id',
        'bio',
        'status',
    ];

    protected $casts = [
        'status' => InstructorStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }
}
