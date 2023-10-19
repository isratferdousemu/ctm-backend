<?php


use App\Http\Controllers\Api\V1\Admin\PovertyScoreCutOffController;


Route::middleware('auth:sanctum')->group(function () {


    /* -------------------------------------------------------------------------- */
    /*                                Beneficiary Routes                          */
    /* -------------------------------------------------------------------------- */
    Route::prefix('admin/poverty')->group(function () {

        Route::get('/get', [PovertyScoreCutOffController::class, 'getAllPovertyScoreCutOffPaginated'])->middleware(['role_or_permission:super-admin|demo-graphic-view']);
        Route::post('/division-cut-off/insert', [PovertyScoreCutOffController::class, 'insertDivisionCutOff'])->middleware(['role_or_permission:super-admin|demo-graphic-create']);
        Route::post('/division-cut-off/update', [PovertyScoreCutOffController::class, 'updateDivisionCutOff'])->middleware(['role_or_permission:super-admin|demo-graphic-create']);
        Route::post('/division-cut-off/filter', [PovertyScoreCutOffController::class, 'getFiltered'])->middleware(['role_or_permission:super-admin|demo-graphic-create']);
        // Route::post('/update', [PovertyScoreCutOffController::class, 'committeeUpdate'])->middleware(['role_or_permission:super-admin|demo-graphic-update']);
        // Route::get('/destroy/{id}', [PovertyScoreCutOffController::class, 'destroyPovertyScoreCuttOff'])->middleware(['role_or_permission:super-admin|demo-graphic-destroy']);
        // Route::get('/{id}', [PovertyScoreCutOffController::class, 'editPovertyScoreCuttOff'])->middleware(['role_or_permission:super-admin|demo-graphic-destroy']);


    });
});
