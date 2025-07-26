<?php

namespace App\Domains\Payments\Services;

use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Webhook;
use Stripe\Exception\ApiErrorException;

class StripePaymentService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        if (empty(config('services.stripe.secret'))) {
            throw new \RuntimeException('Stripe secret key not configured');
        }
    }

    public function createCheckoutSession(array $params): Session
    {
        try {
            return Session::create($params);
        } catch (ApiErrorException $e) {
            Log::error('Stripe API Error: ' . $e->getMessage());
            throw new \RuntimeException('Payment processing error');
        }
    }

    public function constructWebhookEvent(string $payload, string $signature)
    {
        $secret = config('services.stripe.webhook_secret'); // whsec_xxx
        return \Stripe\Webhook::constructEvent($payload, $signature, $secret);
    }
}
