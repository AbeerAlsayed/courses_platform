<?php
use App\Http\Controllers\Api\CartController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index']);
    Route::post('/', [CartController::class, 'store']);
    Route::delete('/{course}', [CartController::class, 'destroy']);
});
Route::delete('test', function () {
    dd('reached');
});
