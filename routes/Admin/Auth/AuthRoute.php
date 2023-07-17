<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;

Route::controller(AuthController::class)->group(function () {
        //login
        Route::post('admin/login', 'LoginAdmin');
        //check token
        Route::post('auth/token/check', 'checkToken');
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    //logout
    Route::get('admin/tokens', [AuthController::class, 'adminTokens']);
    Route::get('admin/logout', [AuthController::class, 'LogoutAdmin']);
});
