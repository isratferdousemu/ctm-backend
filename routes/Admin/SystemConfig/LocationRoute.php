<?php

use App\Http\Controllers\Api\V1\Admin\LocationController;

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('admin/division')->group(function () {

        Route::post('/insert', [LocationController::class, 'insertDivision'])->middleware(['role_or_permission:super-admin|demo-graphic-create']);
        Route::get('/get',[LocationController::class, 'getAllDivisionPaginated'])->middleware(['role_or_permission:super-admin|demo-graphic-view']);
        Route::post('/update', [LocationController::class, 'divisionUpdate'])->middleware(['role_or_permission:super-admin|demo-graphic-update']);

        // Route::get('/edit/{id}', [RoleController::class, 'edit'])->middleware(['role_or_permission:super-admin|main-role-edit']);
        // Route::get('/destroy/{id}', [RoleController::class, 'destroy'])->middleware(['role_or_permission:super-admin|main-role-destroy']);
    });

});
