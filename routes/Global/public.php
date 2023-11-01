<?php

use App\Http\Controllers\Api\V1\Admin\AdminController;
use App\Http\Controllers\Api\V1\Admin\ApplicationController;
use App\Http\Controllers\Api\V1\Admin\LocationController;
use App\Http\Controllers\Api\V1\GlobalController;

Route::prefix('global')->group(function () {
    Route::get('/program',[GlobalController::class, 'getAllProgram']);
    // Route::get('/device/details',[GlobalController::class, 'getDevice ']);
    Route::get('/lookup/get/{type}',[AdminController::class, 'getAllLookupByType']);
    Route::get('/division/get',[LocationController::class, 'getAllDivisionPaginated']);
    Route::get('/district/get/{division_id}',[LocationController::class, 'getAllDistrictByDivisionId']);
    Route::get('/union/get/{thana_id}',[LocationController::class, 'getAllUnionByThanaId']);
    Route::get('/thana/get/{district_id}',[LocationController::class, 'getAllThanaByDistrictId']);
    Route::get('/city/get/{district_id}/{location_type}',[LocationController::class, 'getAllCityByDistrictId']);
    Route::get('/thana/get/city/{city_id}',[LocationController::class, 'getAllThanaByCityId']);
    Route::get('/pmt',[GlobalController::class, 'getAllPMTVariableWithSub']);

    // online application
    Route::post('/online-application/card-verification',[ApplicationController::class, 'onlineApplicationVerifyCard']);
    Route::post('/online-application/dis-card-verification',[ApplicationController::class, 'onlineApplicationVerifyDISCard']);
});
