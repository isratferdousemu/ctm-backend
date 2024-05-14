<?php


use App\Http\Controllers\Api\V1\Admin\Training\TimeSlotController;
use App\Http\Controllers\Api\V1\Admin\Training\TrainerController;
use App\Http\Controllers\Api\V1\Admin\Training\TrainingCircularController;
use App\Http\Controllers\Api\V1\Admin\Training\TrainingProgramController;

Route::middleware('auth:sanctum')->prefix('admin/training')->group(function () {
    Route::apiResource('trainers', TrainerController::class);
    Route::any('trainers/status/{trainer}', [TrainerController::class, 'updateStatus']);

    Route::apiResource('circulars', TrainingCircularController::class);
    Route::apiResource('time-slots', TimeSlotController::class);
    Route::apiResource('programs', TrainingProgramController::class);
    Route::get('program-circulars', [TrainingProgramController::class, 'circulars']);
    Route::get('program-update-status', [TrainingProgramController::class, 'updateStatus']);
    Route::get('program-trainers', [TrainingProgramController::class, 'trainers']);
    Route::get('program-time-slots', [TrainingProgramController::class, 'timeSlots']);
});

Route::get('circulars-details/{circular}', [TrainingCircularController::class, 'show']);

