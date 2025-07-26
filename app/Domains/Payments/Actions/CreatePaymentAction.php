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
            // إنشاء سجل الدفع فقط، بدون تسجيل المستخدم في الكورس

            return $this->createPaymentRecord($data);
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
}
