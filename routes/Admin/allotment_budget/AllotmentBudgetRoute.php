<?php

use App\Http\Controllers\Api\V1\Admin\Budget\AllotmentController;
use App\Http\Controllers\Api\V1\Admin\Budget\BudgetController;
use App\Http\Controllers\Api\V1\Admin\Budget\DashboardController;

Route::middleware('auth:sanctum')->group(function () {
    /* -------------------------------------------------------------------------- */
    /*                               Budget Management  Routes                    */
    /* -------------------------------------------------------------------------- */
    Route::prefix('admin/budget')->group(function () {
        Route::get('/getUserLocation', [BudgetController::class, 'getUserLocation']);
        Route::get('/list', [BudgetController::class, 'list'])->middleware(['role_or_permission:super-admin|budget-view']);
        Route::post('/add', [BudgetController::class, 'add'])->middleware(['role_or_permission:super-admin|budget-create']);
        Route::get('/show/{id}', [BudgetController::class, 'show'])->middleware(['role_or_permission:super-admin|budget-view']);
        Route::put('/update/{id}', [BudgetController::class, 'update'])->middleware(['role_or_permission:super-admin|budget-edit']);
        Route::put('/approve/{id}', [BudgetController::class, 'approve'])->middleware(['role_or_permission:super-admin|budget-edit']);
        Route::delete('/delete/{id}', [BudgetController::class, 'delete'])->middleware(['role_or_permission:super-admin|budget-delete']);
        Route::get('/getProjection', [BudgetController::class, 'getProjection'])->middleware(['role_or_permission:super-admin|budget-create|budget-view']);
        // budget detail
        Route::get('/detail/list/{budget_id}', [BudgetController::class, 'detailList'])->middleware(['role_or_permission:super-admin|budget-view']);
        Route::put('/detail/update/{budget_id}', [BudgetController::class, 'detailUpdate'])->middleware(['role_or_permission:super-admin|budget-view']);
        // report
        Route::get('/detail/report/{budget_id}', [BudgetController::class, 'getBudgetDetailListPdf'])->middleware(['role_or_permission:super-admin|budget-view']);
        // dashboard
        Route::get('/dashboard/getBudgetAndAllotmentSummary', [DashboardController::class, 'getBudgetAndAllotmentSummary'])->middleware(['role_or_permission:super-admin|budget-view|allotment-view']);
        Route::get('/dashboard/currentBudgetAmount', [DashboardController::class, 'currentBudgetAmount'])->middleware(['role_or_permission:super-admin|budget-view|allotment-view']);
        Route::get('/dashboard/totalBeneficiaries', [DashboardController::class, 'totalBeneficiaries'])->middleware(['role_or_permission:super-admin|budget-view|allotment-view']);
    });

    /* -------------------------------------------------------------------------- */
    /*                               Allotment Management  Routes                 */
    /* -------------------------------------------------------------------------- */
    Route::prefix('admin/allotment')->group(function () {
        Route::get('/list', [AllotmentController::class, 'list'])->middleware(['role_or_permission:super-admin|allotment-view']);
        Route::get('/show/{id}', [AllotmentController::class, 'show'])->middleware(['role_or_permission:super-admin|allotment-view']);
        Route::put('/update/{id}', [AllotmentController::class, 'update'])->middleware(['role_or_permission:super-admin|allotment-edit']);
        Route::delete('/delete/{id}', [AllotmentController::class, 'delete'])->middleware(['role_or_permission:super-admin|allotment-delete']);
        // report
        Route::get('/report', [AllotmentController::class, 'report'])->middleware(['role_or_permission:super-admin|allotment-view']);
        // dashboard
        Route::get('/dashboard/totalAllotmentAmount', [DashboardController::class, 'totalAllotmentAmount'])->middleware(['role_or_permission:super-admin|budget-view|allotment-view']);
        Route::get('/dashboard/currentAllotmentAmount', [DashboardController::class, 'currentAllotmentAmount'])->middleware(['role_or_permission:super-admin|budget-view|allotment-view']);
    });


});
