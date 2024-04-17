<?php


use App\Http\Controllers\Api\V1\Admin\ApiDataReceiveController;
use App\Http\Controllers\Api\V1\Admin\APIListController;
use App\Http\Controllers\Api\V1\Admin\APIURLController;

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('admin')->group(function () {
        Route::apiResource('api-url', APIURLController::class);
        Route::apiResource('api', APIListController::class);
        Route::apiResource('api-list', APIListController::class);

        Route::get('table-list', [APIListController::class, 'getTableList']);
        Route::get('get-modules', [APIListController::class, 'getModules']);
        Route::get('get-api-list', [APIListController::class, 'getApiList']);


        Route::apiResource('api-data-receive', ApiDataReceiveController::class);
    });


});
