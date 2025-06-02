<?php

use App\Http\Controllers\Api\SectionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('courses/{course}/sections')->group(function () {
    Route::get('/', [SectionController::class, 'index']);
    Route::get('{section}', [SectionController::class, 'show']);

    Route::middleware('can:create,App\Domains\Courses\Models\Section')->post('/', [SectionController::class, 'store']);
    Route::middleware('can:update,section')->put('{section}', [SectionController::class, 'update']);
    Route::middleware('can:delete,section')->delete('{section}', [SectionController::class, 'destroy']);
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
