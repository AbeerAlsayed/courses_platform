<?php

namespace App\Domains\Auth\Enums;

enum InstructorStatus: string
{
    case Pending  = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';


    public function label(): string
    {
        return match($this) {
            self::Pending  => 'Pending Review',
            self::Approved => 'Approved',
            self::Rejected => 'Rejected',
        };
    }

    public function isTerminal(): bool
    {
        return match($this) {
            self::Approved, self::Rejected => true,
            self::Pending                  => false,
        };
    }

    public static function assignableByAdmin(): array
    {
        return [self::Approved, self::Rejected];
    }
}
