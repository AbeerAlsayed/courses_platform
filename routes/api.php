<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', [AuthController::class, 'adminDashboard']);
    });

    Route::middleware('role:instructor')->group(function () {
        Route::get('/instructor/dashboard', [AuthController::class, 'instructorDashboard']);
    });

    Route::middleware('role:student')->group(function () {
        Route::get('/student/dashboard', [AuthController::class, 'studentDashboard']);
    });
});


