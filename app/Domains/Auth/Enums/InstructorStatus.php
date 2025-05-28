<?php

namespace App\Domains\Auth\Enums;

enum InstructorStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';
}
