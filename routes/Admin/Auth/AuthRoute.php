<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;

Route::controller(AuthController::class)->group(function () {
        //login
        Route::post('admin/login', 'LoginAdmin');
        //check token
        Route::post('auth/token/check', 'checkToken');
});

// Route::group(['prefix' => 'v1', 'middleware' => ['auth:sanctum']], function () {
//     //logout
//     Route::post('logout', [AuthController::class, 'logoutSpa']);
// });
