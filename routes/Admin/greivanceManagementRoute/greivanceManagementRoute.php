<?php
use App\Http\Controllers\Api\V1\Admin\GrievanceSubjectController;
use App\Http\Controllers\Api\V1\Admin\GrievanceTypeController;

Route::middleware('auth:sanctum')->group(function () {

    /* -------------------------------------------------------------------------- */
    /*                                 Role Routes                                */
    /* -------------------------------------------------------------------------- */
/* -----------------------------------Start Grienvace Type--------------------------------------- */
    Route::prefix('admin/grievanceType')->group(function () {

        Route::get('/get',[GrievanceTypeController::class, 'getAllTypePaginated'])->middleware(['role_or_permission:super-admin|grievanceType-create']);
        Route::post('/store', [GrievanceTypeController::class, 'store'])->middleware(['role_or_permission:super-admin|grievanceType-create']);
        Route::get('/edit/{id}', [GrievanceTypeController::class, 'edit'])->middleware(['role_or_permission:super-admin|grievanceType-edit']);
        Route::post('/update', [GrievanceTypeController::class, 'update'])->middleware(['role_or_permission:super-admin|grievanceType-edit']);
        Route::delete('/destroy/{id}', [GrievanceTypeController::class, 'destroy'])->middleware(['role_or_permission:super-admin|grievanceType-delete']);

/* -----------------------------------End Grienvace Type--------------------------------------- */
});

/* -----------------------------------Start Grienvace Subject--------------------------------------- */
Route::prefix('admin/grievanceSubject')->group(function () {
    Route::get('/get', [GrievanceSubjectController::class, 'getAll'])->middleware(['role_or_permission:super-admin|grievanceSubject-create']);
    Route::post('/store', [GrievanceSubjectController::class, 'store'])->middleware(['role_or_permission:super-admin|grievanceSubject-create']);
    Route::get('/edit/{id}', [GrievanceSubjectController::class, 'edit'])->middleware(['role_or_permission:super-admin|grievanceSubject-edit']);
    Route::post('/update', [GrievanceSubjectController::class, 'update'])->middleware(['role_or_permission:super-admin|grievanceSubject-edit']);
    Route::delete('/destroy/{id}', [GrievanceSubjectController::class, 'destroy'])->middleware(['role_or_permission:super-admin|grievanceSubject-delete']);

/* -----------------------------------End Grienvace Subject--------------------------------------- */

});

});