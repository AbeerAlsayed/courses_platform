<?php

namespace App\Domains\Courses\Actions\Courses;

use App\Domains\Auth\Models\User;
use App\Domains\Courses\DTOs\CourseData;
use App\Domains\Courses\Enums\CourseStatus;
use App\Domains\Courses\Models\Course;
use App\Domains\Courses\Services\StripeProductService;
use RuntimeException;

class CreateCourseAction
{
    public function __construct(
        private readonly StripeProductService $stripeService,
    ) {}

    /**
     * @param  User  $actor  The authenticated user creating the course
     */
    public function execute(CourseData $data, User $actor): Course
    {
        $courseData = $data->toArray();

        if ($actor->hasRole('instructor')) {

            if (!$actor->instructor) {
                throw new RuntimeException(
                    'Instructor profile not found.'
                );
            }

            $courseData['instructor_id'] = $actor->instructor->id;
            $courseData['status'] = CourseStatus::Pending;
        } else {
            $courseData['status'] = CourseStatus::Approved;
        }
        if ($data->price > 0) {
            $stripe = $this->stripeService->createProduct(
                title:    $data->title,
                price:    $data->price,
            );

            $courseData['stripe_product_id'] = $stripe->productId;
            $courseData['stripe_price_id']   = $stripe->priceId;
        }

        return Course::create($courseData);
    }
}
