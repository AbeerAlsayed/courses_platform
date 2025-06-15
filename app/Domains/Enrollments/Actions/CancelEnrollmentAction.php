<?php

namespace App\Domains\Enrollments\Actions;

use App\Domains\Enrollments\Models\Enrollment;

class CancelEnrollmentAction
{
    public function execute(Enrollment $enrollment): void
    {
        $enrollment->delete();
    }
}
