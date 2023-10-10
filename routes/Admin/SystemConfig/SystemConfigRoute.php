<?php

use App\Http\Controllers\Api\V1\Admin\DeviceController;
use App\Http\Controllers\Api\V1\Admin\OfficeController;
use App\Http\Controllers\Api\V1\Admin\AdminController;
use App\Http\Controllers\Api\V1\Admin\financialYearController;
use App\Http\Controllers\Api\V1\Admin\MenuController;
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
        Route::get('/get/{district_id}',[OfficeController::class, 'getAllOfficeByDistrictId'])->middleware(['role_or_permission:super-admin|demo-graphic-view']);

    });

    /* -------------------------------------------------------------------------- */
    /*                               Lookup Management Routes                              */
    /* -------------------------------------------------------------------------- */

    Route::prefix('admin/lookup')->group(function () {

        Route::post('/insert', [AdminController::class, 'insertlookup'])->middleware(['role_or_permission:super-admin|demo-graphic-create']);
        Route::get('/get',[AdminController::class, 'getAllLookupPaginated'])->middleware(['role_or_permission:super-admin|demo-graphic-view']);
        Route::post('/update', [AdminController::class, 'LookupUpdate'])->middleware(['role_or_permission:super-admin|demo-graphic-update']);
        Route::get('/get/{type}',[AdminController::class, 'getAllLookupByType']);
        Route::get('/destroy/{id}', [AdminController::class, 'destroyLookup'])->middleware(['role_or_permission:super-admin|demo-graphic-destroy']);
    });
    /* -------------------------------------------------------------------------- */
    /*                               Allowance program Management  Routes                              */
    /* -------------------------------------------------------------------------- */

    Route::prefix('admin/allowance')->group(function () {

        Route::post('/insert', [SystemconfigController::class, 'insertallowance'])->middleware(['role_or_permission:super-admin|demo-graphic-create']);
        Route::get('/get',[SystemconfigController::class, 'getAllallowancePaginated'])->middleware(['role_or_permission:super-admin|demo-graphic-view']);
        Route::get('/get_additional_field',[SystemconfigController::class, 'getAdditionalField'])->middleware(['role_or_permission:super-admin|demo-graphic-view']);
        Route::get('/edit/{id}',[SystemconfigController::class, 'edit'])->middleware(['role_or_permission:super-admin|demo-graphic-view']);
        Route::put('/update/{id}', [SystemconfigController::class, 'AllowanceUpdate'])->middleware(['role_or_permission:super-admin|demo-graphic-update']);
        Route::delete('/destroy/{id}', [SystemconfigController::class, 'destroyAllowance'])->middleware(['role_or_permission:super-admin|demo-graphic-destroy']);
    });

    /* -------------------------------------------------------------------------- */
    /*                              Financial Year Management  Routes             */
    /* -------------------------------------------------------------------------- */

    Route::prefix('admin/financial-year')->group(function () {

        Route::post('/insert', [financialYearController::class, 'insertFinancialYear'])->middleware(['role_or_permission:super-admin|financial-year-create']);
        Route::get('/get',[financialYearController::class, 'getFinancialPaginated'])->middleware(['role_or_permission:super-admin|financial-year-view']);
        Route::get('/destroy/{id}', [financialYearController::class, 'destroyFinancial'])->middleware(['role_or_permission:super-admin|financial-year-destroy']);
    });

    /* -------------------------------------------------------------------------- */
    /*                          Device management Routes                          */
    /* -------------------------------------------------------------------------- */

    Route::prefix('admin/device')->group(function () {
        Route::post('/status/{id}', [DeviceController::class, 'deviceStatusUpdate'])->middleware(['role_or_permission:super-admin|device-edit']);
        Route::post('/insert', [DeviceController::class, 'insertDevice'])->middleware(['role_or_permission:super-admin|device-create']);
        Route::get('/get',[DeviceController::class, 'getAllDevicePaginated'])->middleware(['role_or_permission:super-admin|device-list']);
        Route::get('/get_users',[DeviceController::class, 'getUsers'])->middleware(['role_or_permission:super-admin|device-list']);
        Route::get('/edit/{id}',[DeviceController::class, 'edit'])->middleware(['role_or_permission:super-admin|device-list']);
        Route::post('/update', [DeviceController::class, 'deviceUpdate'])->middleware(['role_or_permission:super-admin|device-update']);
        Route::delete('/destroy/{id}', [DeviceController::class, 'destroyDevice'])->middleware(['role_or_permission:super-admin|device-destroy']);
    });


    /* -------------------------------------------------------------------------- */
    /*                           Menu Management Routes                           */
    /* -------------------------------------------------------------------------- */
    Route::prefix('admin/menu')->group(function () {

        Route::post('/insert', [MenuController::class, 'insertMenu'])->middleware(['role_or_permission:super-admin|menu-create']);
        Route::get('/get',[MenuController::class, 'getAllMenu']);
        Route::get('/get-all',[MenuController::class, 'getMenus']);
        Route::get('/get_page_url', [MenuController::class, 'getPageUrl']);
        Route::get('/get_parent', [MenuController::class, 'getParent']);
        Route::get('/edit/{id}', [MenuController::class, 'edit']);
        Route::put('/update/{id}', [MenuController::class, 'update']);
        Route::delete('/destroy/{id}', [MenuController::class, 'destroy'])->middleware(['role_or_permission:super-admin|menu-destroy']);
    });


});


