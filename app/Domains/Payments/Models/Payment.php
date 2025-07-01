<?php

namespace App\Domains\Payments\Models;

use App\Domains\Auth\Models\User;
use App\Domains\Courses\Models\Course;
use App\Domains\Payments\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id', 'course_id', 'amount',
        'payment_provider', 'provider_reference', 'status'
    ];

    protected $casts = [
        'status' => PaymentStatus::class,
        'amount' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
