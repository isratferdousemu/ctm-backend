<?php

use App\Http\Controllers\Api\V1\Admin\RoleController;
use App\Http\Controllers\Api\V1\Setting\ActivityLogController;

Route::middleware('auth:sanctum')->group(function () {


    Route::post('admin/activity-log/all/filtered',[ActivityLogController::class, 'getAllActivityLogsPaginated'])->middleware(['role_or_permission:super-admin|main-setting-activity-log']);

    Route::prefix('admin/role')->group(function () {

        Route::post('all/filtered',[BankController::class, 'getAllBankPaginated'])->middleware(['role_or_permission:super-admin|main-bank-view']);

        Route::post('/insert', [RoleController::class, 'insert'])->middleware(['role_or_permission:super-admin|main-role-store']);
        Route::get('/edit/{id}', [RoleController::class, 'edit'])->middleware(['role_or_permission:super-admin|main-role-edit']);
        Route::post('/update', [RoleController::class, 'update'])->middleware(['role_or_permission:super-admin|main-role-update']);
        Route::get('/destroy/{id}', [RoleController::class, 'destroy'])->middleware(['role_or_permission:super-admin|main-role-destroy']);
    });

});
