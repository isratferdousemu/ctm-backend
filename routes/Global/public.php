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
    Route::get('/union/pouro/get/{upazila_id}',[LocationController::class, 'getAllPouroByThanaId']);
    Route::get('/thana/get/{district_id}',[LocationController::class, 'getAllThanaByDistrictId']);
    Route::get('/city/get/{district_id}/{location_type}',[LocationController::class, 'getAllCityByDistrictId']);
    Route::get('/thana/get/city/{city_id}',[LocationController::class, 'getAllThanaByCityId']);
    Route::get('/ward/get/thana/{thana_id}',[LocationController::class, 'getAllWardByThanaId']);
    Route::get('/ward/get/pouro/{pouro_id}',[LocationController::class, 'getAllWardByPouroId']);
    Route::get('/ward/get/{union_id}',[LocationController::class, 'getAllWardByUnionId']);
    Route::get('ward/get/district_pouro/{district_pouro_id}',[LocationController::class, 'getAllWardByDistPouroId']);
    Route::get('/pmt',[GlobalController::class, 'getAllPMTVariableWithSub']);
    Route::get('/mobile-operator',[GlobalController::class, 'getAllMobileOperator']);

    // online application
    Route::post('/online-application/card-verification',[ApplicationController::class, 'onlineApplicationVerifyCard']);
    Route::post('/online-application/dis-card-verification',[ApplicationController::class, 'onlineApplicationVerifyDISCard']);
    Route::post('/online-application/registration',[ApplicationController::class, 'onlineApplicationRegistration']);
     Route::get('/applicants_copy',[ApplicationController::class, 'getApplicationCopyById']);
    Route::get('/online-application/check',[ApplicationController::class, 'onlineApplicationCheck']);

//    Route::get('/pdf', [\App\Http\Controllers\PDFController::class, 'index']);
    Route::get('/pdf', [\App\Http\Controllers\Api\V1\Admin\ReportController::class, 'unionReport']);

    Route::get('/class-list',[AdminController::class, 'getClassList'])/*->middleware(['role_or_permission:super-admin|demo-graphic-view'])*/;

});
