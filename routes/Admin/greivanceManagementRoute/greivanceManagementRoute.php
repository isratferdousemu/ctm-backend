<?php
use App\Http\Controllers\Api\V1\Admin\GrievanceController;
use App\Http\Controllers\Api\V1\Admin\GrievanceSettingController;
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

/* -----------------------------------Start Grienvace Settings--------------------------------------- */
Route::prefix('admin/grievanceSetting')->group(function () {
    Route::get('/get', [GrievanceSettingController::class, 'getAll'])->middleware(['role_or_permission:super-admin|grievance-setting-create']);
    Route::post('/store', [GrievanceSettingController::class, 'store'])->middleware(['role_or_permission:super-admin|grievance-setting-create']);
    Route::get('/edit/{id}', [GrievanceSettingController::class, 'edit'])->middleware(['role_or_permission:super-admin|grievance-setting-edit']);
    Route::post('/update', [GrievanceSettingController::class, 'update'])->middleware(['role_or_permission:super-admin|grievance-setting-edit']);
    Route::delete('/destroy/{id}', [GrievanceSettingController::class, 'destroy'])->middleware(['role_or_permission:super-admin|grievance-setting-delete']);

/* -----------------------------------End Grienvace Settings--------------------------------------- */

});
/* -----------------------------------Start Grienvace Settings--------------------------------------- */
Route::prefix('admin/grievance')->group(function () {
   Route::get('/get', [GrievanceController::class, 'getAllGrievancePaginated'])->middleware(['role_or_permission:super-admin|application-entry-view']);
   Route::get('get/{id}', [GrievanceController::class, 'getApplicationById'])->middleware(['role_or_permission:super-admin|application-entry-view']);
   Route::get('/permissions', [GrievanceController::class, 'getApplicationPermission']);
   Route::get('/committee-list', [GrievanceController::class, 'getCommitteeList']);
   Route::post('/update-status', [GrievanceController::class, 'changeApplicationsStatus'])->middleware(['role_or_permission:super-admin|application-entry-edit']);
   Route::get('/generate-pdf', [GrievanceController::class, 'getPdf']);


/* -----------------------------------End Grienvace Settings--------------------------------------- */

});


});