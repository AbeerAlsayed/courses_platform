<?php

use App\Http\Controllers\Admin\InstructorApprovalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

Route::middleware(['auth:sanctum', 'role:admin'])
    ->patch('/instructors/{id}/status', [InstructorApprovalController::class, 'updateInstructorStatus']);
