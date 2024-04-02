<?php
use App\Http\Controllers\Api\V1\Admin\GrievanceTypeController;

Route::middleware('auth:sanctum')->group(function () {

    /* -------------------------------------------------------------------------- */
    /*                                 Role Routes                                */
    /* -------------------------------------------------------------------------- */

    Route::prefix('admin/grievanceType')->group(function () {

        Route::get('/get',[GrievanceTypeController::class, 'getAllTypePaginated'])->middleware(['role_or_permission:super-admin|grievanceType-create']);
        Route::post('/store', [GrievanceTypeController::class, 'store'])->middleware(['role_or_permission:super-admin|grievanceType-create']);
        Route::get('/edit/{id}', [GrievanceTypeController::class, 'edit'])->middleware(['role_or_permission:super-admin|grievanceType-edit']);
        Route::post('/update', [GrievanceTypeController::class, 'update'])->middleware(['role_or_permission:super-admin|grievanceType-edit']);
        Route::delete('/destroy/{id}', [GrievanceTypeController::class, 'destroy'])->middleware(['role_or_permission:super-admin|grievanceType-delete']);

        /* -------------------------------------------------------------------------- */

    });


});