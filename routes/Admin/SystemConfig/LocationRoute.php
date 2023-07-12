<?php

use App\Http\Controllers\Api\V1\Admin\LocationController;

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('admin/division')->group(function () {

        Route::post('/insert', [LocationController::class, 'insertDivision'])->middleware(['role_or_permission:super-admin|demo-graphic-create']);
        Route::get('/get',[LocationController::class, 'getAllDivisionPaginated'])->middleware(['role_or_permission:super-admin|demo-graphic-view']);
        Route::post('/update', [LocationController::class, 'divisionUpdate'])->middleware(['role_or_permission:super-admin|demo-graphic-update']);
        Route::get('/destroy/{id}', [LocationController::class, 'destroyDivision'])->middleware(['role_or_permission:super-admin|demo-graphic-destroy']);
    });

    /* -------------------------------------------------------------------------- */
    /*                               District Routes                              */
    /* -------------------------------------------------------------------------- */
    Route::prefix('admin/district')->group(function () {

        Route::post('/insert', [LocationController::class, 'insertDistrict'])->middleware(['role_or_permission:super-admin|demo-graphic-create']);
        Route::get('/get',[LocationController::class, 'getAllDistrictPaginated'])->middleware(['role_or_permission:super-admin|demo-graphic-view']);
        Route::post('/update', [LocationController::class, 'districtUpdate'])->middleware(['role_or_permission:super-admin|demo-graphic-update']);
        Route::get('/destroy/{id}', [LocationController::class, 'destroyDistrict'])->middleware(['role_or_permission:super-admin|demo-graphic-destroy']);
    });

    /* -------------------------------------------------------------------------- */
    /*                               City Routes                                  */
    /* -------------------------------------------------------------------------- */
    Route::prefix('admin/city')->group(function () {

        Route::post('/insert', [LocationController::class, 'insertCity'])->middleware(['role_or_permission:super-admin|demo-graphic-create']);
        Route::get('/get',[LocationController::class, 'getAllCityPaginated'])->middleware(['role_or_permission:super-admin|demo-graphic-view']);
        Route::post('/update', [LocationController::class, 'cityUpdate'])->middleware(['role_or_permission:super-admin|demo-graphic-update']);
        Route::get('/destroy/{id}', [LocationController::class, 'destroyCity'])->middleware(['role_or_permission:super-admin|demo-graphic-destroy']);
    });

    /* -------------------------------------------------------------------------- */
    /*                               Thana Routes                                  */
    /* -------------------------------------------------------------------------- */
    Route::prefix('admin/thana')->group(function () {

        Route::post('/insert', [LocationController::class, 'insertThana'])->middleware(['role_or_permission:super-admin|demo-graphic-create']);
        // Route::get('/get',[LocationController::class, 'getAllCityPaginated'])->middleware(['role_or_permission:super-admin|demo-graphic-view']);
        // Route::post('/update', [LocationController::class, 'cityUpdate'])->middleware(['role_or_permission:super-admin|demo-graphic-update']);
        // Route::get('/destroy/{id}', [LocationController::class, 'destroyCity'])->middleware(['role_or_permission:super-admin|demo-graphic-destroy']);
    });

});
