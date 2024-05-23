<?php

use App\Http\Controllers\Mobile\V1\BeneficiaryController;
use App\Http\Controllers\Mobile\V1\BeneficiaryDashboardController;
use App\Http\Controllers\Mobile\V1\CommitteeController;
use App\Http\Controllers\Mobile\V1\CommitteePermissionController;
use App\Http\Controllers\Mobile\V1\LocationController;


Route::middleware(['auth:sanctum', 'language'])->group(function () {

    Route::prefix('mobile/beneficiary')->group(function () {
        Route::get('/getUserLocation', [BeneficiaryController::class, 'getUserLocation']);
        Route::get('/list', [BeneficiaryController::class, 'list'])->middleware(['role_or_permission:super-admin|beneficiaryInfo-view']);
        Route::get('/listDropDown', [BeneficiaryController::class, 'listDropDown']);
        Route::get('/show/{id}', [BeneficiaryController::class, 'show'])->middleware(['role_or_permission:super-admin|beneficiaryInfo-view']);
        Route::get('/get/{id}', [BeneficiaryController::class, 'get'])->middleware(['role_or_permission:super-admin|beneficiaryInfo-view']);
        Route::get('/getByBeneficiaryId/{beneficiary_id}', [BeneficiaryController::class, 'getByBeneficiaryId'])->middleware(['role_or_permission:super-admin|beneficiaryInfo-view']);
        Route::get('/edit/{id}', [BeneficiaryController::class, 'edit'])->middleware(['role_or_permission:super-admin|beneficiaryInfo-edit']);
        Route::post('/update/{id}', [BeneficiaryController::class, 'update'])->middleware(['role_or_permission:super-admin|beneficiaryInfo-edit']);
        Route::put('/delete/{id}', [BeneficiaryController::class, 'delete'])->middleware(['role_or_permission:super-admin|beneficiaryInfo-delete']);
        Route::get('/deletedList', [BeneficiaryController::class, 'deletedList'])->middleware(['role_or_permission:super-admin|beneficiaryDeleteList-view']);
        Route::get('/restore/{id}', [BeneficiaryController::class, 'restore'])->middleware(['role_or_permission:super-admin|beneficiaryDeleteList-view']);
        Route::get('/restore-inactive/{id}', [BeneficiaryController::class, 'restoreInactive'])->middleware(['role_or_permission:super-admin|beneficiaryInfo-view']);
        Route::get('/getListForReplace', [BeneficiaryController::class, 'getListForReplace'])->middleware(['role_or_permission:super-admin|beneficiaryReplacement-create']);
        Route::post('/replace/{id}', [BeneficiaryController::class, 'replaceSave'])->middleware(['role_or_permission:super-admin|beneficiaryReplacement-create']);
        Route::get('/replaceList', [BeneficiaryController::class, 'replaceList'])->middleware(['role_or_permission:super-admin|beneficiaryReplacement-view']);
        Route::get('/restore-replace/{id}', [BeneficiaryController::class, 'restoreReplace'])->middleware(['role_or_permission:super-admin|beneficiaryReplacement-view']);
        Route::post('/exit', [BeneficiaryController::class, 'exitSave'])->middleware(['role_or_permission:super-admin|beneficiaryExit-create']);
        Route::get('/exitList', [BeneficiaryController::class, 'exitList'])->middleware(['role_or_permission:super-admin|beneficiaryExit-view']);
        Route::get('/restore-exit/{id}', [BeneficiaryController::class, 'restoreExit'])->middleware(['role_or_permission:super-admin|beneficiaryExit-view']);
        Route::post('/shift', [BeneficiaryController::class, 'shiftingSave'])->middleware(['role_or_permission:super-admin|beneficiaryShifting-create']);
        Route::get('/shiftingList', [BeneficiaryController::class, 'shiftingList'])->middleware(['role_or_permission:super-admin|beneficiaryShifting-view']);
        Route::post('/locationShiftingSave', [BeneficiaryController::class, 'locationShiftingSave'])->middleware(['role_or_permission:super-admin|beneficiaryShifting-create']);
        Route::get('/locationShiftingList', [BeneficiaryController::class, 'locationShiftingList'])->middleware(['role_or_permission:super-admin|beneficiaryShifting-view']);
        // report
        Route::get('/getBeneficiaryListPdf', [BeneficiaryController::class, 'getBeneficiaryListPdf'])->middleware(['role_or_permission:super-admin|beneficiaryInfo-view']);
        Route::get('/getBeneficiaryExitListPdf', [BeneficiaryController::class, 'getBeneficiaryExitListPdf'])->middleware(['role_or_permission:super-admin|beneficiaryExit-view']);
        Route::get('/getBeneficiaryReplaceListPdf', [BeneficiaryController::class, 'getBeneficiaryReplaceListPdf'])->middleware(['role_or_permission:super-admin|beneficiaryReplacement-view']);
        Route::get('/getBeneficiaryShiftingListPdf', [BeneficiaryController::class, 'getBeneficiaryShiftingListPdf'])->middleware(['role_or_permission:super-admin|beneficiaryShifting-view']);
    });

    Route::prefix('admin/beneficiary-dashboard')->group(function () {
        Route::get('/getTotalBeneficiaries', [BeneficiaryDashboardController::class, 'getTotalBeneficiaries'])->middleware(['role_or_permission:super-admin|beneficiaryDashboard-view']);
        Route::get('/getLocationWiseBeneficiaries', [BeneficiaryDashboardController::class, 'getLocationWiseBeneficiaries'])->middleware(['role_or_permission:super-admin|beneficiaryDashboard-view']);
        Route::get('/getGenderWiseBeneficiaries', [BeneficiaryDashboardController::class, 'getGenderWiseBeneficiaries'])->middleware(['role_or_permission:super-admin|beneficiaryDashboard-view']);
        Route::get('/getYearWiseWaitingBeneficiaries', [BeneficiaryDashboardController::class, 'getYearWiseWaitingBeneficiaries'])->middleware(['role_or_permission:super-admin|beneficiaryDashboard-view']);
        Route::get('/getProgramWiseBeneficiaries', [BeneficiaryDashboardController::class, 'getProgramWiseBeneficiaries'])->middleware(['role_or_permission:super-admin|beneficiaryDashboard-view']);
        Route::get('/getAgeWiseBeneficiaries', [BeneficiaryDashboardController::class, 'getAgeWiseBeneficiaries'])->middleware(['role_or_permission:super-admin|beneficiaryDashboard-view']);
        Route::get('/getYearWiseProgramShifting', [BeneficiaryDashboardController::class, 'getYearWiseProgramShifting'])->middleware(['role_or_permission:super-admin|beneficiaryDashboard-view']);
    });

    Route::prefix('admin/committee')->group(function () {
        Route::post('/add', [CommitteeController::class, 'add'])->middleware(['role_or_permission:super-admin|committee-create']);
        Route::get('/list', [CommitteeController::class, 'list'])->middleware(['role_or_permission:super-admin|committee-view']);
        Route::get('/show/{id}', [CommitteeController::class, 'show'])->middleware(['role_or_permission:super-admin|committee-view']);
        Route::put('/update/{id}', [CommitteeController::class, 'update'])->middleware(['role_or_permission:super-admin|committee-edit']);
        Route::delete('/delete/{id}', [CommitteeController::class, 'delete'])->middleware(['role_or_permission:super-admin|committee-delete']);
        Route::get('/{typeId}/{locationId}', [LocationController::class, 'getCommitteesByLocation'])/*->middleware(['role_or_permission:super-admin|demo-graphic-view'])*/;
        // report
        Route::get('/getCommitteeListPdf', [CommitteeController::class, 'getCommitteeListPdf'])->middleware(['role_or_permission:super-admin|committee-view']);
    });

    Route::apiResource('admin/committee-permissions', CommitteePermissionController::class)
        ->only('index', 'store', 'destroy');
});


