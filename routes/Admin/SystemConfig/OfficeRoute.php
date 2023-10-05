<?php

use App\Http\Controllers\Api\V1\Admin\OfficeController;

Route::middleware('auth:sanctum')->group(function () {

    /* -------------------------------------------------------------------------- */
    /*                               Office Management Routes                              */
    /* -------------------------------------------------------------------------- */

    Route::prefix('admin/office')->group(function () {

        Route::post('/insert', [OfficeController::class, 'insertOffice'])->middleware(['role_or_permission:super-admin|office-create']);
        Route::get('/get',[OfficeController::class, 'getAllOfficePaginated'])->middleware(['role_or_permission:super-admin|office-view']);
        Route::post('/update', [OfficeController::class, 'OfficeUpdate'])->middleware(['role_or_permission:super-admin|office-update']);
        Route::get('/destroy/{id}', [OfficeController::class, 'destroyOffice'])->middleware(['role_or_permission:super-admin|office-deletes']);
    });




});
