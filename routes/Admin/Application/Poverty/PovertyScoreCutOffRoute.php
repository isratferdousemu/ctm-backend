<?php


use App\Http\Controllers\Api\V1\Admin\PovertyScoreCutOffController;


Route::middleware('auth:sanctum')->group(function () {


    /* -------------------------------------------------------------------------- */
    /*                                Beneficiary Routes                          */
    /* -------------------------------------------------------------------------- */
    Route::prefix('admin/poverty')->group(function () {

        Route::get('/get', [PovertyScoreCutOffController::class, 'getAllPovertyScoreCutOffPaginated'])->middleware(['role_or_permission:super-admin|demo-graphic-view']);
        Route::post('/poverty-cut-off/insert', [PovertyScoreCutOffController::class, 'insertDivisionCutOff'])->middleware(['role_or_permission:super-admin|demo-graphic-create']);
        Route::post('/poverty-cut-off/update', [PovertyScoreCutOffController::class, 'updatePovertyScoreCutOff'])->middleware(['role_or_permission:super-admin|demo-graphic-create']);
        Route::post('/poverty-cut-off/filter', [PovertyScoreCutOffController::class, 'getFiltered'])->middleware(['role_or_permission:super-admin|demo-graphic-create']);
       
        Route::get('/get/district-fixed-effect', [PovertyScoreCutOffController::class, 'getAllDistrictFixedEffectPaginated'])->middleware(['role_or_permission:super-admin|demo-graphic-view']);
        Route::post('/district-fixed-effect/update', [PovertyScoreCutOffController::class, 'updateDistrictFixedEffect'])->middleware(['role_or_permission:super-admin|demo-graphic-view']);
        // Route::post('/poverty-cut-off/insert', [PovertyScoreCutOffController::class, 'insertDivisionCutOff'])->middleware(['role_or_permission:super-admin|demo-graphic-create']);
        // Route::post('/poverty-cut-off/update', [PovertyScoreCutOffController::class, 'updatePovertyScoreCutOff'])->middleware(['role_or_permission:super-admin|demo-graphic-create']);
        // Route::post('/poverty-cut-off/filter', [PovertyScoreCutOffController::class, 'getFiltered'])->middleware(['role_or_permission:super-admin|demo-graphic-create']);
        // Route::post('/update', [PovertyScoreCutOffController::class, 'committeeUpdate'])->middleware(['role_or_permission:super-admin|demo-graphic-update']);
        // Route::get('/destroy/{id}', [PovertyScoreCutOffController::class, 'destroyPovertyScoreCuttOff'])->middleware(['role_or_permission:super-admin|demo-graphic-destroy']);
        // Route::get('/{id}', [PovertyScoreCutOffController::class, 'editPovertyScoreCuttOff'])->middleware(['role_or_permission:super-admin|demo-graphic-destroy']);


    });
});
