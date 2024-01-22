<?php

use App\Http\Controllers\Api\V1\Admin\BeneficiaryController;
use App\Http\Controllers\Api\V1\Admin\CommitteeController;
use App\Http\Controllers\Api\V1\Admin\CommitteePermissionController;


Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('admin/beneficiary')->group(function () {
        Route::get('/list', [BeneficiaryController::class, 'list'])->middleware(['role_or_permission:super-admin|demo-graphic-view']);
        Route::get('/show/{id}', [BeneficiaryController::class, 'show'])->middleware(['role_or_permission:super-admin|demo-graphic-view']);
        Route::get('/get/{id}', [BeneficiaryController::class, 'get'])->middleware(['role_or_permission:super-admin|demo-graphic-view']);
        Route::get('/edit/{id}', [BeneficiaryController::class, 'edit'])->middleware(['role_or_permission:super-admin|demo-graphic-view']);
        Route::put('/update/{id}', [BeneficiaryController::class, 'update'])->middleware(['role_or_permission:super-admin|demo-graphic-view']);
        Route::get('/replaceList', [BeneficiaryController::class, 'getListForReplace'])->middleware(['role_or_permission:super-admin|demo-graphic-view']);
        Route::put('/replace/{id}', [BeneficiaryController::class, 'replaceSave'])->middleware(['role_or_permission:super-admin|demo-graphic-view']);
        Route::put('/exit/{id}', [BeneficiaryController::class, 'exitSave'])->middleware(['role_or_permission:super-admin|demo-graphic-view']);
        Route::put('/shift', [BeneficiaryController::class, 'shiftingSave'])->middleware(['role_or_permission:super-admin|demo-graphic-view']);
    });

    Route::prefix('admin/committee')->group(function () {
        Route::post('/add', [CommitteeController::class, 'add'])->middleware(['role_or_permission:super-admin|demo-graphic-create']);
        Route::get('/list', [CommitteeController::class, 'list'])->middleware(['role_or_permission:super-admin|demo-graphic-view']);
        Route::get('/show/{id}', [CommitteeController::class, 'show'])->middleware(['role_or_permission:super-admin|demo-graphic-view']);
        Route::put('/update/{id}', [CommitteeController::class, 'update'])->middleware(['role_or_permission:super-admin|demo-graphic-update']);
        Route::delete('/delete/{id}', [CommitteeController::class, 'delete'])->middleware(['role_or_permission:super-admin|demo-graphic-destroy']);
        Route::get('/{typeId}/{locationId}', [CommitteeController::class, 'getCommitteesByLocation'])->middleware(['role_or_permission:super-admin|demo-graphic-view']);
    });

    Route::apiResource('admin/committee-permissions', CommitteePermissionController::class)
        ->only('index', 'store', 'destroy');
});


