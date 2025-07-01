<?php

namespace App\Domains\Payments\Actions;

use App\Domains\Payments\DTOs\PaymentData;
use App\Domains\Payments\Models\Payment;
use App\Domains\Courses\Models\Course;
use App\Domains\Auth\Models\User;
use App\Domains\Payments\Enums\PaymentStatus;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Illuminate\Support\Facades\Log;

class StartPaymentAction
{
    public function execute(User $student, Course $course): array
    {
        // 1. التحقق من صحة السعر
        if (!is_numeric($course->price) || $course->price <= 0) {
            throw new \InvalidArgumentException('سعر الكورس غير صالح');
        }

        // 2. إنشاء سجل الدفع في قاعدة البيانات
        $paymentData = new PaymentData(
            user_id: $student->id,
            course_id: $course->id,
            amount: $course->price,
            payment_provider: 'stripe',
            status: PaymentStatus::Pending->value
        );

        $payment = Payment::create($paymentData->toArray());

        try {
            // 3. إعداد مفتاح Stripe السري
            Stripe::setApiKey(config('services.stripe.secret'));

            // 4. التحقق من وجود المفتاح
            if (empty(config('services.stripe.secret'))) {
                throw new \RuntimeException('لم يتم تكوين مفتاح Stripe');
            }

            // 5. إنشاء جلسة الدفع في Stripe
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur', // يمكنك تغيير العملة حسب احتياجك
                        'product_data' => [
                            'name' => $course->title,
                            'metadata' => [
                                'course_id' => $course->id
                            ]
                        ],
                        'unit_amount' => (int)($course->price * 100), // التحويل إلى سنتات
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('payment.success', ['session_id' => '{CHECKOUT_SESSION_ID}']),
                'cancel_url' => route('payment.cancel'),
                'metadata' => [
                    'payment_id' => $payment->id,
                    'user_id' => $student->id
                ],
                'customer_email' => $student->email, // إضافة البريد الإلكتروني للعميل
            ]);

            return [
                'payment' => $payment,
                'checkout_url' => $session->url,
                'session_id' => $session->id
            ];

        } catch (ApiErrorException $e) {
            // 6. معالجة أخطاء Stripe
            Log::error('Stripe Error: ' . $e->getMessage());
            $payment->update(['status' => PaymentStatus::Failed->value]);

            throw new \RuntimeException('حدث خطأ أثناء معالجة الدفع. يرجى المحاولة لاحقاً.');
        } catch (\Exception $e) {
            // 7. معالجة الأخطاء العامة
            Log::error('Payment Processing Error: ' . $e->getMessage());
            $payment->update(['status' => PaymentStatus::Failed->value]);

            throw $e;
        }
    }
}
