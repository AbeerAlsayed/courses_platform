<?php

use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\StripeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//Route::get('/payment-success', [StripeController::class, 'success'])->name('payment.success');
//Route::get('/payment-failed', [StripeController::class, 'failed'])->name('payment.failed');
Route::get('/payment-success', [PaymentController::class, 'success'])
    ->name('payment.success');

Route::get('/payment/{payment}/cancel', [PaymentController::class, 'cancel'])
    ->name('payment.cancel');
