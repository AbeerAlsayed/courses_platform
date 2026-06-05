<?php

namespace App\Domains\Auth\Enums;

enum RegistrationRole: string
{
    case Student    = 'student';
    case Instructor = 'instructor';
}
