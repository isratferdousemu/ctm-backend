<?php


use App\Http\Controllers\Api\V1\Admin\APIURLController;
use App\Http\Controllers\APIController;

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('admin')->group(function () {
        Route::apiResource('api-url', APIURLController::class);
        Route::apiResource('api', APIController::class);
    });


});
