<?php

use App\Http\Controllers\Api\CourseController;
use Illuminate\Support\Facades\Route;


Route::apiResource('courses', CourseController::class)->only(['index', 'show']);

// ✅ هذه فقط للمدرب أو الأدمن ومسجلة دخول:
Route::middleware(['auth:sanctum', 'role:instructor|admin'])->group(function () {
    Route::apiResource('courses', CourseController::class)->except(['index', 'show', 'create', 'edit']);
});
