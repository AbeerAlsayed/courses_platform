<?php

namespace App\Domains\Courses\Enums;

enum CourseStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';
}
