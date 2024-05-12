<?php


use App\Http\Controllers\Api\V1\Admin\Training\TimeSlotController;
use App\Http\Controllers\Api\V1\Admin\Training\TrainerController;
use App\Http\Controllers\Api\V1\Admin\Training\TrainingCircularController;

Route::middleware('auth:sanctum')->prefix('admin/training')->group(function () {
    Route::apiResource('trainers', TrainerController::class);
    Route::any('trainers/status/{trainer}', [TrainerController::class, 'updateStatus']);

    Route::apiResource('circulars', TrainingCircularController::class);
    Route::get('circulars-details/{circular}', [TrainingCircularController::class, 'show'])->withoutMiddleware('auth:sanctum');
    Route::apiResource('time-slots', TimeSlotController::class);
});
