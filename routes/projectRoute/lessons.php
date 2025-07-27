<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LessonController;

// ربط الدروس تحت الأقسام ضمن الكورسات
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('courses/{course}/sections/order/{sectionOrder}')->group(function () {
        Route::get('lessons', [LessonController::class, 'index']);
        Route::get('lessons/{lesson}', [LessonController::class, 'show']);
        Route::post('lessons', [LessonController::class, 'store']);
        Route::put('lessons/{lesson}', [LessonController::class, 'update']);
        Route::delete('lessons/{lesson}', [LessonController::class, 'destroy']);
    });
});

Route::middleware('ensure.user.is.enrolled')->group(function () {
    Route::get('/courses/{course}/sections/{section}/lessons/{lesson}', [LessonController::class, 'show']);
});
