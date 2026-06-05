<?php

use App\Http\Controllers\Api\CourseController;
use Illuminate\Support\Facades\Route;

Route::get('/courses', [CourseController::class, 'index']);
Route::middleware('auth:sanctum')->group(function () {

    Route::post   ('/courses',                 [CourseController::class, 'store']);
    Route::put    ('/courses/{course}',        [CourseController::class, 'update']);
    Route::delete ('/courses/{course}',        [CourseController::class, 'destroy']);

    Route::patch  ('/courses/{course}/status', [CourseController::class, 'changeStatus'])
        ->middleware('role:admin');

    Route::get    ('/courses/{course}',        [CourseController::class, 'show']);
});
