<?php

use App\Http\Controllers\Api\V1\Admin\UserController;

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('admin/user')->group(function () {

        Route::post('/insert', [UserController::class, 'insertUser'])->middleware(['role_or_permission:super-admin|demo-graphic-create']);
        // Route::get('/get',[LocationController::class, 'getAllDivisionPaginated'])->middleware(['role_or_permission:super-admin|demo-graphic-view']);
        // Route::post('/update', [LocationController::class, 'divisionUpdate'])->middleware(['role_or_permission:super-admin|demo-graphic-update']);
        // Route::get('/destroy/{id}', [LocationController::class, 'destroyDivision'])->middleware(['role_or_permission:super-admin|demo-graphic-destroy']);
    });


});
