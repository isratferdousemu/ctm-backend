<?php

use App\Http\Controllers\Api\V1\Admin\UserController;

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('admin/user')->group(function () {

        Route::post('/insert', [UserController::class, 'insertUser'])->middleware(['role_or_permission:super-admin|demo-graphic-create']);
        Route::get('/get',[UserController::class, 'getAllUserPaginated'])->middleware(['role_or_permission:super-admin|user-list']);
        Route::post('/office/by-location', [UserController::class, 'getOfficeByLocationAssignId'])->middleware(['role_or_permission:super-admin|demo-graphic-create']);
    });


});
