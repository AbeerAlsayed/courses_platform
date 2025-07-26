<?php

namespace App\Domains\Payments\Actions;

use App\Domains\Payments\Models\Payment;
use App\Domains\Payments\Services\StripePaymentService;
use App\Domains\Enrollments\Actions\CreateEnrollmentAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ConfirmPaymentAction
{
    public function __construct(
        private StripePaymentService $stripeService,
        private CreateEnrollmentAction $enrollAction
    ) {}

    public function handleWebhook(Request $request): Response
    {
        $event = $this->verifyWebhookEvent($request);

        if ($event->type === 'checkout.session.completed') {
            $this->processCompletedSession($event->data->object);
        }

        return response('Webhook handled', Response::HTTP_OK);
    }

    protected function verifyWebhookEvent(Request $request): \Stripe\Event
    {
        try {
            return $this->stripeService->constructWebhookEvent(
                $request->getContent(),
                $request->header('Stripe-Signature')
            );
        } catch (\Exception $e) {
            report($e);
            abort(Response::HTTP_BAD_REQUEST, 'Invalid webhook signature');
        }
    }

    protected function processCompletedSession(\Stripe\Checkout\Session $session): void
    {
        $payment = $this->getPaymentFromSession($session);

        if ($payment && $payment->isPending()) {
            DB::transaction(function () use ($payment, $session) {
                $payment->markAsSucceeded($session->id);
                // تسجيل المستخدم في الكورس بعد تأكيد الدفع
                $this->enrollAction->execute($payment->user, $payment->course);
            });
        }
    }

    protected function getPaymentFromSession(\Stripe\Checkout\Session $session): ?Payment
    {
        $paymentId = $session->metadata->payment_id ?? null;

        return $paymentId ? Payment::find($paymentId) : null;
    }
}
