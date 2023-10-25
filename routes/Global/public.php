<?php

use App\Http\Controllers\Api\V1\Admin\AdminController;
use App\Http\Controllers\Api\V1\Admin\LocationController;
use App\Http\Controllers\Api\V1\GlobalController;

Route::prefix('global')->group(function () {
    Route::get('/program',[GlobalController::class, 'getAllProgram']);
    // Route::get('/device/details',[GlobalController::class, 'getDevice ']);
    Route::get('/lookup/get/{type}',[AdminController::class, 'getAllLookupByType']);
    Route::get('/division/get',[LocationController::class, 'getAllDivisionPaginated']);
    Route::get('/district/get/{division_id}',[LocationController::class, 'getAllDistrictByDivisionId']);


});
