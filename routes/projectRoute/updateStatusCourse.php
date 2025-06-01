<?php

use App\Http\Controllers\Api\Admin\UpdateStatusCourseController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'role:admin'])
    ->patch('/courses/{course}/status', [UpdateStatusCourseController::class, 'updateStatus']);
