<?php

namespace App\Domains\Payments\Actions;

use App\Domains\Payments\Models\Payment;
use App\Domains\Payments\Enums\PaymentStatus;
use App\Domains\Enrollments\Actions\CreateEnrollmentAction;
use Stripe\Stripe;
use Stripe\Webhook;
use Illuminate\Http\Request;

class ConfirmPaymentAction
{
    public function handleWebhook(Request $request, CreateEnrollmentAction $enrollAction)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        } catch (\Exception $e) {
            return response('Webhook error: ' . $e->getMessage(), 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            $paymentId = $session->metadata->payment_id ?? null;
            $providerRef = $session->id;

            if ($paymentId) {
                $payment = Payment::find($paymentId);
                if ($payment && $payment->status === PaymentStatus::Pending) {
                    $payment->update([
                        'status' => PaymentStatus::Success,
                        'provider_reference' => $providerRef,
                    ]);

                    $enrollAction->execute($payment->student, $payment->course);
                }
            }
        }

        return response('Webhook handled', 200);
    }
}
