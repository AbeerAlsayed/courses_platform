<?php

if (!function_exists('successResponse')) {
    function successResponse(string $message = 'Success', $data = null, int $status = 200) {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }
}

if (!function_exists('errorResponse')) {
    function errorResponse(string $message = 'Something went wrong', $errors = [], int $status = 400) {
        return response()->json([
            'status' => false,
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }
}
