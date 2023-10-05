<?php

use App\Http\Controllers\Api\V1\Admin\UserController;

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('admin/user')->group(function () {

        Route::post('/insert', [UserController::class, 'insertUser'])->middleware(['role_or_permission:super-admin|user-create']);
         Route::get('/get',[UserController::class, 'getAllDivisionPaginated'])->middleware(['role_or_permission:super-admin|user-view']);
         Route::post('/update', [UserController::class, 'divisionUpdate'])->middleware(['role_or_permission:super-admin|user-update']);
         Route::get('/destroy/{id}', [UserController::class, 'destroyDivision'])->middleware(['role_or_permission:super-admin|user-delete']);
    });


});
