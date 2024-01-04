<?php

use App\Http\Controllers\Api\V1\Admin\UserController;

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('admin/user')->group(function () {

        Route::post('/insert', [UserController::class, 'insertUser'])->middleware(['role_or_permission:super-admin|user-create']);
        // Route::post('/update/', [UserController::class, 'update'])->middleware(['role_or_permission:super-admin|user-edit']);
        Route::post('/update/{id}', [UserController::class, 'update'])->middleware(['role_or_permission:super-admin|user-edit']);
        Route::get('/get',[UserController::class, 'getAllUserPaginated'])->middleware(['role_or_permission:super-admin|office-head|user-view']);
        Route::post('/office/by-location', [UserController::class, 'getOfficeByLocationAssignId'])->middleware(['role_or_permission:super-admin|user-create']);
        Route::delete('/destroy/{id}', [UserController::class, 'destroyUser'])->middleware(['role_or_permission:super-admin|user-destroy']);
        Route::any('/approve/{id}', [UserController::class, 'approve'])->middleware(['role_or_permission:super-admin|user-edit']);


        Route::get('get-roles', [UserController::class, 'getRoles']);
        Route::get('get-users', [UserController::class, 'getUsersId']);






    });


});
