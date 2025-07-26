<?php

use App\Http\Controllers\Api\StripeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/payment-success', [StripeController::class, 'success'])->name('payment.success');
Route::get('/payment-failed', [StripeController::class, 'failed'])->name('payment.failed');
