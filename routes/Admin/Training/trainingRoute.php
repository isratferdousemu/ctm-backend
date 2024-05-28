<?php


use App\Http\Controllers\Api\V1\Admin\Training\TimeSlotController;
use App\Http\Controllers\Api\V1\Admin\Training\TrainerController;
use App\Http\Controllers\Api\V1\Admin\Training\TrainingCircularController;
use App\Http\Controllers\Api\V1\Admin\Training\TrainingParticipantController;
use App\Http\Controllers\Api\V1\Admin\Training\TrainingProgramController;

Route::middleware('auth:sanctum')->prefix('admin/training')->group(function () {
    Route::apiResource('trainers', TrainerController::class);
    Route::any('trainers/status/{trainer}', [TrainerController::class, 'updateStatus']);

    Route::apiResource('circulars', TrainingCircularController::class);
    Route::apiResource('time-slots', TimeSlotController::class);
    Route::apiResource('programs', TrainingProgramController::class);
    Route::put('programs/status/{program}', [TrainingProgramController::class, 'updateStatus']);
    Route::put('programs/exam-status/{program}', [TrainingProgramController::class, 'updateExamStatus']);
    Route::put('programs/rating-status/{program}', [TrainingProgramController::class, 'updateRatingStatus']);
    Route::get('program-circulars', [TrainingProgramController::class, 'circulars']);
    Route::get('program-trainers', [TrainingProgramController::class, 'trainers']);
    Route::get('program-time-slots', [TrainingProgramController::class, 'timeSlots']);

    Route::post('participants/external', [TrainingParticipantController::class, 'storeExternalParticipant']);
    Route::get('participants/users/{type}', [TrainingParticipantController::class, 'getUsers']);
    Route::get('participants/circulars', [TrainingParticipantController::class, 'trainingCirculars']);
    Route::resource('participants', TrainingParticipantController::class);


});

Route::get('circulars-details/{circular}', [TrainingCircularController::class, 'show']);
Route::get('training/program-details/{program}', [TrainingProgramController::class, 'show']);

