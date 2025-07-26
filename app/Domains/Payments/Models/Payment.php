<?php

namespace App\Domains\Payments\Models;

use App\Domains\Auth\Models\User;
use App\Domains\Courses\Models\Course;
use App\Domains\Payments\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['user_id', 'course_id', 'amount', 'payment_provider', 'provider_reference', 'status'];

    protected $casts = [
        'amount' => 'decimal:2',
        'status' => PaymentStatus::class
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function isPending(): bool
    {
        return $this->status === PaymentStatus::PENDING;
    }

    public function markAsSucceeded(string $providerReference): bool
    {
        return $this->update([
            'status' => PaymentStatus::SUCCEEDED,
            'provider_reference' => $providerReference
        ]);
    }
}
