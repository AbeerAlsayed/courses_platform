<?php

namespace App\Http\Controllers\Api;

use App\Domains\Payments\Enums\PaymentStatus;
use App\Domains\Payments\Models\Payment;
use App\Domains\Payments\Services\StripePaymentService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->server('HTTP_STRIPE_SIGNATURE');

        Log::info('Webhook received', [
            'signature' => $signature,
            'payload' => $payload,
        ]);

        if (!$signature) {
            return response()->json(['error' => 'Missing Stripe-Signature header'], 400);
        }

        try {
            $event = (new StripePaymentService)->constructWebhookEvent($payload, $signature);
        } catch (\Exception $e) {
            Log::error('Stripe webhook error: ' . $e->getMessage());
            return response()->json(['error' => 'Webhook failed'], 400);
        }

        // ✅ التعامل مع الحدث
        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            // بيانات metadata التي أرسلتها أثناء إنشاء جلسة الدفع
            $paymentId = $session->metadata->payment_id ?? null;
            $userId = $session->metadata->user_id ?? null;
            $courseId = $session->metadata->course_id ?? null;

            Log::info('Checkout Session Completed', [
                'payment_id' => $paymentId,
                'user_id' => $userId,
                'course_id' => $courseId,
            ]);

            // تحقق من الدفع وحدّث الحالة وأضف المستخدم للكورس
            $payment = Payment::find($paymentId);

            if ($payment && $payment->status !== PaymentStatus::SUCCEEDED->value) {
                $payment->update(['status' => PaymentStatus::SUCCEEDED->value]);

                // إدخال المستخدم في الكورس
                DB::table('course_user')->updateOrInsert([
                    'user_id' => $userId,
                    'course_id' => $courseId,
                ], [
                    'enrolled_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                Log::info("User {$userId} enrolled in course {$courseId}");
            }
        }

        return response()->json(['status' => 'success']);
    }
}
