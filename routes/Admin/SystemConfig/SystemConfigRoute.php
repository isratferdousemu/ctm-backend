<?php

use App\Http\Controllers\Api\V1\Admin\OfficeController;
use App\Http\Controllers\APi\V1\Admin\AdminController;
use App\Http\Controllers\Api\V1\Admin\SystemconfigController;

       Route::middleware('auth:sanctum')->group(function () {

    /* -------------------------------------------------------------------------- */
    /*                               Office Management Routes                              */
    /* -------------------------------------------------------------------------- */

     Route::prefix('admin/office')->group(function () {

        Route::post('/insert', [OfficeController::class, 'insertOffice'])->middleware(['role_or_permission:super-admin|demo-graphic-create']);
        Route::get('/get',[OfficeController::class, 'getAllOfficePaginated'])->middleware(['role_or_permission:super-admin|demo-graphic-view']);
        Route::post('/update', [OfficeController::class, 'OfficeUpdate'])->middleware(['role_or_permission:super-admin|demo-graphic-update']);
        Route::get('/destroy/{id}', [OfficeController::class, 'destroyOffice'])->middleware(['role_or_permission:super-admin|demo-graphic-destroy']);
    });

    /* -------------------------------------------------------------------------- */
    /*                               Lookup Management Routes                              */
    /* -------------------------------------------------------------------------- */

    Route::prefix('admin/lookup')->group(function () {

        Route::post('/insert', [AdminController::class, 'insertlookup'])->middleware(['role_or_permission:super-admin|demo-graphic-create']);
        Route::get('/get',[AdminController::class, 'getAllLookupPaginated'])->middleware(['role_or_permission:super-admin|demo-graphic-view']);
        Route::post('/update', [AdminController::class, 'LookupUpdate'])->middleware(['role_or_permission:super-admin|demo-graphic-update']);
        Route::get('/get/{type}',[AdminController::class, 'getAllLookupByType'])->middleware(['role_or_permission:super-admin|demo-graphic-view']);
        Route::get('/destroy/{id}', [AdminController::class, 'destroyLookup'])->middleware(['role_or_permission:super-admin|demo-graphic-destroy']);
    });
       /* -------------------------------------------------------------------------- */
    /*                               Allowance program Management  Routes                              */
    /* -------------------------------------------------------------------------- */

    Route::prefix('admin/allowance')->group(function () {

        Route::post('/insert', [SystemconfigController::class, 'insertallowance'])->middleware(['role_or_permission:super-admin|demo-graphic-create']);
        Route::get('/get',[SystemconfigController::class, 'getAllallowancePaginated'])->middleware(['role_or_permission:super-admin|demo-graphic-view']);
        Route::post('/update', [SystemconfigController::class, 'AllowanceUpdate'])->middleware(['role_or_permission:super-admin|demo-graphic-update']);
        Route::get('/destroy/{id}', [SystemconfigController::class, 'destroyAllowance'])->middleware(['role_or_permission:super-admin|demo-graphic-destroy']);
    });




});
