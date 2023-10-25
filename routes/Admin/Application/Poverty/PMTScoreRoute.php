<?php


use App\Http\Controllers\Api\V1\Admin\PMTScoreController;
use App\Http\Controllers\Api\V1\Admin\VariableController;

Route::middleware('auth:sanctum')->group(function () {


    /* -------------------------------------------------------------------------- */
    /*                                PMT SCORE Routes                          */
    /* -------------------------------------------------------------------------- */
    Route::prefix('admin/poverty')->group(function () {

        //////////////////////////
        ///         CUTT OFF
        //////////////////////////
        Route::get('/get', [PMTScoreController::class, 'getAllPMTScorePaginated'])->middleware(['role_or_permission:super-admin|demo-graphic-view']);
        Route::post('/poverty-cut-off/insert', [PMTScoreController::class, 'insertDivisionCutOff'])->middleware(['role_or_permission:super-admin|demo-graphic-create']);
        Route::post('/poverty-cut-off/update', [PMTScoreController::class, 'updatePMTScore'])->middleware(['role_or_permission:super-admin|demo-graphic-create']);
        Route::post('/poverty-cut-off/filter', [PMTScoreController::class, 'getFiltered'])->middleware(['role_or_permission:super-admin|demo-graphic-create']);
       
        

        //////////////////////////
        ///    DISTRICT FIXED EFFECT
        //////////////////////////
        Route::get('/get/district-fixed-effect', [PMTScoreController::class, 'getAllDistrictFixedEffectPaginated'])->middleware(['role_or_permission:super-admin|demo-graphic-view']);
        Route::post('/district-fixed-effect/update', [PMTScoreController::class, 'updateDistrictFixedEffect'])->middleware(['role_or_permission:super-admin|demo-graphic-view']);
        


        //////////////////////////
        ///         VARIABLE
        //////////////////////////
        Route::get('/get/variable', [VariableController::class, 'getAllVariablePaginated'])->middleware(['role_or_permission:super-admin|demo-graphic-view']);
        Route::post('/variable/insert', [VariableController::class, 'insertVariable'])->middleware(['role_or_permission:super-admin|demo-graphic-create']);
        Route::post('/variable/update', [VariableController::class, 'updateVariable'])->middleware(['role_or_permission:super-admin|demo-graphic-view']);
        
        
        
        //////////////////////////
        ///         SUB VARIABLE
        //////////////////////////
        
        Route::get('/get/sub-variable', [VariableController::class, 'getAllSubVariablePaginated'])->middleware(['role_or_permission:super-admin|demo-graphic-view']);
        Route::post('/sub-variable/insert', [VariableController::class, 'insertSubVariable'])->middleware(['role_or_permission:super-admin|demo-graphic-create']);
        
        
        // Route::post('/poverty-cut-off/insert', [PMTScoreController::class, 'insertDivisionCutOff'])->middleware(['role_or_permission:super-admin|demo-graphic-create']);
        // Route::post('/poverty-cut-off/update', [PMTScoreController::class, 'updatePMTScore'])->middleware(['role_or_permission:super-admin|demo-graphic-create']);
        // Route::post('/poverty-cut-off/filter', [PMTScoreController::class, 'getFiltered'])->middleware(['role_or_permission:super-admin|demo-graphic-create']);
        // Route::post('/update', [PMTScoreController::class, 'committeeUpdate'])->middleware(['role_or_permission:super-admin|demo-graphic-update']);
        // Route::get('/destroy/{id}', [PMTScoreController::class, 'destroyPovertyScoreCuttOff'])->middleware(['role_or_permission:super-admin|demo-graphic-destroy']);
        // Route::get('/{id}', [PMTScoreController::class, 'editPovertyScoreCuttOff'])->middleware(['role_or_permission:super-admin|demo-graphic-destroy']);


    });
});
