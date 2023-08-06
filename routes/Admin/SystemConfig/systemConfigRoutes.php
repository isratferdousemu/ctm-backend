<?php

use App\Http\Controllers\Api\V1\Admin\DeviceController;

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('admin/device')->group(function () {

        Route::post('/insert', [DeviceController::class, 'insertDevice'])->middleware(['role_or_permission:super-admin|device-create']);
        Route::get('/get',[DeviceController::class, 'getAllDevicePaginated'])->middleware(['role_or_permission:super-admin|device-list']);
        // Route::post('/update', [LocationController::class, 'divisionUpdate'])->middleware(['role_or_permission:super-admin|demo-graphic-update']);
        // Route::get('/destroy/{id}', [LocationController::class, 'destroyDivision'])->middleware(['role_or_permission:super-admin|demo-graphic-destroy']);
    });


});
