<?php


use App\Http\Controllers\Api\V1\Admin\APIURLController;
use App\Http\Controllers\APIListController;

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('admin')->group(function () {
        Route::apiResource('api-url', APIURLController::class);
        Route::apiResource('api', APIListController::class);
        Route::apiResource('api-list', APIListController::class);

        Route::get('table-list', [APIListController::class, 'getTableList']);
        Route::get('get-modules', [APIListController::class, 'getModules']);
    });


});
