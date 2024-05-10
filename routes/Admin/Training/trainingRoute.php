<?php


use App\Http\Controllers\Api\V1\Admin\Training\TrainerController;
use App\Http\Controllers\TrainingCircularController;

Route::middleware('auth:sanctum')->prefix('admin/training')->group(function () {
    Route::apiResource('trainers', TrainerController::class);
    Route::any('trainers/status/{trainer}', [TrainerController::class, 'updateStatus']);

    Route::apiResource('circulars', TrainingCircularController::class);

});
