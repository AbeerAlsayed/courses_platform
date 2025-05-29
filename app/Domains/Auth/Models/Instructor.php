<?php

namespace App\Domains\Auth\Models;

use App\Domains\Auth\Enums\InstructorStatus;
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
}
