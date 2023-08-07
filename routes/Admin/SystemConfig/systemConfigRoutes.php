<?php

use App\Http\Controllers\Api\V1\Admin\DeviceController;

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('admin/device')->group(function () {
        Route::post('/status', [DeviceController::class, 'deviceStatusUpdate'])->middleware(['role_or_permission:super-admin|device-edit']);
        Route::post('/insert', [DeviceController::class, 'insertDevice'])->middleware(['role_or_permission:super-admin|device-create']);
        Route::get('/get',[DeviceController::class, 'getAllDevicePaginated'])->middleware(['role_or_permission:super-admin|device-list']);
        Route::post('/update', [DeviceController::class, 'deviceUpdate'])->middleware(['role_or_permission:super-admin|device-update']);
        // Route::get('/destroy/{id}', [LocationController::class, 'destroyDivision'])->middleware(['role_or_permission:super-admin|demo-graphic-destroy']);
    });


});
