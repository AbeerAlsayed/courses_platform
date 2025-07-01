<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PaymentController;



Route::middleware('auth:sanctum')->group(function () {
    Route::post('/pay-course', [\App\Http\Controllers\Api\PaymentController::class, 'createCheckout']);
});

Route::post('/stripe/webhook', [\App\Http\Controllers\Api\PaymentController::class, 'handleWebhook']);

Route::get('/payment/success', function () {
    return response('✅ تم الدفع بنجاح! يمكنك إغلاق هذه الصفحة.', 200);
});

Route::get('/payment/cancel', function () {
    return response('❌ تم إلغاء الدفع. حاول مرة أخرى إذا رغبت.', 200);
});

Route::get('/checkout', [PaymentController::class, 'checkout']);
Route::get('/success', function () {
    return 'Payment successful!';
});
Route::get('/cancel', function () {
    return 'Payment cancelled.';
});
