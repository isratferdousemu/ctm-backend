<?php

use App\Http\Controllers\Api\V1\Admin\RoleController;
use App\Http\Controllers\Api\V1\Setting\ActivityLogController;

Route::middleware('auth:sanctum')->group(function () {


    Route::post('admin/activity-log/all/filtered',[ActivityLogController::class, 'getAllActivityLogsPaginated'])->middleware(['role_or_permission:super-admin|main-setting-activity-log']);



    /* -------------------------------------------------------------------------- */
    /*                                 Role Routes                                */
    /* -------------------------------------------------------------------------- */

    Route::prefix('admin/role')->group(function () {

        Route::get('all/filtered',[RoleController::class, 'getAllRolePaginated'])->middleware(['role_or_permission:super-admin|main-role-list']);

        Route::post('/insert', [RoleController::class, 'insert'])->middleware(['role_or_permission:super-admin|main-role-store']);
        Route::get('/edit/{id}', [RoleController::class, 'edit'])->middleware(['role_or_permission:super-admin|main-role-edit']);
        Route::post('/update', [RoleController::class, 'updateRole'])->middleware(['role_or_permission:super-admin|main-role-update']);
        Route::get('/destroy/{id}', [RoleController::class, 'destroRole'])->middleware(['role_or_permission:super-admin|main-role-destroy']);

        /* -------------------------------------------------------------------------- */
        /*                              Permission Routes                             */
        /* -------------------------------------------------------------------------- */
        Route::prefix('permission')->group(function () {

            Route::get('roles/unassign',[RoleController::class, 'getUnAssignPermissionRole'])->middleware(['role_or_permission:super-admin|main-role-list']);
            Route::get('roles/all',[RoleController::class, 'getAllRole'])->middleware(['role_or_permission:super-admin|main-role-list']);
            Route::get('get',[RoleController::class, 'getAllPermission'])->middleware(['role_or_permission:super-admin|main-permission-list']);

            Route::post('/assign', [RoleController::class, 'AssignPermissionRole'])->middleware(['role_or_permission:super-admin|main-role-store']);

        });
    });
    

});
