<?php

use App\Http\Controllers\Api\EnrollmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('courses/{course}/enroll', [EnrollmentController::class, 'store']);
    Route::delete('courses/{course}/enroll', [EnrollmentController::class, 'destroy']);
    Route::get('my-enrollments', [EnrollmentController::class, 'myEnrollments']);
});
