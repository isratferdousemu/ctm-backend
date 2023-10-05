<?php

use App\Http\Controllers\Api\V1\Admin\RoleController;
use App\Http\Controllers\Api\V1\Setting\ActivityLogController;

Route::middleware('auth:sanctum')->group(function () {


    Route::post('admin/activity-log/all/filtered',[ActivityLogController::class, 'getAllActivityLogsPaginated'])->middleware(['role_or_permission:super-admin|main-setting-activity-log']);



    /* -------------------------------------------------------------------------- */
    /*                                 Role Routes                                */
    /* -------------------------------------------------------------------------- */

    Route::prefix('admin/role')->group(function () {

        Route::get('/get',[RoleController::class, 'getAllRolePaginated'])->middleware(['role_or_permission:super-admin|role-list']);
        Route::post('/insert', [RoleController::class, 'insert'])->middleware(['role_or_permission:super-admin|role-create']);
        Route::get('/edit/{id}', [RoleController::class, 'edit'])->middleware(['role_or_permission:super-admin|role-edit']);
        Route::post('/update', [RoleController::class, 'updateRole'])->middleware(['role_or_permission:super-admin|role-update']);
        Route::delete('/destroy/{id}', [RoleController::class, 'destroyRole'])->middleware(['role_or_permission:super-admin|role-delete']);

        /* -------------------------------------------------------------------------- */
        /*                              Permission Routes                             */
        /* -------------------------------------------------------------------------- */
        Route::prefix('permission')->group(function () {

            Route::get('roles/unassign',[RoleController::class, 'getUnAssignPermissionRole'])->middleware(['role_or_permission:super-admin|main-role-list']);
            Route::get('roles/all',[RoleController::class, 'getAllRole'])->middleware(['role_or_permission:super-admin|main-role-list']);
            Route::get('modules',[RoleController::class, 'getAllPermission'])->middleware(['role_or_permission:super-admin|main-permission-list']);

            Route::post('/assign', [RoleController::class, 'AssignPermissionRole'])->middleware(['role_or_permission:super-admin|main-role-store']);
            Route::get('/role_permission_edit/{id}', [RoleController::class, 'rolePermissionEdit'])->middleware(['role_or_permission:super-admin|main-role-store']);

        });
    });


});
