<?php

use App\Http\Controllers\Api\V1\Admin\ApplicationController;

Route::middleware('auth:sanctum')->group(function () {
    /* -------------------------------------------------------------------------- */
    /*                       APPLICATION SELECTION Routes                         */
    /* -------------------------------------------------------------------------- */
    Route::prefix('admin/application')->group(function () {

    Route::get('/get', [ApplicationController::class, 'getAllApplicationPaginated'])->middleware(['role_or_permission:super-admin|application-entry-view']);
    
    Route::get('get/{id}', [ApplicationController::class, 'getApplicationById'])->middleware(['role_or_permission:super-admin|application-entry-view']);


    });
        /* -------------------------------------------------------------------------- */
    /*                      Mobile Operator Route                         */
    /* -------------------------------------------------------------------------- */
    Route::prefix('admin/mobile-operator')->group(function () {

    Route::get('/get', [ApplicationController::class, 'getAllApplicationPaginated'])->middleware(['role_or_permission:super-admin|application-entry-view']);
    
    Route::get('get/{id}', [ApplicationController::class, 'getApplicationById'])->middleware(['role_or_permission:super-admin|application-entry-view']);


    });
});
