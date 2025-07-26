<?php

use App\Http\Controllers\Api\StripeWebhookController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PaymentController;

Route::middleware(['auth:sanctum', 'role:student|admin'])->group(function () {
    Route::post('/courses/{course}/pay', [PaymentController::class, 'initiate'])
        ->name('payment.initiate');

    Route::get('/payment/{payment}/success', [PaymentController::class, 'success'])
        ->name('payment.success');

    Route::get('/payment/{payment}/cancel', [PaymentController::class, 'cancel'])
        ->name('payment.cancel');
});

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])->name('stripe.webhook');
