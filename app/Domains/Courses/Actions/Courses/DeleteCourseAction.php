<?php

namespace App\Domains\Courses\Actions\Courses;

use App\Domains\Courses\Models\Course;
use App\Domains\Courses\Services\StripeProductService;

class DeleteCourseAction
{
    public function __construct(
        private readonly StripeProductService $stripeService,
    ) {}

    public function execute(Course $course): void
    {
        if ($course->stripe_product_id) {
            $this->stripeService->archiveProduct($course->stripe_product_id);
        }

        $course->clearMediaCollection('course_image');

        $course->delete();
    }
}
