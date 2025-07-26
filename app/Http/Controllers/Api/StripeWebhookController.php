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
        $signature = $request->header('Stripe-Signature');

        try {
            $event = (new StripePaymentService)->constructWebhookEvent($payload, $signature);
        } catch (\Exception $e) {
            Log::error('Stripe webhook error: ' . $e->getMessage());
            return response()->json(['error' => 'Webhook failed'], 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            $paymentId = $session->metadata->payment_id;
            $userId = $session->metadata->user_id;
            $courseId = $session->metadata->course_id;

            $payment = Payment::find($paymentId);

            if ($payment && $payment->status !== PaymentStatus::PAID->value) {
                $payment->update(['status' => PaymentStatus::PAID->value]);

                DB::table('course_user')->updateOrInsert([
                    'user_id' => $userId,
                    'course_id' => $courseId,
                ], [
                    'enrolled_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return response()->json(['status' => 'success']);
    }
}
