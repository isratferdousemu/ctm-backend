<?php

use App\Http\Controllers\Api\V1\Admin\AllotmentController;
use App\Http\Controllers\Api\V1\Admin\BudgetController;

Route::middleware('auth:sanctum')->group(function () {
    /* -------------------------------------------------------------------------- */
    /*                               Budget Management  Routes                    */
    /* -------------------------------------------------------------------------- */
    Route::prefix('admin/budget')->group(function () {
        Route::get('/list', [BudgetController::class, 'list'])->middleware(['role_or_permission:super-admin|budget-view']);
        Route::post('/add', [BudgetController::class, 'add'])->middleware(['role_or_permission:super-admin|budget-create']);
        Route::get('/show/{id}', [BudgetController::class, 'show'])->middleware(['role_or_permission:super-admin|budget-view']);
        Route::post('/update/{id}', [BudgetController::class, 'update'])->middleware(['role_or_permission:super-admin|budget-edit']);
        Route::delete('/delete/{id}', [BudgetController::class, 'delete'])->middleware(['role_or_permission:super-admin|budget-delete']);
        Route::get('/getProjection', [BudgetController::class, 'getProjection'])->middleware(['role_or_permission:super-admin|budget-view']);
    });

    /* -------------------------------------------------------------------------- */
    /*                               Allotment Management  Routes                 */
    /* -------------------------------------------------------------------------- */
    Route::prefix('admin/allotment')->group(function () {
        Route::get('/list', [BudgetController::class, 'list'])->middleware(['role_or_permission:super-admin|budget-view']);
        Route::post('/add', [BudgetController::class, 'add'])->middleware(['role_or_permission:super-admin|budget-create']);
        Route::get('/show/{id}', [BudgetController::class, 'show'])->middleware(['role_or_permission:super-admin|budget-view']);
        Route::post('/update/{id}', [BudgetController::class, 'update'])->middleware(['role_or_permission:super-admin|budget-edit']);
        Route::delete('/delete/{id}', [BudgetController::class, 'delete'])->middleware(['role_or_permission:super-admin|budget-delete']);




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


});
