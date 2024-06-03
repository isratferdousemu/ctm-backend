<?php

use App\Http\Controllers\Api\V1\Admin\Emergency\EmergencyAllotmentController;

Route::middleware(['auth:sanctum', 'language'])->group(function () {

    Route::prefix('admin/emergency')->group(function () {

        Route::get('/allotments', [EmergencyAllotmentController::class, 'getEmergencyAllotments'])->middleware(['role_or_permission:super-admin|emergency-allotment-view']);
        Route::post('/allotments', [EmergencyAllotmentController::class, 'store'])->middleware(['role_or_permission:super-admin|emergency-allotment-create']);
        Route::delete('/allotments/{id}', [EmergencyAllotmentController::class, 'destroy'])->middleware(['role_or_permission:super-admin|emergency-allotment-delete']);
        Route::get('/allotments/edit/{id}', [EmergencyAllotmentController::class, 'edit'])->middleware(['role_or_permission:super-admin|emergency-allotment-edit']);
        Route::put('/allotments/update/{id}', [EmergencyAllotmentController::class, 'update'])->middleware(['role_or_permission:super-admin|emergency-allotment-edit']);
        // Route::get('/get', [GrievanceTypeController::class, 'getAllTypePaginated'])->middleware(['role_or_permission:super-admin|grievanceType-view']);

        /* -----------------------------------End Grienvace Type--------------------------------------- */
    });
});