<?php

namespace App\Domains\Payments\Actions;

use App\Domains\Payments\DTOs\PaymentData;
use App\Domains\Payments\Models\Payment;
use App\Domains\Payments\Exceptions\CourseAlreadyPurchasedException;
use Illuminate\Support\Facades\DB;

class CreatePaymentAction
{
    /**
     * @throws CourseAlreadyPurchasedException
     */
    public function execute(PaymentData $data): Payment
    {
        $this->ensureCourseNotPurchased($data->user_id, $data->course_id);

        return DB::transaction(function () use ($data) {
            $payment = $this->createPaymentRecord($data);
            $this->createEnrollment($data->user_id, $data->course_id);
            return $payment;
        });
    }

    /**
     * @throws CourseAlreadyPurchasedException
     */
    protected function ensureCourseNotPurchased(int $userId, int $courseId): void
    {
        if ($this->isCoursePurchased($userId, $courseId)) {
            throw new CourseAlreadyPurchasedException();
        }
    }

    protected function isCoursePurchased(int $userId, int $courseId): bool
    {
        return DB::table('course_user')
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->exists();
    }

    protected function createPaymentRecord(PaymentData $data): Payment
    {
        return Payment::create($data->toArray());
    }

    protected function createEnrollment(int $userId, int $courseId): void
    {
        DB::table('course_user')->insert([
            'user_id' => $userId,
            'course_id' => $courseId,
            'enrolled_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
