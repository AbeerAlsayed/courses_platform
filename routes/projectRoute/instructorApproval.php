<?php

use App\Http\Controllers\Api\Admin\InstructorApprovalController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'role:admin'])
    ->patch('/instructors/{id}/status', [InstructorApprovalController::class, 'updateInstructorStatus']);
