<?php


use App\Http\Controllers\Client\ApplicationController;

Route::prefix('client')->group(function () {
    Route::get('application/get', [ApplicationController::class, 'getAllApplicationPaginated']);
    Route::get('application/get/{id}', [ApplicationController::class, 'getApplicationById']);

    Route::get('beneficiary/list', [ApplicationController::class, 'getApplicationById']);


});

