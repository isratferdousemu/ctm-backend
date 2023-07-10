<?php

use App\Http\Controllers\Api\V1\Admin\LocationController;

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('admin/division')->group(function () {

        Route::post('/insert', [LocationController::class, 'insertDivision'])->middleware(['role_or_permission:super-admin|demo-graphic-create']);
        // Route::post('all/filtered',[BankController::class, 'getAllBankPaginated'])->middleware(['role_or_permission:super-admin|main-bank-view']);

        // Route::get('/edit/{id}', [RoleController::class, 'edit'])->middleware(['role_or_permission:super-admin|main-role-edit']);
        // Route::post('/update', [RoleController::class, 'update'])->middleware(['role_or_permission:super-admin|main-role-update']);
        // Route::get('/destroy/{id}', [RoleController::class, 'destroy'])->middleware(['role_or_permission:super-admin|main-role-destroy']);
    });

});
