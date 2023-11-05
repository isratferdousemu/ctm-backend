<?php

use App\Http\Controllers\Api\V1\Admin\AllotmentController;

Route::middleware('auth:sanctum')->group(function (){

    /* -------------------------------------------------------------------------- */
    /*                               Allotment program Management  Routes         */
    /* -------------------------------------------------------------------------- */
    Route::prefix('admin/allotment')->group(function (){
        Route::get('/get', [AllotmentController::class, 'index'])->middleware(['role_or_permission:super-admin|demo-graphic-create']);
        Route::get('/get_allowance_program', [AllotmentController::class, 'getAllowanceProgram'])->middleware(['role_or_permission:super-admin|demo-graphic-create']);
        Route::get('/get_allowance_amount/{id}', [AllotmentController::class, 'getAllowanceProgramAmount'])->middleware(['role_or_permission:super-admin|demo-graphic-create']);
        Route::get('/get_district', [AllotmentController::class, 'getDistrict'])->middleware(['role_or_permission:super-admin|demo-graphic-create']);
        Route::get('/get_location/{id}', [AllotmentController::class, 'getLocation'])->middleware(['role_or_permission:super-admin|demo-graphic-create']);
        Route::get('/get_financial_year', [AllotmentController::class, 'getFinancialYear'])->middleware(['role_or_permission:super-admin|demo-graphic-create']);
        Route::post('/insert', [AllotmentController::class, 'store'])->name('allotment.store')->middleware(['role_or_permission:super-admin|demo-graphic-create']);
        Route::get('/edit/{id}', [AllotmentController::class, 'edit'])->middleware(['role_or_permission:super-admin|demo-graphic-create']);
        Route::put('/update/{id}', [AllotmentController::class, 'update'])->name('allotment.update')->middleware(['role_or_permission:super-admin|demo-graphic-create']);
        Route::delete('/destroy/{id}', [AllotmentController::class, 'destroy'])->middleware(['role_or_permission:super-admin|demo-graphic-create']);
    });

    /* -------------------------------------------------------------------------- */
    /*                               Budget program Management  Routes            */
    /* -------------------------------------------------------------------------- */

});