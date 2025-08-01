<?php

use Illuminate\Support\Facades\Route;

//Route::post('/checkout', [StripeController::class, 'checkout']);

require __DIR__ . '/projectRoute/auth.php';
require __DIR__ . '/projectRoute/instructorApproval.php';
require __DIR__ . '/projectRoute/category.php';
require __DIR__ . '/projectRoute/courses.php';
require __DIR__ . '/projectRoute/updateStatusCourse.php';
require __DIR__ . '/projectRoute/sections.php';
require __DIR__ . '/projectRoute/lessons.php';
require __DIR__ . '/projectRoute/enrollments.php';
require __DIR__ . '/projectRoute/payments.php';
require __DIR__ . '/projectRoute/carts.php';
