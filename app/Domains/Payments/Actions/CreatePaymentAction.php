<?php

namespace App\Domains\Payments\Actions;

use App\Domains\Payments\DTOs\PaymentData;
use App\Domains\Payments\Models\Payment;
use Illuminate\Support\Facades\DB;

class CreatePaymentAction
{
    public function execute(PaymentData $data): Payment
    {
        // التحقق من عدم شراء الطالب لنفس الكورس من قبل
        $alreadyPurchased = DB::table('course_user')
            ->where('user_id', $data->user_id)
            ->where('course_id', $data->course_id)
            ->exists();

        if ($alreadyPurchased) {
            throw new \Exception("You have already purchased this course.");
        }

        return DB::transaction(function () use ($data) {
            // إنشاء سجل الدفع
            $payment = Payment::create($data->toArray());

            // ربط الطالب بالكورس (اشتراك مدى الحياة)
            DB::table('course_user')->insert([
                'user_id' => $data->user_id,
                'course_id' => $data->course_id,
                'enrolled_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return $payment;
        });
    }
}
