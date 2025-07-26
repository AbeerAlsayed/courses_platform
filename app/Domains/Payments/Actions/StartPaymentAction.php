<?php

namespace App\Domains\Payments\Actions;

use App\Domains\Payments\DTOs\PaymentData;
use App\Domains\Payments\DTOs\ProcessStripePaymentResponse;
use App\Domains\Payments\Enums\PaymentStatus;
use App\Domains\Payments\Models\Payment;
use App\Domains\Payments\Services\StripePaymentService;
use App\Domains\Payments\Exceptions\InvalidCoursePriceException;
use App\Domains\Courses\Models\Course;
use App\Domains\Auth\Models\User;

class StartPaymentAction
{
    public function __construct(
        private StripePaymentService $stripeService,
        private CreatePaymentAction $createPaymentAction
    ) {}

    public function execute(User $student, Course $course): ProcessStripePaymentResponse
    {
        $this->validateCourse($course);

        // 1. أنشئ سجل الدفع في قاعدة البيانات
        $payment = $this->createPayment($student, $course);

        // 2. ابدأ جلسة Stripe Checkout
        return $this->initiateStripePayment($payment, $course, $student);
    }

    protected function validateCourse(Course $course): void
    {
        if (!is_numeric($course->price) || $course->price <= 0) {
            throw new InvalidCoursePriceException($course);
        }

        if (empty($course->stripe_price_id)) {
            throw new \DomainException('Course is not properly configured for payments');
        }
    }

    protected function createPayment(User $student, Course $course): Payment
    {
        $paymentData = new PaymentData(
            user_id: $student->id,
            course_id: $course->id,
            amount: $course->price
        );

        return $this->createPaymentAction->execute($paymentData);
    }

    protected function initiateStripePayment(Payment $payment, Course $course, User $student): ProcessStripePaymentResponse
    {
        try {
            $session = $this->stripeService->createCheckoutSession([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price' => $course->stripe_price_id,
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('payment.success', ['payment' => $payment->id]),
                'cancel_url' => route('payment.cancel', ['payment' => $payment->id]),
                'metadata' => [
                    'payment_id' => $payment->id,
                    'user_id' => $student->id,
                    'course_id' => $course->id
                ],
                'customer_email' => $student->email,
            ]);

            return new ProcessStripePaymentResponse(
                paymentId: $payment->id,
                status: 'requires_payment_method',
                redirectUrl: $session->url
            );
        } catch (\Exception $e) {
            $payment->update(['status' => PaymentStatus::FAILED->value]);
            throw $e;
        }
    }
}
