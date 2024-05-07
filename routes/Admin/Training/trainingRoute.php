<?php


use App\Http\Controllers\Api\V1\Admin\Training\TrainerController;

Route::middleware('auth:sanctum')->prefix('admin/training')->group(function () {
    Route::apiResource('trainers', TrainerController::class);
    Route::any('trainers/status/{trainer}', [TrainerController::class, 'updateStatus']);
});
