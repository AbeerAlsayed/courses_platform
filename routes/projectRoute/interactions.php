<?php


use App\Http\Controllers\Api\CourseCommentController;
use App\Http\Controllers\Api\CourseQuestionController;
use App\Http\Controllers\Api\CourseRatingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'role:student'])->group(function () {
    Route::post('/comments', [CourseCommentController::class, 'store']);
    Route::post('/ratings', [CourseRatingController::class, 'store']);
    Route::post('/questions', [CourseQuestionController::class, 'store']);
});
