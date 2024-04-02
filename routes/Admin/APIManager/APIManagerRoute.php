<?php


use App\Http\Controllers\Api\V1\Admin\APIURLController;

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('admin')->group(function () {
        Route::apiResource('api-url', APIURLController::class);
        Route::post('api-url/update', [APIURLController::class, 'updateURL']);
            /*->parameter('api-url', 'apiUrl')*/;
    });


});
