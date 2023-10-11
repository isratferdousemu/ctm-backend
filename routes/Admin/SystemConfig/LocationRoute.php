<?php

use App\Http\Controllers\Api\V1\Admin\LocationController;

Route::middleware('auth:sanctum')->group(function () {


    /* -------------------------------------------------------------------------- */
    /*                               Division Routes                              */
    /* -------------------------------------------------------------------------- */

    Route::prefix('admin/division')->group(function () {

        Route::post('/insert', [LocationController::class, 'insertDivision'])->middleware(['role_or_permission:super-admin|division-create']);
        Route::get('/get',[LocationController::class, 'getAllDivisionPaginated']);
        Route::post('/update', [LocationController::class, 'divisionUpdate'])->middleware(['role_or_permission:super-admin|division-update']);
        Route::get('/destroy/{id}', [LocationController::class, 'destroyDivision'])->middleware(['role_or_permission:super-admin|division-delete']);
    });

    /* -------------------------------------------------------------------------- */
    /*                               District Routes                              */
    /* -------------------------------------------------------------------------- */
    Route::prefix('admin/district')->group(function () {

        Route::post('/insert', [LocationController::class, 'insertDistrict'])->middleware(['role_or_permission:super-admin|district-create']);
        Route::get('/get',[LocationController::class, 'getAllDistrictPaginated']);
        Route::get('/get/{division_id}',[LocationController::class, 'getAllDistrictByDivisionId']);
        Route::post('/update', [LocationController::class, 'districtUpdate'])->middleware(['role_or_permission:super-admin|district-update']);
        Route::get('/destroy/{id}', [LocationController::class, 'destroyDistrict'])->middleware(['role_or_permission:super-admin|district-delete']);
    });

    /* -------------------------------------------------------------------------- */
    /*                               City Routes                                  */
    /* -------------------------------------------------------------------------- */
    Route::prefix('admin/city')->group(function () {

        Route::post('/insert', [LocationController::class, 'insertCity'])->middleware(['role_or_permission:super-admin|city-create']);
        Route::get('/get',[LocationController::class, 'getAllCityPaginated']);
        Route::post('/update', [LocationController::class, 'cityUpdate'])->middleware(['role_or_permission:super-admin|city-update']);
        Route::get('/destroy/{id}', [LocationController::class, 'destroyCity'])->middleware(['role_or_permission:super-admin|city-delete']);
        Route::get('/get/{district_id}/{location_type}',[LocationController::class, 'getAllCityByDistrictId']);

    });

    /* -------------------------------------------------------------------------- */
    /*                               Thana Routes                                  */
    /* -------------------------------------------------------------------------- */
    Route::prefix('admin/thana')->group(function () {

        Route::post('/insert', [LocationController::class, 'insertThana'])->middleware(['role_or_permission:super-admin|thana-create']);
        Route::get('/get',[LocationController::class, 'getAllThanaPaginated']);
        Route::get('/get/{district_id}',[LocationController::class, 'getAllThanaByDistrictId']);
        Route::get('/get/city/{city_id}',[LocationController::class, 'getAllThanaByCityId']);
        Route::post('/update', [LocationController::class, 'thanaUpdate'])->middleware(['role_or_permission:super-admin|thana-update']);
        Route::get('/destroy/{id}', [LocationController::class, 'destroyThana'])->middleware(['role_or_permission:super-admin|thana-delete']);
    });


    /* -------------------------------------------------------------------------- */
    /*                               Union Routes                                  */
    /* -------------------------------------------------------------------------- */
    Route::prefix('admin/union')->group(function () {

        Route::post('/insert', [LocationController::class, 'insertUnion'])->middleware(['role_or_permission:super-admin|union-create']);
        Route::get('/get',[LocationController::class, 'getAllUnionPaginated']);
        Route::get('/get/{thana_id}',[LocationController::class, 'getAllUnionByThanaId']);
        Route::post('/update', [LocationController::class, 'unionUpdate'])->middleware(['role_or_permission:super-admin|union-update']);
        Route::get('/destroy/{id}', [LocationController::class, 'destroyUnion'])->middleware(['role_or_permission:super-admin|union-delete']);
    });


    /* -------------------------------------------------------------------------- */
    /*                               Ward Routes                                  */
    /* -------------------------------------------------------------------------- */
    Route::prefix('admin/ward')->group(function () {

        Route::post('/insert', [LocationController::class, 'insertWard'])->middleware(['role_or_permission:super-admin|ward-create']);
        Route::get('/get',[LocationController::class, 'getAllWardPaginated']);
        Route::get('/get/city/{city_id}',[LocationController::class, 'getAllWardByCityId']);
        Route::post('/update', [LocationController::class, 'wardUpdate'])->middleware(['role_or_permission:super-admin|ward-update']);
        Route::get('/destroy/{id}', [LocationController::class, 'destroyWard'])->middleware(['role_or_permission:super-admin|ward-delete']);
    });

});
